<?php

namespace Tests;

use Shippo\Util;

class UtilTest extends TestCase {
  public function testIsList() {
    $list = array(
          5,
          'nstaoush',
          array()
      );
    $this->assertTrue(Util::isList($list));

    $notlist = array(
          5,
          'nstaoush',
          array(),
          'bar' => 'baz'
      );
    $this->assertFalse(Util::isList($notlist));
  }

  public function testThatPHPHasValueSemanticsForArrays() {
    $original = array(
          'php-arrays' => 'value-semantics'
      );
    $derived = $original;
    $derived['php-arrays'] = 'reference-semantics';

    $this->assertEquals('value-semantics', $original['php-arrays']);
  }

  public function testUtf8() {
        // UTF-8 string
    $x = "\xc3\xa9";
    $this->assertEquals(Util::utf8($x), $x);

        // Latin-1 string
    $x = "\xe9";
    $this->assertEquals(Util::utf8($x), "\xc3\xa9");

        // Not a string
    $x = true;
    $this->assertEquals(Util::utf8($x), $x);
  }
}
