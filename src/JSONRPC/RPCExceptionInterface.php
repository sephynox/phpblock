<?php

/**
 * JSON RPC Exception Interface.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

interface RPCExceptionInterface
{
    /**
     * Return the data for the error object.
     *
     * @return array
     */
    public function getData(): array;

    /**
     * Get the JSON RPC Code message.
     *
     * @param integer $Code
     *
     * @return string
     */
    public function getMessageForCode(int $Code): string;

    /**
     * Get the JSON RPC Code meaning.
     *
     * @param integer $Code
     *
     * @return string
     */
    public function getMeaningForCode(int $Code): string;
}
