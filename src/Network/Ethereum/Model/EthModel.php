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

use JsonSerializable;
use PHPBlock\Model\BaseModel;

abstract class EthModel extends BaseModel implements JsonSerializable
{
    public function __toString()
    {
        return json_encode($this->jsonSerialize());
    }

    #region JsonSerializable Members

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }

    #endregion
}
