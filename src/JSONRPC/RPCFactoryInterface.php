<?php

/**
 * JSON RPC Request Interface.
 *
 * @package PHPBlock
 * @category Network
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;
use PHPBlock\Network\FactoryInterface;
use PHPBlock\JSONRPC\RequestInterface;
use PHPBlock\JSONRPC\ResponseInterface;

interface RPCFactoryInterface extends FactoryInterface
{
    /**
     * Create a JSON RPC Request object.
     *
     * @param string $method
     * @param integer $id
     * @param array $body
     * @param array $headers
     *
     * @return Request
     */
    public function makeRequest(
        string $method,
        int $id,
        array $body = [],
        array $headers = []
    ): RequestInterface;

    /**
     * Create a JSON RPC response object.
     *
     * @param integer $status
     * @param string $body
     * @param array $headers
     *
     * @return ResponseInterface
     */
    public function makeResponse(
        int $status,
        string $body = '',
        array $headers = []
    ): ResponseInterface;

    /**
     * Create a JSON RPC Response from a PSR-7 Response.
     *
     * @param HttpResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function makeFromResponse(
        HttpResponseInterface $response
    ): ResponseInterface;
}
