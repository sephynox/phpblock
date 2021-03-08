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
use function PHPBlock\Helper\hexToByte;

class Hash32 extends EthType
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

        $strValue = strtolower($this->stripPrefix($value));

        if (64 !== strlen($strValue)) {
            throw new AddressException($value);
        }

        return hexToByte($strValue);
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
