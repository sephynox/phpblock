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

class HexString extends EthType
{
    #region Typing Members

    /**
     * {@inheritdoc}
     */
    public function unpack($value)
    {
        return hex2bin($this->stripPrefix($value));
    }

    /**
     * {@inheritdoc}
     */
    public function pack($value)
    {
        return $this->appendPrefix(bin2hex($value));
    }

    #endregion
}
