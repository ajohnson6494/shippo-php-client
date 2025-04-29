<?php

namespace Tests;

use Shippo\Shippo;
use Shippo\Address;
use Shippo\AuthenticationError;

class AuthenticationErrorTest extends TestCase {
  public function testInvalidCredentials() {
    Shippo::setApiKey('invalid');

    try {
      $address = Address::create();
    } catch (AuthenticationError $e) {
      $this->assertEquals(401, $e->getHttpStatus());
    }
  }
}
