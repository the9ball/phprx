<?php

require_once "Disposable.php";

interface IObserver
{
	public function OnNext( $value );
	public function OnCompleted();
	public function OnError( $error );
}

trait TObserver
{
	private $onNextListener;
	private $onCompletedListener;
	private $onErrorListener;
	private $disposed;

	function __construct()
	{
		$this->onNextListener      = array();
		$this->onCompletedListener = array();
		$this->onErrorListener     = array();
		$this->disposed = false;
	}

	public function OnNext( $value )
	{
		if ( true === $this->disposed ) return;
		foreach ( $this->onNextListener as $f ) $f( $value );
	}

	public function OnCompleted()
	{
		if ( true === $this->disposed ) return;
		foreach ( $this->onCompletedListener as $f ) $f();
	}

	public function OnError( $error )
	{
		if ( true === $this->disposed ) return;
		foreach ( $this->onErrorListener as $f ) $f( $error );
	}

	public function AddListener( callable $onNext, callable $onCompleted, callable $onError )
	{
		$this->onNextListener[]      = $onNext;
		$this->onCompletedListener[] = $onCompleted;
		$this->onErrorListener[]     = $onError;
	}

	public function Dispose()
	{
		$this->disposed = true;
		$this->onNextListener      = null;
		$this->onCompletedListener = null;
		$this->onErrorListener     = null;
	}
}

class AnonymouseObserver implements IObserver, IDisposable
{
	use TObserver;

	private $disposable;

	public static function CreateCallable( callable $onNext, callable $onCompleted, callable $onError, IDisposable $disposable )
	{
		$instance = new AnonymouseObserver();
		$instance->AddListener( $onNext, $onCompleted, $onError );
		$instance->disposable = $disposable;
		return $instance;
	}

	public static function CreateObserver( IObserver $observer, IDisposable $disposable )
	{
		return self::CreateCallable(
			function($x) use (&$observer) { $observer->onNext($x); },
			function() use (&$observer) { $observer->onCompleted(); },
			function($e) use (&$observer) { $observer->onError($e); },
			$disposable
		);
	}

	public function Dispose()
	{
		parent::Dispose();
		$this->disposable->Dispose();
	}
}

class Observer
{
	public static function CreateCallable( callable $onNext, callable $onCompleted, callable $onError, IDisposable $disposable )
	{
		return AnonymouseObserver::CreateCallable( $onNext, $onCompleted, $onError, $disposable );
	}

	public static function CreateObserver( IObserver $observer, IDisposable $disposable )
	{
		return AnonymouseObserver::CreateObserver( $observer, $disposable );
	}
}

