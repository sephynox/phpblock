<?php

/**
 * Ethereum Network Client.
 *
 * @package PHPBlock
 * @category Client
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum;

use PHPBlock\JSONRPC\Factory;
use PHPBlock\JSONRPC\Request;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Promise\PromiseInterface;
use React\Promise\Deferred;
use React\Http\Server;
use React\Http\Message\Response;
use PHPBlock\Network\Base;

class Client extends Base
{
    public function __construct(string $uri)
    {
        parent::__construct(new Factory($uri));
    }

    /**
     * Call the eth_protocolVersion endpoint.
     * @see https://eth.wiki/json-rpc/API#eth_protocolversion
     *
     * @return PromiseInterface
     */
    public function protocolVersion() #: PromiseInterface
    {
        // $Request = $this->factory()->makeRequest();
        // return $this->browser->post();
    }
}
