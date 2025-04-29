<?php

namespace Shippo;

class Shipment extends ApiResource {
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Shipment Create a Shipment.
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
     * @param string $id
     * @param string|null $apiKey
     *
     * @return Retrieve Get a Shipment.
     */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return All Get all the Shipments.
     */
  public static function all($params = null, $apiKey = null) {
    return self::scopedAll(self::class, $params, $apiKey);
  }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Get_Shipping_Rates Get the rates for a Shipment.
     */
  public static function get_shipping_rates($params = null, $apiKey = null) {
    $id = $params['id'];
    return self::scopedGet(self::class, $id, $params, $apiKey = null);
  }
}
