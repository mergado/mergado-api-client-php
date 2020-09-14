<?php

namespace MergadoClientTest;

use MergadoClient\ApiClient;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ApiClientTest extends TestCase {

	const URL = 'https://example.com/api';

	public function test1(){

		$url = (string) ApiClient::call('token', self::URL)
			->me();
		Assert::assertSame('https://example.com/api/me/', $url);

	}

	public function test2(){

		$url = (string) ApiClient::call('token', self::URL)
			->apps();
		Assert::assertSame('https://example.com/api/apps/', $url);

	}

	public function test3(){

		$url = (string) ApiClient::call('token', self::URL)
			->users(1)
			->shops()
			->limit(100)
			->offset(0);
		Assert::assertSame('https://example.com/api/users/1/shops/?limit=100&offset=0', $url);

	}

	public function test4(){

		$url = (string) ApiClient::call('token', self::URL)
			->projects(1234)
			->queries();
		Assert::assertSame('https://example.com/api/projects/1234/queries/', $url);

	}

}
