<?php

namespace Shippo;

use ArrayAccess;
use Shippo\Util\Set;
use InvalidArgumentException;

class ShippoObject implements ArrayAccess {
    /**
     * @var Set Attributes that should not be sent to the API because
     *    they're not updatable (e.g. API key, ID).
     */
  public static $permanentAttributes;

    /**
     * @var Set Attributes that are nested but still updatable from
     *    the parent class's URL (e.g. metadata).
     */
  public static $nestedUpdatableAttributes;

  public static function init() {
    self::$permanentAttributes = new Set(array(
          'apiKey',
          'id'
      ));
    self::$nestedUpdatableAttributes = new Set(array(
          'metadata'
      ));
  }

  protected $apiKey;

  protected array $values = [];

  protected $unsavedValues;

  protected $transientValues;

  protected $retrieveOptions;

  public function __construct($id = null, $apiKey = null) {
    $this->apiKey = $apiKey;
    $this->values = array();
    $this->unsavedValues = new Set();
    $this->transientValues = new Set();

    $this->retrieveOptions = array();
    if (is_array($id)) {
      foreach ($id as $key => $value) {
        if ($key != 'id') {
          $this->retrieveOptions[$key] = $value;
        }
      }
      $id = $id['id'];
    }

    if ($id !== null) {
      $this->id = $id;
    }

    $this->init();
  }

    // Standard accessor magic methods
  public function __set($k, $v) {
    if ($v === "") {
      throw new InvalidArgumentException('You cannot set \'' . $k . '\'to an empty string. ' . 'We interpret empty strings as NULL in requests. ' . 'You may set obj->' . $k . ' = NULL to delete the property');
    }

    if (is_array(self::$nestedUpdatableAttributes) && self::$nestedUpdatableAttributes->includes($k) && isset($this->$k) && is_array($v)) {
      $this->$k->replaceWith($v);
    } else {
      // TODO: may want to clear from $transientValues (Won't be user-visible).
      $this->values[$k] = $v;
    }
    if (is_array(self::$permanentAttributes) && !self::$permanentAttributes->includes($k)) {
      $this->unsavedValues->add($k);
    }
  }

  public function __isset($k) {
    return isset($this->values[$k]);
  }

  public function __unset($k) {
    unset($this->values[$k]);
    $this->transientValues->add($k);
    $this->unsavedValues->discard($k);
  }

  public function __get($k) {
    if (is_array($this->values) && array_key_exists($k, $this->values)) {
      return $this->values[$k];
    } elseif ($this->transientValues->includes($k)) {
      $class = get_class($this);
      $attrs = join(', ', array_keys($this->values));
      $message = "Shippo Notice: Undefined property of $class instance: $k. " . "HINT: The $k attribute was set in the past, however. " . "It was then wiped when refreshing the object " . "with the result returned by Shippo's API, " . "probably as a result of a save(). The attributes currently " . "available on this object are: $attrs";
      error_log($message);
      return null;
    } else {
      $class = get_class($this);
      error_log("Shippo Notice: Undefined property of $class instance: $k");
      return null;
    }
  }

    // ArrayAccess methods
  public function offsetSet($k, $v): void {
    $this->$k = $v;
  }

  public function offsetExists($k): bool {
    return is_array($this->values) && array_key_exists($k, $this->values);
  }

  public function offsetUnset($k): void {
    unset($this->$k);
  }

  public function offsetGet($k): mixed {
    return is_array($this->values) && array_key_exists($k, $this->values) ? $this->values[$k] : null;
  }

  public function keys() {
    return array_keys($this->values);
  }

    /**
     * This unfortunately needs to be public to be used in Util.php
     *
     * @param string $class
     * @param array $values
     * @param string|null $apiKey
     *
     * @return ShippoObject The object constructed from the given values.
     */
  public static function scopedConstructFrom($class, $values, $apiKey = null) {
    $obj = new $class(isset($values['id']) ? $values['id'] : null, $apiKey);
    $obj->refreshFrom($values, $apiKey);
    return $obj;
  }

    /**
     * @param array $values
     * @param string|null $apiKey
     *
     * @return ShippoObject The object of the same class as $this constructed
     *    from the given values.
     */
  public static function constructFrom($values, $apiKey = null) {
    return self::scopedConstructFrom(__CLASS__, $values, $apiKey);
  }

    /**
     * Refreshes this object using the provided values.
     *
     * @param array $values
     * @param string $apiKey
     * @param boolean $partial Defaults to false.
     */
  public function refreshFrom($values, $apiKey, $partial = false) {
    $this->apiKey = $apiKey;

        // Wipe old state before setting new.  This is useful for e.g. updating a
        // customer, where there is no persistent card parameter.  Mark those values
        // which don't persist as transient
    if ($partial) {
      $removed = new Set();
    } else {
      $removed = array_diff(array_keys($this->values), array_keys($values));
    }

    foreach ($removed as $k) {
      if (self::$permanentAttributes->includes($k)) {
        continue;
      }
      unset($this->$k);
    }

    foreach ($values as $k => $v) {
      if (self::$permanentAttributes->includes($k) && isset($this[$k])) {
        continue;
      }

      if (self::$nestedUpdatableAttributes->includes($k) && is_array($v)) {
        $this->values[$k] = ShippoObject::scopedConstructFrom('AttachedObject', $v, $apiKey);
      } else {
        $this->values[$k] = Util::convertToShippoObject($v, $apiKey);
      }

      $this->transientValues->discard($k);
      $this->unsavedValues->discard($k);
    }
  }

    /**
     * @return array A recursive mapping of attributes to values for this object,
     *    including the proper value for deleted attributes.
     */
  public function serializeParameters() {
    $params = array();
    if ($this->unsavedValues) {
      foreach ($this->unsavedValues->toArray() as $k) {
        $v = $this->$k;
        if ($v === null) {
          $v = '';
        }
        $params[$k] = $v;
      }
    }

        // Get nested updates.
    foreach (self::$nestedUpdatableAttributes->toArray() as $property) {
      if (isset($this->$property) && $this->$property instanceof ShippoObject) {
        $params[$property] = $this->$property->serializeParameters();
      }
    }
    return $params;
  }

    // Pretend to have late static bindings, even in PHP 5.2
  protected function lsb($method) {
    $class = get_class($this);
    $args = array_slice(func_get_args(), 1);
    return call_user_func_array(array(
          $class,
          $method
      ), $args);
  }

  protected static function scopedLsb($class, $method) {
    $args = array_slice(func_get_args(), 2);
    return call_user_func_array(array(
          $class,
          $method
      ), $args);
  }

  public function __toJSON() {
    if (defined('JSON_PRETTY_PRINT')) {
      return json_encode($this->__toArray(true), JSON_PRETTY_PRINT);
    } else {
      return json_encode($this->__toArray(true));
    }
  }

  public function __toString() {
    return $this->__toJSON();
  }

  public function __toArray($recursive = false) {
    if ($recursive) {
      return Util::convertShippoObjectToArray($this->values);
    } else {
      return $this->values;
    }
  }
}
