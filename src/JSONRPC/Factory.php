<?php

/**
 * JSON RPC Message Factory.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network;

use PHPBlock\JSONRPC\Request;
use PHPBlock\JSONRPC\Response;
use PHPBlock\JSONRPC\RequestInterface;
use PHPBlock\JSONRPC\ResponseInterface;

class Factory
{
    public const METHOD = 'POST';
    public const KEY_VERSION = 'jsonrpc';
    public const KEY_METHOD = 'method';
    public const KEY_RESULT = 'result';
    public const KEY_ID = 'id';
    public const KEY_PARAMS = 'params';
    public const KEY_ERROR = 'error';

    private string $uri;
    private string $version = '2.0';

    public function __construct(string $uri, string $version = '2.0')
    {
        $this->uri = $uri;
        $this->version = $version;
    }

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
    ): RequestInterface {
        $strBody = $this->makeJson($this->makePayload($body, $method, $id));
        return new Request(static::METHOD, $this->uri, $headers, $strBody);
    }

    /**
     * Create a JSON RPC response object.
     *
     * @param integer $status
     * @param array $body
     * @param array $headers
     *
     * @return ResponseInterface
     */
    public function makeResponse(
        int $status,
        array $body = [],
        array $headers = []
    ): ResponseInterface {
        $arrBody = [
            static::KEY_VERSION => $this->version,
            static::KEY_RESULT => null,
            static::KEY_ERROR => null,
            static::KEY_ID => null,
        ];

        $strBody = $this->makeJson(array_merge($arrBody, $body));
        return new Response($status, $headers, $body);
    }

    /**
     * Create RPC Json Body.
     *
     * @param array $Body
     *
     * @return array
     */
    private function makePayload(array $params, string $method, int $id): array
    {
        $arrBody = [
            static::KEY_VERSION => $this->version,
            static::KEY_METHOD => $method,
            static::KEY_ID => $id,
            static::KEY_PARAMS => $params,
        ];

        return $arrBody;
    }

    /**
     * Proxy json_encode.
     *
     * @param array $data
     *
     * @return string
     */
    private function makeJson(array $data): string
    {
        return json_encode($data);
    }
}
