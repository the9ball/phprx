<?php

require_once "Disposable.php";
require_once "Observer.php";

interface IObservable
{
	public function SubscribeObserver( IObserver $observer );
}

trait TObservable
{
	public function Select( callable $selector )
	{
		return Observable::Create(
			function($o) use ($selector)
			{
				return $this->Subscribe(
					function($x) use ($selector, $o) {
						$o->OnNext( $selector( $x ) );
					},
					function() use ($o) { $o->OnCompleted(); },
					function($e) use ($o) { $o->OnError($e); }
				);
			}
		);
	}

	public function Where( callable $filter )
	{
		return Observable::Create(
			function($o) use ($filter)
			{
				return $this->Subscribe(
					function($x) use ($filter, $o) {
						if ( $filter( $x ) ) $o->OnNext( $x );
					},
					function() use ($o) { $o->OnCompleted(); },
					function($e) use ($o) { $o->OnError($e); }
				);
			}
		);
	}

	// TODO:rename
	public function Do_( callable $function )
	{
		return Observable::Create(
			function($o) use ($function)
			{
				return $this->Subscribe(
					function(&$x) use ($function, $o) {
						$function( $x );
						$o->OnNext( $x );
					},
					function() use ($o) { $o->OnCompleted(); },
					function($e) use ($o) { $o->OnError($e); }
				);
			}
		);
	}
}

class AnonymouseObservable implements IObservable
{
	use TObservable;

	private $subscribe;
	private $observer;

	function __construct( callable $subscribe )
	{
		$this->subscribe = $subscribe;
	}

	public function SubscribeObserver( IObserver $observer )
	{
		$sd = new SingleAssignmentDisposable();

		$this->observer = Observer::CreateObserver( $observer, $sd );

		$s = $this->subscribe;
		$sd->disposable = $s( $this->observer );

		return $sd;
	}

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		return $this->SubscribeObserver(
			Observer::CreateCallable( $onNext, $onCompleted, $onError, new EmptyDisposable() )
		);
	}
}

class Observable
{
	public static function Create( callable $subscribe )
	{
		return new AnonymouseObservable( $subscribe );
	}
}

