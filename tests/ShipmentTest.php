<?php

namespace Tests;

use Exception;
use Shippo\Shipment;

class ShipmentTest extends TestCase {
  public function testValidCreate() {
    $shipment = self::getDefaultShipment();
    $this->assertEquals($shipment->status, 'SUCCESS');
  }

  public function testInvalidCreate() {
    try {
      $shipment = Shipment::create(['invalid_data' => 'invalid']);
    } catch (Exception $e) {
      $this->assertTrue(true);
    }
  }

  public function testRetrieve() {
    $shipment = self::getDefaultShipment();
    $retrieve_shipment = Shipment::retrieve($shipment->object_id);
    $this->assertEquals($retrieve_shipment->object_id, $shipment->object_id);
  }

  public function testListAll() {
    $list = Shipment::all(array(
          'results' => '3',
          'page' => '1'
      ));
    $this->assertFalse(is_null($list->results));
  }

  public function testListPageSize() {
    $pagesize = 1;
    $list = Shipment::all(array(
          'results' => $pagesize,
          'page' => '1'
      ));
    $this->assertEquals(count($list->results), $pagesize);
  }

  public static function getDefaultShipment() {
    $addressFrom = AddressTest::getDefaultAddress();
    $addressTo = AddressTest::getDefaultAddress_2();
    $parcel = ParcelTest::getDefaultParcel();
    return Shipment::create(array(
          'address_from' => $addressFrom->object_id,
          'address_to' => $addressTo->object_id,
          'parcels' => array($parcel->object_id),
          'extra' => array(
              'signature_confirmation' => 'True',
              'insurance' => array(
                  'amount' => '30',
                  'currency' => 'USD'
              ),
              'reference_1' => '',
              'reference_2' => '',
          ),
          'customs_declaration' => '',
          'metadata' => 'Customer ID 123456',
          'async' => 'False'
      ));
  }
}
