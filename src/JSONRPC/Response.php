<?php

/**
 * JSON RPC Response.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

use RingCentral\Psr7\Response as HttpResponse;

class Response extends HttpResponse implements ResponseInterface
{
    private static array $parsedBodyTemplate = [
        "error" => null,
        "jsonrpc" => null,
        "id" => null,
        "result" => null
    ];

    private array $responseData = [];

    public function __construct(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        parent::__construct($status, $headers, $body, $version, $reason);
        $this->responseData = (array) json_decode($this->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    public static function getRPCResultTemplate(): array
    {
        return static::$parsedBodyTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function getRPCError(): ?RPCExceptionInterface
    {
        if (isset($this->responseData["error"])) {
            return new RPCException(
                $this->responseData["error"]["message"],
                $this->responseData["error"]["code"],
                $this->responseData["error"]["data"]
            );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getRPCVersion(): string
    {
        if (!isset($this->responseData["result"])) {
            throw new RPCException("Malformed response lacking version", -32700);
        }

        return $this->responseData["jsonrpc"];
    }

    /**
     * {@inheritdoc}
     */
    public function getRPCId(): int
    {
        if (!isset($this->responseData["result"])) {
            throw new RPCException("Malformed response lacking id", -32700);
        }

        return $this->responseData["id"];
    }

    /**
     * {@inheritdoc}
     */
    public function getRPCResult()
    {
        if (!isset($this->responseData["result"])) {
            throw new RPCException("Malformed response lacking result", -32700);
        }

        return $this->responseData["result"];
    }
}
