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

use PHPBlock\JSONRPC\RequestInterface;
use RingCentral\Psr7\Request as HttpRequest;

class Request extends HttpRequest implements RequestInterface
{
}
