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
	public $onNextListener;
	public $onCompletedListener;
	public $onErrorListener;
	private $disposed = false;

	public function OnNext( $value )
	{
		if ( true === $this->disposed ) return;
		$f = $this->onNextListener;
		if ( null != $f ) $f( $value );
	}

	public function OnCompleted()
	{
		if ( true === $this->disposed ) return;
		$f = $this->onCompletedListener;
		if ( null != $f ) $f();
	}

	public function OnError( $error )
	{
		if ( true === $this->disposed ) return;
		$f = $this->onErrorListener;
		if ( null != $f ) $f( $value );
	}

	public function AddListener( callable $onNext, callable $onCompleted, callable $onError )
	{
		$this->onNextListener      = $onNext;
		$this->onCompletedListener = $onCompleted;
		$this->onErrorListener     = $onError;
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

	function __construct( callable $onNext, callable $onCompleted, callable $onError, IDisposable $disposable )
	{
		$this->AddListener( $onNext, $onCompleted, $onError );
		$this->disposable = $disposable;
	}

	public function Dispose()
	{
		parent::Dispose();
		$this->disposable->Dispose();
	}
}

class Observer
{
	public static function Create( callable $onNext, callable $onCompleted, callable $onError, IDisposable $disposable )
	{
		return new AnonymouseObserver( $onNext, $onCompleted, $onError, $disposable );
	}
}

