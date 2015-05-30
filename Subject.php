<?php

require_once "Observer.php";
require_once "Observable.php";

class Subject implements IObserver, IObservable, IDisposable
{
	use TObserver, TObservable;

	public function SubscribeObserver( IObserver $observer )
	{
		// TODO:add $observer to ListenerList

		$sd = new SingleAssignmentDisposable();

		$s = $this->subscribe;
		$sd->disposable = $s( $this );

		return $sd;
	}

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		$this->AddListener( $onNext, $onCompleted, $onError );
		return $this;
	}
}

