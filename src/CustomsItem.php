<?php

namespace Shippo;

class CustomsItem extends ApiResource {
    /**
     * @param string $class Ignored.
     *
     * @return string The class URL for this resource. It needs to be special
     *    cased because it doesn't fit into the standard resource pattern.
     *    The standard resource pattern is name + s, e.g. parcel becomes parcels.
     */
  public static function classUrl($class) {
    return "/customs/items";
  }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return CustomsItem Create a customs item.
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
     * @param array|null $params
     *
     * @return Retrieve Get a customs item.
     */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }

    /**
     * @param array|null $params
     *
     * @return All Get all the customs items.
     */
  public static function all($params = null, $apiKey = null) {
    return self::scopedAll(self::class, $params, $apiKey);
  }
}
