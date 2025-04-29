<?php

namespace Tests;

use Shippo\Address;
use Shippo\ApiRequestor;
use PHPUnit\Framework\Attributes\DataProvider;

class ApiRequestorTest extends TestCase {
  public function setUp(): void {
    parent::setUp();
  }

  public function testEncodeObjects() {
    $apiRequestor = new ApiRequestor();

    $v = ['customer' => new Address('abcd')];
    $enc = $apiRequestor->encodeObjects($v);
    $this->assertEquals($enc, ['customer' => 'abcd']);

    // Preserves UTF-8
    $v = ['customer' => "☃"];
    $enc = $apiRequestor->encodeObjects($v);
    $this->assertEquals($enc, $v);

    // Encodes latin-1 -> UTF-8
    $v = ['customer' => "\xe9"];
    $enc = $apiRequestor->encodeObjects($v);
    $this->assertEquals($enc, ['customer' => "\xc3\xa9"]);
  }

  /**
   * @dataProvider provider
   */
  public function testGetAuthorizationType(): void {
    $testTokens = [
      [
          'Bearer',
          'oauth.612BUDkTaTuJP3ll5-VkebURXUIJ5Zefxwda1tpd.U_akmGaXVQl80CWPXSbueSG7NX7sNe_HvLJLN1d1pn0='
      ],
      [
          'Bearer',
          'oauth.foo'
      ],
      [
          'ShippoToken',
          'dW5pdHRlc3Q6dW5pdHRlc3Q='
      ],
      [
          'ShippoToken',
          'askdljfgaklsdfjalskdfjalksjd'
      ],
      [
          'ShippoToken',
          'askdljfgaklsdfjalskdfjalksjd.oauth'
      ],
    ];

    foreach ($testTokens as $testToken) {
      $apiToken = $testToken[1];
      $expectedAuthorizationType = $testToken[0];

      $apiRequestor = new ApiRequestor($apiToken);
      $headers = $apiRequestor->getRequestHeaders();
      $authorizationHeader = current(array_filter($headers, function ($header) {
          return strpos($header, 'Authorization:') === 0;
      }));

      $this->assertEquals(strpos($authorizationHeader, 'Authorization: ' . $expectedAuthorizationType), 0);
    }
  }
}
