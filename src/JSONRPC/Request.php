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

use GuzzleHttp\Psr7\Request as Psr7Request;
use PHPBlock\JSONRPC\RequestInterface;

class Request extends Psr7Request implements RequestInterface
{
}
