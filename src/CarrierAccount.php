<?php

namespace Shippo;

class CarrierAccount extends ApiResource {
    /**
    * @param string $class Ignored.
    *
    * @return string The class URL for this resource. It needs to be special
    *    cased because it doesn't fit into the standard resource pattern.
    *    The standard resource pattern is name + s, e.g. parcel becomes parcels.
    */
  public static function classUrl($class) {
    return "/carrier_accounts";
  }

    /**
    * @param array|null $params
    * @param string|null $apiKey
    *
    * @return CarrierAccount Create a carrier account.
    */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
    * @param array|null $params
    *
    * @return Retrieve Get a carrier account.
    */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }

    /**
    * @param array|null $params
    *
    * @return All Get all the carrier account.
    */
  public static function all($params = null, $apiKey = null) {
    return self::scopedAll(self::class, $params, $apiKey);
  }

    /**
    * @param id of the CarrierAccount to be updated
    *
    * @return Retrieve Get a carrier account.
    */
  public static function update($id, $params, $apiKey = null) {
    return self::scopedUpdate(self::class, $id, $params, $apiKey);
  }
}
