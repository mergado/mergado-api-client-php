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
			['stats', [6],'http://app.mergado.com/api/stats/6'],
			['rules', ['blabla'], 'http://app.mergado.com/api/rules/blabla'],
			['permisions', [10], 'http://app.mergado.com/api/permisions/10'],
			['rUles', ['6'], 'http://app.mergado.com/api/rules/6'],
			['project-id33', ['@#!'], 'http://app.mergado.com/api/project-id33/%40%23%21'],
			['pRojecT-Id', [44], 'http://app.mergado.com/api/project-id/44']
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
			['stats', 'http://app.mergado.com/api/stats'],
			['rules', 'http://app.mergado.com/api/rules'],
			['permisions44', 'http://app.mergado.com/api/permisions44'],
			['rUles', 'http://app.mergado.com/api/rules'],
			['project-id', 'http://app.mergado.com/api/project-id'],
			['pRojecT-Id', 'http://app.mergado.com/api/project-id']
		];
	}

	/**
	 * @param $method
	 * @param $arg
	 * @param $queryParams
	 * @param $expected
	 *
	 * @dataProvider providerTestAddQueryParamsWorkAsExpected
	 */
	public function testAddQueryParamsWorkAsExpected ($method, $args, $queryParams, $expected) {
		$urlBuilder = new UrlBuilder();

		$result = $urlBuilder->appendFromMethod($method, $args);

		foreach ($queryParams as $key => $value) {
			$result = $result->addQueryParam($key, $value);
		}

		$result = $result->buildUrl();

		$this->assertEquals($expected, $result);
	}


	public function providerTestAddQueryParamsWorkAsExpected () {
		return [
				['stats', [6], [
					'limit' => 20,
					'offset' => 10,
					'fields' => 'name,email,gender'
				], 'http://app.mergado.com/api/stats/6?limit=20&offset=10&fields=name,email,gender'],
				['rules', ['blabla'], [
					'limit' => 20,
					'offset' => 10,
					'fields' => 'name,email,gender'
				], 'http://app.mergado.com/api/rules/blabla?limit=20&offset=10&fields=name,email,gender'],
				['permisions', [10], [
					'limit' => 30,
					'fields' => 'name,email,gender'
				], 'http://app.mergado.com/api/permisions/10?limit=30&fields=name,email,gender'],
				['rUles', ['6'], [
					'offset' => 10,
					'fields' => 'name,email,gender'
				], 'http://app.mergado.com/api/rules/6?offset=10&fields=name,email,gender'],
				['project-id33', ['@#!'], [
					'limit' => 20
				], 'http://app.mergado.com/api/project-id33/%40%23%21?limit=20'],
				['pRojecT-Id', [44], [
					'fields' => 'name,email,gender'
				], 'http://app.mergado.com/api/project-id/44?fields=name,email,gender']
		];
	}

}