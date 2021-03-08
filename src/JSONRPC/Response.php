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

use React\Http\Message\Response as ReactResponse;

class Response extends ReactResponse implements ResponseInterface
{
    private array $parsedBody = [
        "error" => null,
        "jsonrpc" => null,
        "id" => null,
        "result" => null
    ];

    public function __construct(
        $status = 200,
        array $headers = [],
        $body = null,
        $version = '1.1',
        $reason = null
    ) {
        $jsonData = \json_decode($body == "" ? "{}" : $body);

        if (json_last_error() == JSON_ERROR_NONE) {
            $this->parsedBody = $this->parsedBody + $jsonData;
        } else {
            $this->parsedBody = $this->parsedBody + [$body];
        }

        parent::__construct($status, $headers, $body, $version, $reason);
    }

    /**
     * {@inheritdoc}
     */
    public function getRPCError(): ?RPCExceptionInterface
    {
        if (isset($this->parsedBody["error"])) {
            return new RPCException(
                $this->parsedBody["error"]["message"],
                $this->parsedBody["error"]["code"],
                $this->parsedBody["error"]["data"]
            );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getRPCVersion(): string
    {
        return $this->parsedBody["jsonrpc"];
    }

    /**
     * {@inheritdoc}
     */
    public function getRPCId(): int
    {
        return $this->parsedBody["id"];
    }

    /**
     * {@inheritdoc}
     */
    public function getRPCResult()
    {
        return $this->parsedBody["result"];
    }
}
