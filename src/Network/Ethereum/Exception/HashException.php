<?php

/**
 * Ethereum Network Exception.
 *
 * @package PHPBlock
 * @category Ethereum
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum\Exception;

use Throwable;
use PHPBlock\Network\NetworkException;

class HashException extends NetworkException
{
    public function __construct(string $message, $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Invalid Hash: " . $message, $code, $previous);
    }
}
