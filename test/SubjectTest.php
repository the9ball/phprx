<?php

require_once 'Subject.php';

class SubjectTest extends PHPUnit_Framework_TestCase
{
	public function testSubjectCallee()
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
}

