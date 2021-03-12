<?php

/**
 * Ethereum Network Model.
 *
 * @package PHPBlock
 * @category Ethereum
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum\Model;

class Gwei extends EthModel
{
    private static string $ether = '0.000000001';
    private static string $wei = '1000000000';
    private string $value;

    /**
     * Gwei gas price. Provide a value in Gwei.
     *
     * @param string $value
     */
    public function __construct(string $value, bool $wei = false)
    {
        if ($wei) {
            $this->value = static::weiToGwei($value);
        } else {
            $this->value = $value;
        }
    }

    public function __toString()
    {
        return $this->value;
    }

    /**
     * Convert ether to gwei.
     *
     * @param string $value
     *
     * @return string
     */
    public static function ethToGwei(string $value): string
    {
        return bcdiv($value, static::$ether);
    }

    /**
     * Convert gwei to ether.
     *
     * @param string $value
     *
     * @return string
     */
    public static function gweiToEth(string $value): string
    {
        return rtrim(rtrim(bcmul($value, static::$ether, 18), '0'), '.');
    }

    /**
     * Convert wei to gwei.
     *
     * @param string $value
     *
     * @return string
     */
    public static function weiToGwei(string $value): string
    {
        return bcdiv($value, static::$wei);
    }

    /**
     * Return the value in Gwei.
     *
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Convert gwei to wei.
     *
     * @return string
     */
    public function toWei(): string
    {
        return bcmul($this->value, static::$wei);
    }

    /**
     * Return the ether value of Gwei.
     *
     * @return string
     */
    public function toEth(): string
    {
        return static::gweiToEth($this->value);
    }

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        return [];
    }

    #endregion
}
