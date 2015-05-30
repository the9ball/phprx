<?php

require_once 'Subject.php';

class SubjectTest extends PHPUnit_Framework_TestCase
{
	public function testCallee()
	{
		$count = 0;
		$sum   = 0;

		$s = new Subject();
		$d = $s->Subscribe(
			function($x) use (&$count,&$sum) { ++$count; $sum+=$x; },
			function(){},
			function($e){}
		);

		$s->OnNext( 1 );
		$s->OnNext( 2 );
		$s->OnNext( 3 );
		$s->OnNext( 4 );
		$s->OnCompleted();
		$d->Dispose();

		$this->assertSame( 4, $count );
		$this->assertSame( 10, $sum );
	}

	public function testSelect()
	{
		$count = 0;
		$sum   = 0;

		$s = new Subject();
		$d = $s
			->Select(
				function($x){ return $x+10; }
			)
			->Subscribe(
				function($x) use (&$count,&$sum) { ++$count; $sum+=$x; },
				function(){},
				function($e){}
			);

		$s->OnNext( 1 );
		$s->OnNext( 2 );
		$s->OnNext( 3 );
		$s->OnNext( 4 );
		$s->OnCompleted();
		$d->Dispose();

		$this->assertSame( 4, $count );
		$this->assertSame( 50, $sum );
	}

	public function testWhere()
	{
		$count = 0;
		$sum   = 0;

		$s = new Subject();
		$d = $s
			->Where(
				function($x){ return 0 == ( $x % 2 ); }
			)
			->Subscribe(
				function($x) use (&$count,&$sum) { ++$count; $sum+=$x; },
				function(){},
				function($e){}
			);

		$s->OnNext( 1 );
		$s->OnNext( 2 );
		$s->OnNext( 3 );
		$s->OnNext( 4 );
		$s->OnCompleted();
		$d->Dispose();

		$this->assertSame( 2, $count );
		$this->assertSame( 6, $sum );
	}

	public function testDo()
	{
		$count = 0;
		$sum   = 0;

		$s = new Subject();
		$d = $s
			->Do_(
				function($x) use (&$count,&$sum) { ++$count; $sum+=$x; }
			)
			->Subscribe(
				function($x){},
				function(){},
				function($e){}
			);

		$s->OnNext( 1 );
		$s->OnNext( 2 );
		$s->OnNext( 3 );
		$s->OnNext( 4 );
		$s->OnCompleted();
		$d->Dispose();

		$this->assertSame( 4, $count );
		$this->assertSame( 10, $sum );
	}

	public function testMultiCallee()
	{
		$countA = 0;
		$countB = 0;

		$s = new Subject();
		$dA = $s
			->Where(
				function($x){ return 0 != ( $x % 2 ); }
			)
			->Subscribe(
				function($x) use (&$countA) { ++$countA; },
				function(){},
				function($e){}
			);
		$dB = $s
			->Where(
				function($x){ return 0 == ( $x % 2 ); }
			)
			->Subscribe(
				function($x) use (&$countB) { ++$countB; },
				function(){},
				function($e){}
			);

		$s->OnNext( 1 );
		$s->OnNext( 2 );
		$s->OnNext( 3 );
		$s->OnCompleted();
		$dA->Dispose();
		$dB->Dispose();

		$this->assertSame( 2, $countA );
		$this->assertSame( 1, $countB );
	}

	public function testMerge()
	{
		$a = new Subject();
		$b = new Subject();
		$l = array();

		$a->Merge( $b )
			->Subscribe(
				function($x)use(&$l){$l[]=$x;},
				function(){},
				function($e){}
			);

		$a->OnNext( 1 );
		$b->OnNext( 10 );
		$a->OnNext( 2 );
		$a->OnNext( 3 );
		$b->OnNext( 11 );

		$this->assertSame(
			array( 1, 10, 2, 3, 11 ), $l
		);
	}
}

