<?php

require_once "Observer.php";
require_once "Observable.php";

class Subject implements IObserver, IObservable
{
	use TObserver, TObservable;

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		$this->onNextListener      = $onNext;
		$this->onCompletedListener = $onCompleted;
		$this->onErrorListener     = $onError;
		return $this;
	}
}

