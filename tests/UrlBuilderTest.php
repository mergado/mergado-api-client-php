<?php

namespace MergadoClientTest;

use MergadoClient\UrlBuilder;
use PHPUnit\Framework\TestCase;


class UrlBuilderTest extends TestCase
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
			['stats', [6], 'https://api.mergado.com/stats/6/'],
			['rules', ['blabla'], 'https://api.mergado.com/rules/blabla/'],
			['permisions', [10], 'https://api.mergado.com/permisions/10/'],
			['rUles', ['6'], 'https://api.mergado.com/rules/6/'],
			['project-id33', ['@#!'], 'https://api.mergado.com/project-id33/%40%23%21/'],
			['pRojecT-Id', [44], 'https://api.mergado.com/project-id/44/']
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
			['stats', 'https://api.mergado.com/stats/'],
			['rules', 'https://api.mergado.com/rules/'],
			['permisions44', 'https://api.mergado.com/permisions44/'],
			['rUles', 'https://api.mergado.com/rules/'],
			['project-id', 'https://api.mergado.com/project-id/'],
			['pRojecT-Id', 'https://api.mergado.com/project-id/']
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
				], 'https://api.mergado.com/stats/6/?limit=20&offset=10&fields=name,email,gender'],
				['rules', ['blabla'], [
					'limit' => 20,
					'offset' => 10,
					'fields' => 'name,email,gender'
				], 'https://api.mergado.com/rules/blabla/?limit=20&offset=10&fields=name,email,gender'],
				['permisions', [10], [
					'limit' => 30,
					'fields' => 'name,email,gender'
				], 'https://api.mergado.com/permisions/10/?limit=30&fields=name,email,gender'],
				['rUles', ['6'], [
					'offset' => 10,
					'fields' => 'name,email,gender'
				], 'https://api.mergado.com/rules/6/?offset=10&fields=name,email,gender'],
				['project-id33', ['@#!'], [
					'limit' => 20
				], 'https://api.mergado.com/project-id33/%40%23%21/?limit=20'],
				['pRojecT-Id', [44], [
					'fields' => 'name,email,gender'
				], 'https://api.mergado.com/project-id/44/?fields=name,email,gender']
		];
	}

	/**
	 * @param $method
	 * @param array $args
	 * @param $expected
	 *
	 * @dataProvider providerCustomBaseUrlExpected
	 */
	public function testCustomBaseUrl($method, array $args, $expected){

		$urlBuilder = new UrlBuilder('http://example.com/api');

		$result = $urlBuilder->appendFromMethod($method, $args)->buildUrl();

		$this->assertEquals($result, $expected);

	}

	/**
	 * @return array
	 */
	public function providerCustomBaseUrlExpected() {
		return [
			['stats', [6], 'http://example.com/api/stats/6/'],
			['rules', ['blabla'], 'http://example.com/api/rules/blabla/'],
			['permisions', [10], 'http://example.com/api/permisions/10/'],
		];
	}

}