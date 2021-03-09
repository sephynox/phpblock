<?php

/**
 * Network Base.
 *
 * @package PHPBlock
 * @category Network
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface FactoryInterface
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
}
