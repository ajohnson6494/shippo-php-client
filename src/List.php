<?php

namespace Shippo;

class List extends Object {
  public function all($params = null) {
    $requestor = new ApiRequestor($this->apiKey);
    list($response, $apiKey) = $requestor->request('get', $this['url'], $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

  public function create($params = null) {
    $requestor = new ApiRequestor($this->apiKey);
    list($response, $apiKey) = $requestor->request('post', $this['url'], $params);
    return Util::convertToShippoObject($response, $apiKey);
  }

  public function retrieve($id, $params = null) {
    $requestor = new ApiRequestor($this->apiKey);
    $base = $this['url'];
    $id = Util::utf8($id);
    $extn = urlencode($id);
    list($response, $apiKey) = $requestor->request('get', "$base/$extn", $params);
    return Util::convertToShippoObject($response, $apiKey);
  }
}
