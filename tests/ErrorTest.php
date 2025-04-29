<?php

namespace Tests;

use Shippo\ShippoError;

class ErrorTest extends TestCase {
  public function testCreation() {
    try {
      throw new ShippoError("hello", 500, "{'foo':'bar'}", array(
            'foo' => 'bar'
        ));
      $this->fail("Did not raise error");
    } catch (ShippoError $e) {
      $this->assertEquals("hello", $e->getMessage());
      $this->assertEquals(500, $e->getHttpStatus());
      $this->assertEquals("{'foo':'bar'}", $e->getHttpBody());
      $this->assertEquals(array(
            'foo' => 'bar'
        ), $e->getJsonBody());
    }
  }
}
