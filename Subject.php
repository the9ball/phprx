<?php

require_once "Observer.php";
require_once "Observable.php";

class Subject implements IObserver, IDisposable
{
	use TObserver, TObservable;

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		$this->AddListener( $onNext, $onCompleted, $onError );
		return $this;
	}
}

