<?php

/**
 * Helper functions for promise related procedures.
 *
 * @package PHPBlock
 * @category Helper
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Helper;

use React\Promise\Promise;

/**
 * Pass additional returns for a promise. Useful for chaining.
 *
 * @param Promise $promise
 * @param mixed ...$args
 *
 * @return string
 */
function pass(Promise $promise, ...$args): Promise
{
    return $promise->then(function () use ($args) {
        return [...func_get_args(), ...$args];
    });
}
