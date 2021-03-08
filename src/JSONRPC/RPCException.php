<?php

/**
 * JSON RPC Exception.
 *
 * @package PHPBlock
 * @category JSONRPC
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\JSONRPC;

use Exception;

class RPCException extends Exception implements RPCExceptionInterface
{
    /**
     * @var array
     */
    private array $codes = [
        0 => ["Unknown" => "Unknown code provided."],
        -32700 => ['Parse error' => 'Invalid JSON was received by the server.
                    An error occurred on the server while parsing the JSON text.'],
        -32600 => ['Invalid Request' => 'The JSON sent is not a valid Request object.'],
        -32601 => ['Method not found' => 'The method does not exist / is not available.'],
        -32602 => ['Invalid params' => 'Invalid method parameter(s).'],
        -32603 => ['Internal error' => 'Internal JSON-RPC error.'],
        -32000 => ['Server error' => 'Reserved for implementation-defined server-errors.'],
        -32099 => ['Server error' => 'Reserved for implementation-defined server-errors.'],
    ];

    /**
     * @var array
     */
    private array $data = [];

    public function __construct($message, $code, $data = [])
    {
        $this->data = $data;
        parent::__construct($message, $code);
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageForCode(int $Code): string
    {
        $strMessage = $this->getMessage();

        if (!$strMessage) {
            if ($Code < -32000 && $Code > -32100) {
                $strMessage = key($this->codes[-32000]);
            } elseif (isset($this->codes[$Code])) {
                $strMessage = key($this->codes[$Code]);
            } else {
                $strMessage = key($this->codes[0]);
            }
        }

        return $strMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function getMeaningForCode(int $Code): string
    {
        if ($Code < -32000 && $Code > -32100) {
            $strMessage = current($this->codes[-32000]);
        } elseif (isset($this->codes[$Code])) {
            $strMessage = current($this->codes[$Code]);
        } else {
            $strMessage = current($this->codes[0]);
        }

        return $strMessage;
    }
}
