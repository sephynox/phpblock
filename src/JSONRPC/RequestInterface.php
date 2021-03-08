<?php

/**
 * JSON RPC Request Interface.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

use Psr\Http\Message\RequestInterface as HttpRequestInterface;

interface RequestInterface extends HttpRequestInterface
{
}
