<?php

/**
 * JSON RPC Request.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

use React\Http\Message\ServerRequest;
use PHPBlock\JSONRPC\RequestInterface;

class Request extends ServerRequest implements RequestInterface
{
}
