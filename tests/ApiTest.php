<?php

namespace MergadoClientTest;

use MergadoClient\Api;

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
				array('stats', 'shops','http://api.mergado.com/stats/shops/'),
				array('rules', 'project', 'http://api.mergado.com/rules/project/'),
				array('permisions', 'useRs', 'http://api.mergado.com/permisions/users/'),
				array('rules', 'nonsense_Asda', 'http://api.mergado.com/rules/nonsense_asda/'),
				array('project-id', 'adaw', 'http://api.mergado.com/project-id/adaw/')
		);
	}

	public function testFooReturnsObject()
	{

		$api = new Api();

		$res = $api->foo();

		$this->assertEquals('object', gettype($res));

	}

}