<?php

require_once "Observable.php";
require_once "Subject.php";

interface IReadOnlyReactiveProperty extends IObservable
{
	public function GetValue();
}

interface IReactiveProperty extends IReadOnlyReactiveProperty
{
	public function SetValue( $value );
}

trait TReactiveProperty
{
	private $value;
	private $subject;

	function __construct()
	{
		$this->subject = new Subject();
	}

	function __destruct()
	{
		$this->subject->OnCompleted();
	}

	public function GetValue()
	{
		return $this->value;
	}

	public function SubscribeObserver( IObserver $observer )
	{
		return $this->subject->SubscribeObserver( $observer );
	}

	public function Subscribe( callable $onNext, callable $onCompleted, callable $onError )
	{
		return $this->subject->Subscribe( $onNext, $onCompleted, $onError );
	}

	public function SetValue( $value )
	{
		$this->value = $value;
		$this->subject->OnNext( $value );
	}
}

class ReadOnlyReactiveProperty implements IReadOnlyReactiveProperty
{
	use TObservable, TReactiveProperty { SetValue as private; }

	public static function CreateEmpty()
	{
		$instance = new ReadOnlyReactiveProperty();
		$instance->value = null;
		return $instance;
	}

	public static function CreateDefaultValue( $defaultValue )
	{
		$instance = new ReadOnlyReactiveProperty();
		$instance->SetValue( $defaultValue );
		return $instance;
	}

	public static function CreateObservable( IObservable $source )
	{
		$instance = new ReadOnlyReactiveProperty();
		$source->Subscribe(
			function($x) use (&$instance) {
				$instance->SetValue( $x );
			},
			function(){},
			function($e){}
		);
		return $instance;
	}
}


class ReactiveProperty implements IReactiveProperty
{
	use TObservable, TReactiveProperty;

	public static function CreateEmpty()
	{
		$instance = new ReactiveProperty();
		$instance->value = null;
		return $instance;
	}

	public static function CreateDefaultValue( $defaultValue )
	{
		$instance = new ReactiveProperty();
		$instance->SetValue( $defaultValue );
		return $instance;
	}

	public static function CreateObservable( IObservable $source )
	{
		$instance = new ReactiveProperty();
		$source->Subscribe(
			function($x) use (&$instance) {
				$instance->SetValue( $x );
			},
			function(){},
			function($e){}
		);
		return $instance;
	}
}

