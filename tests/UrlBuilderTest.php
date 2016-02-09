<?php

namespace MergadoClientTest;

use MergadoClient\UrlBuilder;


class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @param $method
	 * @param array $args
	 * @param $expected
	 *
	 * @dataProvider providerTestAppendFromMethodReturnsExpected
	 */
	public function testAppendFromMethodWorkAsExpected($method, array $args, $expected){
		$urlBuilder = new UrlBuilder();

		$result = $urlBuilder->appendFromMethod($method, $args)->buildUrl();

		$this->assertEquals($result, $expected);

	}

	/**
	 * @return array
	 */
	public function providerTestAppendFromMethodReturnsExpected() {
		return [
			['stats', [6],'http://api.mergado.com/stats/6/'],
			['rules', ['blabla'], 'http://api.mergado.com/rules/'],
			['permisions', [10], 'http://api.mergado.com/permisions/10/'],
			['rUles', ['6'], 'http://api.mergado.com/rules/'],
			['project-id33', ['@#!'], 'http://api.mergado.com/project-id33/'],
			['pRojecT-Id', [44], 'http://api.mergado.com/project-id/44/']
		];
	}


	/**
	 * @param $name
	 * @param $expected
	 *
	 * @dataProvider providerTestAppendFromPropertyWorkAsExpected
	 */
	public function testAppendFromPropertyWorkAsExpected($name, $expected){
		$urlBuilder = new UrlBuilder();

		$result = $urlBuilder->appendFromProperty($name)->buildUrl();

		$this->assertEquals($result, $expected);
	}

	/**
	 * @return array
	 */
	public function providerTestAppendFromPropertyWorkAsExpected() {
		return [
			['stats', 'http://api.mergado.com/stats/'],
			['rules', 'http://api.mergado.com/rules/'],
			['permisions44', 'http://api.mergado.com/permisions44/'],
			['rUles', 'http://api.mergado.com/rules/'],
			['project-id', 'http://api.mergado.com/project-id/'],
			['pRojecT-Id', 'http://api.mergado.com/project-id/']
		];
	}


}