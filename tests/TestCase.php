<?php

namespace Tests;

use Shippo\Shippo;
use Shippo\CurlClient;
use Shippo\ApiRequestor;

class TestCase extends \PHPUnit\Framework\TestCase {
  public const SHIPPO_KEY = 'shippo_test_cf1b6d0655e59fc6316880580765066038ef20d8';
  public const SHIPPO_API_VERSION = '2018-02-08';

    //mock curl client for mocking requests
  private $mock;

  public function setUp(): void {
    self::authFromEnv();
    self::apiVersionFromEnv();
    ApiRequestor::setHttpClient(CurlClient::instance());
    $this->mock = null;
  }

  protected static function authFromEnv() {
    $apiKey = getenv('SHIPPO_API_KEY');
    if (!$apiKey) {
      $apiKey = self::SHIPPO_KEY;
    }

    Shippo::setApiKey($apiKey);
  }

  protected static function apiVersionFromEnv() {
    $apiVersion = getenv('SHIPPO_API_VERSION');
    if (!$apiVersion) {
      $apiVersion = self::SHIPPO_API_VERSION;
    }

    Shippo::setApiVersion($apiVersion);
  }

  protected function mockRequest(
      $method,
      $path,
      $params = array(),
      $return = array(),
      $rcode = 200
  ) {
    $mock = $this->setMockObject();
    $mock->expects($this->any())
             ->method('request')
             ->with(
                 strtolower($method),
                 Shippo::$apiBase . $path,
                 $this->anything(),
                 $params
             )
             ->willReturn(array(json_encode($return), $rcode));
  }

  protected function setMockObject() {
    if (!$this->mock) {
      $this->mock = $this->createMock(CurlClient::class);
      ApiRequestor::setHttpClient($this->mock);
    }
    return $this->mock;
  }
}
