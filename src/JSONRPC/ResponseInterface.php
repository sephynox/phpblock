<?php

/**
 * JSON RPC Response Interface.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

use Psr\Http\Message\ResponseInterface as HttpResponseInterface;

interface ResponseInterface extends HttpResponseInterface
{
    /**
     * Provide the RPC response template.
     *
     * @return array
     */
    public static function getRPCResultTemplate(): array;

    /**
     * Get the RPC response error if one exists.
     *
     * @return RPCExceptionInterface|null
     */
    public function getRPCError(): ?RPCExceptionInterface;

    /**
     * Get the RPC response method id.
     *
     * @return int
     */
    public function getRPCId(): int;

    /**
     * Get the response RPC version.
     *
     * @return string
     */
    public function getRPCVersion(): string;

    /**
     * Get the RPC result value.
     *
     * @return mixed
     */
    public function getRPCResult();
}
