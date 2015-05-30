<?php

require_once "Observer.php";
require_once "Observable.php";

class Subject implements IObserver, IObservable, IDisposable
{
	use TObserver, TObservable;

	public function SubscribeObserver( IObserver $observer )
	{
		$this->AddListener(
			function($x) use (&$observer) { $observer->onNext($x); },
			function() use (&$observer) { $observer->onCompleted(); },
			function($e) use (&$observer) { $observer->onError($e); }
		);

		return $this;
	}

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		$this->AddListener( $onNext, $onCompleted, $onError );
		return $this;
	}
}

