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

use Exception;
use kornrunner\Keccak;
use PHPBlock\Type\Typing;
use PHPBlock\Network\Ethereum\Model\Gwei;

use function PHPBlock\Helper\hexToBigInt;

abstract class EthType extends Typing
{
    public const HEX_PREFIX = '0x';
    public const HEX_INTS = '0123456789';
    public const HEX_CHARS = 'abcdef';

    /**
     * Remove the "0x" from the start of the hex string.
     *
     * @param string $input
     *
     * @return string
     */
    public static function stripPrefix(string $input): string
    {
        if (substr($input, 0, strlen(static::HEX_PREFIX)) == static::HEX_PREFIX) {
            $input = substr($input, strlen(static::HEX_PREFIX));
        }

        return $input;
    }

    /**
     * Append the "0x" to the start of the hex string.
     *
     * @param string $input
     *
     * @return string
     */
    public static function appendPrefix(string $input): string
    {
        if (substr($input, 0, strlen(static::HEX_PREFIX)) == static::HEX_PREFIX) {
            return $input;
        }

        return static::HEX_PREFIX . $input;
    }

    /**
     * Encode hex checksums according to EIP-55.
     * @see https://eips.ethereum.org/EIPS/eip-55
     *
     * @param string $value
     * @param integer $len
     *
     * @return string
     */
    public static function checksumEncode(string $value, int $len = 256): string
    {
        $strReturn = '';
        $strHash = static::stripPrefix(strtolower($value));
        $strHashed = Keccak::hash($strHash, $len);
        $arrHash = str_split($strHash);

        foreach ($arrHash as $i => $v) {
            if (false !== strpos(EthType::HEX_INTS, $v)) {
                $strReturn .= $v;
            } elseif (false !== stripos(EthType::HEX_CHARS, $v)) {
                $n = hexdec($strHashed[$i]);

                if ($n > 7) {
                    $strReturn .= strtoupper($v);
                } else {
                    $strReturn .= $v;
                }
            } else {
                throw new Exception("Unknown hex character: " . $v);
            }
        }

        return static::appendPrefix($strReturn);
    }

    /**
     * Provide an eth protocol type.
     */
    public function toEth()
    {
        return $this->pack($this->value());
    }

    /**
     * Return a Gwei object or string.
     *
     * @param string $hex
     *
     * @return Gwei|string
     */
    public static function gweiOrString(string $hex, bool $wei = true)
    {
        if (function_exists('bcdiv')) {
            return new Gwei(hexToBigInt($hex), $wei);
        } else {
            return hexToBigInt(EthType::stripPrefix($hex));
        }
    }
}
