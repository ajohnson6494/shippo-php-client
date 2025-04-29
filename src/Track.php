<?php

namespace Shippo;

class Track extends ApiResource {
    /**
    * @param string $class Ignored.
    *
    * @return string The class URL for this resource. It needs to be special
    *    cased because it doesn't fit into the standard resource pattern.
    *    The standard resource pattern is name + s, e.g. parcel becomes parcels.
    */
  public static function classUrl($class) {
    return "/tracks";
  }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Tracking_Webhook Registers a webhook for the shipment
     */
  public static function create($params = null, $apiKey = null) {
    return self::scopedCreate(self::class, $params, $apiKey);
  }

    /**
     * @param array|null $params
     * @param string|null $apiKey
     *
     * @return Get_Status Get the tracking status of a Shipment.
     */
  public static function get_status($params = null, $apiKey = null) {
    $id = $params['id'];
    return self::scopedGetStatus(self::class, $id, $params, $apiKey);
  }
}
