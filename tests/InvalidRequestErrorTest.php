<?php

namespace Tests;

use Shippo\Address;
use Shippo\InvalidRequestError;

class InvalidRequestErrorTest extends TestCase {
  public function testInvalidObject() {
    try {
      Address::retrieve('invalid');
    } catch (InvalidRequestError $e) {
      $this->assertEquals(404, $e->getHttpStatus());
    }
  }
}
