<?php

namespace Shippo;

abstract class ApiResource extends ShippoObject {
  protected static function scopedRetrieve($class, $id, $apiKey = null) {
    $instance = new $class($id, $apiKey);
    $instance->refresh();
    return $instance;
  }

    /**
     * @returns ApiResource The refreshed resource.
     */
  public function refresh() {
    $requestor = new ApiRequestor($this->apiKey);
    $url = $this->instanceUrl();

    list($response, $apiKey) = $requestor->request('get', $url, $this->retrieveOptions);
    $this->refreshFrom($response, $apiKey);
    return $this;
  }

    /**
     * @param string $class
     *
     * @returns string The name of the class, with namespacing and underscores
     *    stripped.
     */
  public static function className($class) {
    if ($postfixNamespaces = strrchr($class, '\\')) {
      $class = substr($postfixNamespaces, 1);
    }
        // Useful for underscored 'namespaces': Foo_Shippo_Shipment
    if ($postfixFakeNamespaces = strrchr($class, 'Shippo_')) {
      $class = $postfixFakeNamespaces;
    }
    if (substr($class, 0, strlen('Shippo')) == 'Shippo') {
      $class = substr($class, strlen('Shippo'));
    }
    $class = str_replace('_', '', $class);
    $name = urlencode($class);
    $name = strtolower($name);
    return $name;
  }

    /**
     * @param string $class
     *
     * @returns string The endpoint URL for the given class.
     */
  public static function classUrl($class) {
    $base = self::scopedLsb($class, 'className', $class);
    return "/{$base}s";
  }

    /**
     * @returns string The full API URL for this API resource.
     */
  public function instanceUrl() {
    $id = $this['id'];
    $class = get_class($this);
    if ($id === null) {
      $message = "Could not determine which URL to request: " . "$class instance has invalid ID: $id";
      throw new InvalidRequestError($message, null);
    }
    $id = Util::utf8($id);
    $base = $this->lsb('classUrl', $class);
    $extn = urlencode($id);
    return "$base/$extn";
  }

  private static function validateCall($method, $params = null, $apiKey = null) {
    if ($params && !is_array($params)) {
      $message = "You must pass an array as the first argument to Shippo API ";
      throw new ShippoError($message);
    }

    if ($apiKey && !is_string($apiKey)) {
      $message = 'The second argument to Shippo API method calls is an ' . 'optional per-request apiKey (credentials base_64 encoded), which must be a string';
      throw new ShippoError($message);
    }
  }

  protected static function scopedAll($class, $params = null, $apiKey = null) {
    self::validateCall('all', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class);
    list($response, $apiKey) = $requestor->request('get', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

  protected static function scopedCreate($class, $params = null, $apiKey = null) {
    self::validateCall('create', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class);
        // Correction for malformed shippo URLs
    $url = $url . "/";
    list($response, $apiKey) = $requestor->request('post', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

  protected static function scopedUpdate($class, $id, $params = null, $apiKey = null) {
    self::validateCall('update', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class) . "/" . $id;
    list($response, $apiKey) = $requestor->request('put', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

    // Special Case for Rates which has parameters of the format: /url/parameter/url
  protected static function scopedGet($class, $id, $params = null, $apiKey = null) {
    self::validateCall('create', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = "/shipments/" . $id . "/rates/{$params['currency']}";
    list($response, $apiKey) = $requestor->request('get', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

    // Special case for Address Validation
  protected static function scopedValidate($class, $id, $params = null, $apiKey = null) {
    self::validateCall('create', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class) . "/" . $id . "/validate/";
    list($response, $apiKey) = $requestor->request('get', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

    //Special case for Tracking Status
  protected static function scopedGetStatus($class, $id, $params = null, $apiKey = null) {
    self::validateCall('create', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class) . "/{$params['carrier']}/" . $id;
    list($response, $apiKey) = $requestor->request('get', $url, array());
    return Util::convertToShippoObject($response, $apiKey);
  }

  protected static function scopedAddBatch($class, $id, $params = null, $apiKey = null) {
    self::validateCall('add', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class) . "/" . $id . "/" . 'add_shipments/';
    list($response, $apiKey) = $requestor->request('post', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

  protected static function scopedRemoveBatch($class, $id, $params = null, $apiKey = null) {
    self::validateCall('add', $params, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class) . "/" . $id . "/" .  'remove_shipments/';
    list($response, $apiKey) = $requestor->request('post', $url, $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

  protected static function scopedPurchaseBatch($class, $id, $apiKey = null) {
    self::validateCall('purchase', null, $apiKey);
    $requestor = new ApiRequestor($apiKey);
    $url = self::scopedLsb($class, 'classUrl', $class) . "/" . $id . "/" .  'purchase/';
    list($response, $apiKey) = $requestor->request('post', $url, null);
    return Util::convertToShippoObject($response, $apiKey);
  }
}
