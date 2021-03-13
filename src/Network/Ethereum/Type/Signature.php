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

use function PHPBlock\Helper\byteToHex;
use function PHPBlock\Helper\hexToBytes;

class Signature extends EthType
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

        if (strlen($strValue) == 63) {
            # The ECDSA signature is sometimes 63...
            # TODO Figure out why
            $strValue .= '0';
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
