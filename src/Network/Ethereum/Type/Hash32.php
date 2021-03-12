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

use kornrunner\Keccak;
use PHPBlock\Network\Ethereum\Exception\HashException;

use function PHPBlock\Helper\byteToHex;
use function PHPBlock\Helper\hexToBytes;

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

        if (!ctype_xdigit($this->stripPrefix($value))) {
            $value = Keccak::hash($value, 256);
        }

        $strValue = strtolower($this->stripPrefix($value));

        if (64 !== strlen($strValue)) {
            throw new HashException($value);
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
