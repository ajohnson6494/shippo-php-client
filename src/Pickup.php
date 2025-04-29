<?php

namespace Shippo;

class Pickup extends ApiResource {
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Pickup Create a pickup.
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }
}
