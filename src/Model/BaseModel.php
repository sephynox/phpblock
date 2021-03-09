<?php

/**
 * Ethereum Network Client.
 *
 * @package PHPBlock
 * @category Model
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Model;

abstract class BaseModel
{
    public function __construct($data)
    {
        $m = $this->map();

        foreach ($data as $k => $v) {
            # Allow for model custom data mutations (arrays)
            $f = 'mutate' . ucfirst($k);

            if (method_exists($this, $f)) {
                $this->{$k} = $this->{$f}($v);
            } elseif (isset($m[$k])) {
                $this->{$k} = $m[$k]($v);
            } else {
                $this->{$k} = $v;
            }
        }
    }

    /**
     * Return the model data mutation map.
     *
     * @return array
     */
    abstract public function map(): array;
}
