<?php

namespace Shippo;

class Rate extends ApiResource {
  /**
   * @param array|null $params
   *
   * @return Retrieve Get a Rate.
   */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }
}
