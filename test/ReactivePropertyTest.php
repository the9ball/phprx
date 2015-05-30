<?php

require_once 'ReactiveProperty.php';

class ReactivePropertyTest extends PHPUnit_Framework_TestCase
{
	public function testCallee()
	{
		$count = 0;
		$rp = new ReactiveProperty();
		$d  = $rp->Subscribe(
			function($x) use (&$count) { ++$count; },
			function(){},
			function($e){}
		);

		$rp->SetValue( 1 );
		$rp->SetValue( 2 );
		$rp->SetValue( 3 );
		$d->Dispose();

		$this->assertEquals( 3, $count );
		$this->assertEquals( 3, $rp->GetValue() );
	}

	public function testSelect()
	{
		$count = 0;
		$last  = null;
		$rp = new ReactiveProperty();
		$d  = $rp
			->Select(
				function($x){ return $x + 10; }
			)
			->Subscribe(
				function($x) use (&$count,&$last) { ++$count; $last=$x; },
				function(){},
				function($e){}
			);

		$rp->SetValue( 1 );
		$rp->SetValue( 2 );
		$rp->SetValue( 3 );
		$d->Dispose();

		$this->assertEquals( 3, $count );
		$this->assertEquals( 3, $rp->GetValue() );
		$this->assertEquals( 13, $last );
	}

	public function testWhere()
	{
		$count = 0;
		$rp = new ReactiveProperty();
		$d  = $rp
			->Where(
				function($x){ return 0 == ( $x % 2 ); }
			)
			->Subscribe(
				function($x) use (&$count) { ++$count; },
				function(){},
				function($e){}
			);

		$rp->SetValue( 1 );
		$rp->SetValue( 2 );
		$rp->SetValue( 3 );
		$d->Dispose();

		$this->assertEquals( 1, $count );
		$this->assertEquals( 3, $rp->GetValue() );
	}
}

