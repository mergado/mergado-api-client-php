<?php

namespace MergadoClientTest;

use MergadoClient\ApiClient as Api;

class ApiTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @param $methodOne
	 * @param $methodTwo
	 *
	 * @dataProvider providerTestApiBuildUrlBasic
	 *
	 */
	public function testApiBuildUrlBasicWithTwoMethods($methodOne, $methodTwo, $expected){

		$api = new Api();

		call_user_method($methodOne, $api);
		call_user_method($methodTwo, $api);

		$this->assertEquals($expected, $api->get());
	}

	public function providerTestApiBuildUrlBasic()
	{
		return array(
				array('stats', 'shops','http://app.mergado.com/api/stats/shops'),
				array('rules', 'project', 'http://app.mergado.com/api/rules/project'),
				array('permisions', 'useRs', 'http://app.mergado.com/api/permisions/users'),
				array('rules', 'nonsense_Asda', 'http://app.mergado.com/api/rules/nonsense_asda'),
				array('project-id', 'adaw', 'http://app.mergado.com/api/project-id/adaw')
		);
	}

	public function testFooReturnsObject()
	{

		$api = new Api();

		$res = $api->foo();

		$this->assertEquals('object', gettype($res));

	}

}