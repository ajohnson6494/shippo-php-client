<?php

namespace Shippo;

class Transaction extends ApiResource {
    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Transaction Create a Transaction.
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
     * @param array|null $params
     *
     * @return Retrieve Get a Transaction.
     */
  public static function retrieve($id, $apiKey = null) {
    return self::scopedRetrieve(self::class, $id, $apiKey);
  }

    /**
     * @param array|null $params
     *
     * @return All Get all the Transactions.
     */
  public static function all($params = null, $apiKey = null) {
    return self::scopedAll(self::class, $params, $apiKey);
  }
}
