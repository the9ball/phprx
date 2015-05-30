<?php

require_once 'Disposable.php';

class DisposableTest extends PHPUnit_Framework_TestCase
{
	public function testBooleanDisposable()
	{
		$d = new BooleanDisposable();
		$this->assertFalse( $d->IsDisposed() );
		$d->Dispose();
		$this->assertTrue( $d->IsDisposed() );
	}

	public function testCompositeDisposableDispose()
	{
		$d = new CompositeDisposable();
		$a = new BooleanDisposable();
		$b = new BooleanDisposable();
		$c = new BooleanDisposable();
		$d->AddTo( $a );
		$d->AddTo( $b );
		$d->Dispose();
		$d->AddTo( $c );
		$this->assertTrue( $a->IsDisposed() );
		$this->assertTrue( $b->IsDisposed() );
		$this->assertTrue( $c->IsDisposed() );
	}

	public function testCompositeDisposableClear()
	{
		$d = new CompositeDisposable();
		$a = new BooleanDisposable();
		$b = new BooleanDisposable();
		$c = new BooleanDisposable();
		$d->AddTo( $a );
		$d->AddTo( $b );
		$d->Clear();
		$d->AddTo( $c );
		$this->assertTrue( $a->IsDisposed() );
		$this->assertTrue( $b->IsDisposed() );
		$this->assertFalse( $c->IsDisposed() );
	}
}

