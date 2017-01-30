<?php

namespace MergadoClientTest;

use PHPUnit\Framework\TestCase;

class StupidTest extends TestCase
{

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