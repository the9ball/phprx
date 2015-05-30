<?php

interface IDisposable
{
	public function Dispose();
}

class EmptyDisposable implements IDisposable
{
	public function Dispose(){}
}

class SingleAssignmentDisposable implements IDisposable
{
	public $disposable;

	public function Dispose()
	{
		if ( null != $this->disposable ) $this->disposable->Dispose();
	}
}

