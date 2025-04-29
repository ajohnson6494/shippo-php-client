<?php

namespace Tests;

use Exception;
use Shippo\Shipment;

class RateTest extends TestCase {
  public function testValidCreate() {
    $rate = self::getDefaultRate();
    $this->assertFalse(is_null($rate->results));
  }

  public static function getDefaultRate() {
    $shipment = ShipmentTest::getDefaultShipment();
    try {
      Shipment::get_shipping_rates(array(
            'id' => $shipment->object_id,
            'currency' => 'USD'
        ));
    } catch (Exception $e) {
            // Expected Exception, rates not ready, prompting to server to generate
    }
    sleep(5);
    return Shipment::get_shipping_rates(array(
          'id' => $shipment->object_id,
          'currency' => 'USD'
      ));
  }
}
