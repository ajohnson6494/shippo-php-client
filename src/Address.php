<?php

namespace Shippo;

class Address extends ApiResource {
  /**
   * @param string $class Ignored.
   *
   * @return string The class URL for this resource. It needs to be special
   *    cased because it doesn't fit into the standard resource pattern.
   *    The standard resource pattern is name + s, e.g. parcel becomes parcels.
   */
  public static function classUrl($class) {
    return "/addresses";
  }

  public static function create(?array $params = null, ?string $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

  /**
   * @param array|null $params
   *
   * @return Retrieve Get an address.
   */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(static::class, $id, $apiKey);
  }

  /**
   * @param array|null $params
   *
   * @return All Get all the addresses.
   */
  public static function all($params = null, $apiKey = null) {
    return self::scopedAll(static::class, $params, $apiKey);
  }

  /**
   * @param array|null $params
   *
   * @return Validate Validate an address.
   */
  public static function validate($id) {
    return self::scopedValidate(static::class, $id);
  }
}
