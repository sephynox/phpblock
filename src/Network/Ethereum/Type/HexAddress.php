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

class HexAddress extends Address
{
    #region Typing Members

    /**
     * {@inheritdoc}
     */
    public function unpack($value)
    {
        if (is_array($value)) {
            return $value;
        }

        $strValue = strtolower($value);

        if (!static::isAddress($value)) {
            throw new AddressException($value);
        }

        return strtolower($strValue);
    }

    /**
     * {@inheritdoc}
     */
    public function pack($value)
    {
        return $value;
    }

    #endregion
}
