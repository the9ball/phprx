<?php

interface IDisposable
{
	public function Dispose();
}

class EmptyDisposable implements IDisposable
{
	public function Dispose(){}
}

class BooleanDisposable implements IDisposable
{
	private $disposed;

	function __construct()
	{
		$this->disposed = false;
	}

	public function IsDisposed()
	{
		return $this->disposed;
	}

	public function Dispose()
	{
		$this->disposed = true;
	}
}

class SingleAssignmentDisposable implements IDisposable
{
	public $disposable;

	public function Dispose()
	{
		if ( null != $this->disposable ) $this->disposable->Dispose();
	}
}

class CompositeDisposable extends BooleanDisposable
{
	public $disposables;

	function __construct()
	{
		parent::__construct();
		$this->disposable = array();
	}

	public function AddTo( IDisposable $disposable )
	{
		if ( $this->IsDisposed() )
		{
			$disposable->Dispose();
		}
		else
		{
			$this->disposable[] = $disposable;
		}
	}

	public function Clear()
	{
		$temp = $this->disposable;
		$this->disposable = array();
		foreach ( $temp as $d ) $d->Dispose();
	}

	public function Dispose()
	{
		parent::Dispose();
		$temp = $this->disposable;
		$this->disposable = null;
		foreach ( $temp as $d ) $d->Dispose();
	}
}

