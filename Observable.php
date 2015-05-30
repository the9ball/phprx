<?php

require_once "Disposable.php";
require_once "Observer.php";

interface IObservable
{
	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError );
}

trait TObservable
{
}

class AnonymouseObservable implements IObservable
{
	use TObservable;

	private $observer;

	function __construct( IObserver $observer )
	{
		$this->observer = $observer;
	}

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		return Observer::Create(
			$this->observer->onNext,
			$onCompleted,
			$onError
		);
	}
}

class Observable
{
}

