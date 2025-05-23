<?php

namespace Shippo;

class InvalidRequestError extends ShippoError {
  public $param;

  public function __construct($message, $param, $httpStatus = null, $httpBody = null, $jsonBody = null) {
    parent::__construct($message, $httpStatus, $httpBody, $jsonBody);
    $this->param = $param;
  }
}
