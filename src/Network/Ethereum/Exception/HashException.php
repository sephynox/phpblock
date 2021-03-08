<?php

/**
 * JSON RPC Exception.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @version $Revision: 0.1 $
 * @access public
 * @see https://github.com/sephynox/phpblock
 */

namespace PHPBlock\Network\Ethereum\Exception;

use Exception;
use Throwable;

class HashException extends Exception
{
    public function __construct(string $message, $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Invalid Hash: " . $message, $code, $previous);
    }
}
