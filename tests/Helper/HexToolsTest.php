<?php

/**
 * Type Test.
 *
 * @package PHPBlock
 * @category Test
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

declare(strict_types=1);

namespace PHPBlock\Helper;

use PHPUnit\Framework\TestCase;

class HexToolsTest extends TestCase
{
    /**
     * Test converting a byte array to a hex string.
     */
    public function testByteToHex(): void
    {
        $arrBytes = [72, 101, 108, 108, 111, 32, 87, 111, 114, 108, 100, 33];
        $test = byteToHex($arrBytes);

        $this->assertTrue(ctype_xdigit($test));
        $this->assertEquals('48656c6c6f20576f726c6421', $test);
    }

    /**
     * Test converting a hex string to a string.
     */
    public function testHexToStr(): void
    {
        $strHex = '48656c6c6f20576f726c6421';
        $test = hexToStr($strHex);

        $this->assertEquals('Hello World!', $test);
    }

    /**
     * Test converting a hex string to a byte array.
     */
    public function testHexToBytes(): void
    {
        $strHex = '48656c6c6f20576f726c6421';
        $arrBytes = [72, 101, 108, 108, 111, 32, 87, 111, 114, 108, 100, 33];
        $test = hexToBytes($strHex);

        $this->assertIsArray($test);
        $this->assertEquals($arrBytes, array_values($test));
    }

    /**
     * Test converting a hex string to an integer.
     */
    public function testHexToInt(): void
    {
        $strHex = '75bcd15';
        $test = hexToInt($strHex);

        $this->assertEquals(123456789, $test);
    }

    /**
     * Test converting a hex string to an integer.
     */
    public function testHexToBigInt(): void
    {
        $strHex = '002386f26fc10000';
        $test = hexToBigInt($strHex);

        $this->assertIsString($test);
        $this->assertEquals('10000000000000000', $test);
    }

    /**
     * Test converting an integer into a hex string.
     */
    public function testIntToHex(): void
    {
        $int = 123456789;
        $test = intToHex($int);

        $this->assertIsString($test);
        $this->assertTrue(ctype_xdigit($test));
        $this->assertEquals('75bcd15', $test);
    }
}
