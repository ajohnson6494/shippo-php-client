<?php

namespace Shippo;

class Batch extends ApiResource {
    /**
    * @param string $class Ignored.
    *
    * @return string The class URL for this resource. It needs to be special
    *    cased because it doesn't fit into the standard resource pattern.
    *    The standard resource pattern is name + s, e.g. parcel becomes parcels.
    */
  public static function classUrl($class) {
    return "/batches";
  }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Batch Create a batch shipment object
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
     * @param string $id
     * @param string|null $apiKey
     *
     * @return Retrieve Retrieves a batch shipment
     */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }

     /**
     * @param string $id
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Add Adds shipments to a batch
     */
  public static function add($id, $params = null, $apiKey = null) {
    return self::scopedAddBatch(self::class, $id, $params, $apiKey);
  }

     /**
     * @param string $id
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Remove Removes shipments from a batch
     */
  public static function remove($id, $params = null, $apiKey = null) {
    return self::scopedRemoveBatch(self::class, $id, $params, $apiKey);
  }

     /**
     * @param string $id
     * @param string|null $apiKey
     *
     * @return Purchase Attempts to purchase a batch shipment
     */
  public static function purchase($id, $apiKey = null) {
    return self::scopedPurchaseBatch(self::class, $id, $apiKey);
  }
}
