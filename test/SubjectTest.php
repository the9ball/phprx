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

		$this->assertEquals( 4, $count );
		$this->assertEquals( 10, $sum );
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

		$this->assertEquals( 4, $count );
		$this->assertEquals( 50, $sum );
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

		$this->assertEquals( 2, $count );
		$this->assertEquals( 6, $sum );
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

		$this->assertEquals( 4, $count );
		$this->assertEquals( 10, $sum );
	}
}

