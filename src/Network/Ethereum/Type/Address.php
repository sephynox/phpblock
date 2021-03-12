<?php

/**
 * Ethereum Type.
 *
 * @package PHPBlock
 * @category Type
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum\Type;

use PHPBlock\Network\Ethereum\Exception\AddressException;

use function PHPBlock\Helper\byteToHex;
use function PHPBlock\Helper\hexToBytes;

class Address extends EthType
{
    /**
     * Checks if the given string is an address.
     * @see https://github.com/ethereum/go-ethereum
     *
     * @param string $address
     * @return bool
     */
    public static function isAddress(string $address): bool
    {
        if (preg_match('/^(0x)?[0-9a-f]{40}$/i', $address) === false) {
            return false;
        } elseif (
            preg_match('/^(0x)?[0-9a-f]{40}$/', $address) ||
            preg_match('/^(0x)?[0-9A-F]{40}$/', $address) === true
        ) {
            return true;
        } else {
            return static::isChecksumAddress($address);
        }
    }

    /**
     * Checks if the given string is a checksum address.
     * @see https://github.com/ethereum/go-ethereum
     *
     * @param string $address
     * @return bool
     */
    public static function isChecksumAddress(string $address)
    {
        return static::checksumEncode($address) === $address;
    }

    #region Typing Members

    /**
     * {@inheritdoc}
     */
    public function unpack($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $strValue = strtolower($this->stripPrefix($value));

        if (!static::isAddress($value)) {
            throw new AddressException($value);
        }

        return hexToBytes($strValue);
    }

    /**
     * {@inheritdoc}
     */
    public function pack($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        return $this->appendPrefix(byteToHex($value));
    }

    #endregion
}
