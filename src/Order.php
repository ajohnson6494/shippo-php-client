<?php

namespace Shippo;

class Order extends ApiResource {
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Order Create an order.
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
     * @param array|null $params
     *
     * @return Retrieve Get an order.
     */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }

    /**
     * @param array|null $params
     *
     * @return All Get all the orders.
     */
  public static function all($params = null, $apiKey = null) {
    return self::scopedAll(self::class, $params, $apiKey);
  }
}
