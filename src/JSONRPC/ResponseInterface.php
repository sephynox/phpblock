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
     * @return RPCExceptionInterface|null
     */
    public function getRPCError(): ?RPCExceptionInterface;

    /**
     * @return int
     */
    public function getRPCId(): int;

    /**
     * @return string
     */
    public function getRPCVersion(): string;

    /**
     * @return mixed
     */
    public function getRPCResult();
}
