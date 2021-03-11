<?php

/**
 * Helper functions for hex related procedures.
 *
 * @package PHPBlock
 * @category Type
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Helper;

/**
 * Convert a byte array to a hex string.
 *
 * @param array $bytes
 *
 * @return string
 */
function byteToHex(array $bytes): string
{
    return bin2hex(join(array_map('chr', $bytes)));
}

/**
 * Convert a hex string to a string.
 *
 * @param string $hex
 *
 * @return string
 */
function hexToStr(string $hex): string
{
    return hex2bin($hex);
}

/**
 * Convert a hex string to a byte array.
 *
 * @param string $hex
 *
 * @return array
 */
function hexToByte(string $hex): array
{
    return unpack('C*', hex2bin($hex));
}

/**
 * Convert a hex string to an integer.
 *
 * @param string $hex
 *
 * @return integer
 */
function hexToInt(string $hex): int
{
    return (int) hexdec($hex);
}

/**
 * Convert a hex string to an integer.
 *
 * @param string $hex
 *
 * @return string
 */
function hexToBigInt(string $hex): string
{
    return number_format(hexdec($hex), 0, '.', '');
}

/**
 * Convert an integer into a hex string.
 *
 * @param integer $int
 *
 * @return string
 */
function intToHex(int $int): string
{
    return dechex($int);
}
