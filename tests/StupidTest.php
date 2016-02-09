<?php

namespace MergadoClientTest;

class StupidTest extends \PHPUnit_Framework_TestCase {

	public function testTrueIsTrue() {
		$foo = true;
		$this->assertTrue($foo);
	}

	public function testFalseIsFalse() {
		$foo = false;
		$this->assertFalse($foo);
	}

	public function testFalseIsNotTrue() {
		$foo = false;
		$this->assertNotTrue($foo);
	}

	public function testTrueIsNotFalse() {
		$foo = true;
		$this->assertNotFalse($foo);
	}

}