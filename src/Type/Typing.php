<?php

/**
 * Immutable types.
 *
 * @package PHPBlock
 * @category Type
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @version $Revision: 0.1 $
 * @access public
 * @see https://github.com/sephynox/phpblock
 */

namespace PHPBlock\Type;

use JsonSerializable;

abstract class Typing implements JsonSerializable
{
    private $val;

    public function __construct($value, bool $raw = false)
    {
        if (!$raw) {
            $this->val = $this->unpack(trim($value));
        } else {
            $this->val = $value;
        }
    }

    public function __toString()
    {
        return (string) $this->pack($this->val);
    }

    #region JsonSerializable Members

    public function jsonSerialize()
    {
        return $this->__toString();
    }

    #endregion

    /**
     * Get the stored value.
     *
     * @return mixed
     */
    public function value()
    {
        return $this->val;
    }

    /**
     * Unpack the data.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function unpack($value);

    /**
     * Pack the data.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    abstract public function pack($value);
}
