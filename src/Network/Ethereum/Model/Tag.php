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

use TypeError;

use function PHPBlock\Helper\intToHex;

class Tag extends EthModel
{
    public const LATEST = 'latest';
    public const PENDING = 'pending';
    public const EARLIEST = 'earliest';

    /**
     * @var int|string
     */
    private $value = null;

    /**
     * Tag or block number.
     *
     * @param int|string value
     */
    public function __construct($value)
    {
        if (is_int($value)) {
            $this->value = $value;
            return;
        } elseif (is_string($value)) {
            switch ($value) {
                case static::LATEST:
                case static::PENDING:
                case static::EARLIEST:
                    $this->value = $value;
                    return;
            }
        }

        throw new TypeError("Must be a valid tag or block number");
    }

    public function __toString()
    {
        if (is_int($this->value)) {
            return intToHex($this->value);
        } else {
            return $this->value;
        }
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
