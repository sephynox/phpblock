<?php

/**
 * Ethereum Network Client.
 *
 * @package PHPBlock
 * @category Ethereum
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum;

use React\Promise\Promise;
use PHPBlock\JSONRPC\RPCExceptionInterface;
use PHPBlock\JSONRPC\Factory;
use PHPBlock\JSONRPC\RequestInterface;
use PHPBlock\JSONRPC\ResponseInterface;
use PHPBlock\Network\Base;
use PHPBlock\Network\Ethereum\Model\Block;
use PHPBlock\Network\Ethereum\Model\EthModel;
use PHPBlock\Network\Ethereum\Type\Hash32;

class Client extends Base
{
    public function __construct(string $uri)
    {
        parent::__construct(new Factory($uri));
    }

    /**
     * Creates new message call transaction or a contract creation for
     * signed transactions.
     * @see https://eth.wiki/json-rpc/API#eth_sendRawTransaction
     *
     * @param string $data The signed transaction data.
     * @return Promise<Hash32> The transaction hash.
     */
    public function sendRawTransaction(string $data): Promise
    {
        return $this->callEndpoint(
            'eth_sendRawTransaction',
            1,
            Hash32::class,
            [$data]
        );
    }

    /**
     * Returns the current ethereum protocol version.
     * @see https://eth.wiki/json-rpc/API#eth_protocolversion
     *
     * @return Promise<string> The current ethereum protocol version.
     */
    public function protocolVersion(): Promise
    {
        var_dump("Run2");
        return $this->callEndpoint('eth_protocolVersion', 67, string::class);
    }


    /**
     * Returns information about a block by hash.
     *
     * @param Hash32 $hash Hash of a block.
     * @param bool $Full If true it returns the full transaction objects.
     *
     * @return Promise<Block>
     */
    public function getBlockByHash(Hash32 $hash, bool $Full = true): Promise
    {
        return $this->callEndpoint(
            'eth_getBlockByHash',
            1,
            Block::class,
            [$hash, $Full]
        );
    }

    /**
     * Call an Ethereum RPC endpoint.
     *
     * @param string $method The method name from the RPC specification.
     * @param integer $id The method id from the RPC specification.
     * @param string $class The response result type.
     * @param array $data The RPC response data.
     *
     * @return Promise<mixed>
     */
    public function callEndpoint(
        string $method,
        int $id,
        string $class,
        array $data = []
    ): Promise {
        return $this->getResult($this->getMessage($method, $id, $data), $class);
    }

    /**
     * Wrap the response promise to retrieve and pass the value.
     *
     * @param RequestInterface $response The request object to send.
     * @param string $class The response result type.
     *
     * @throws RPCExceptionInterface
     * @return Promise<mixed>
     */
    public function getResult(RequestInterface $request, string $class): Promise
    {
        var_dump("Run3");
        return $this->send($request)
            ->then(function (ResponseInterface $resp) use ($class) {
                var_dump("Run4");
                $error = $resp->getRPCError();
                $data = $resp->getRPCResult();

                if ($error !== null) {
                    var_dump("Run4.1");
                    if (isset(EthModel::$dataMap[$class])) {
                        return EthModel::$dataMap[$class]($data);
                    }

                    return $data;
                } else {
                    var_dump("Run4.2");
                    throw $error;
                }
            }, function (\Exception $exception) {
                var_dump("Run4.3");
                var_dump($exception->getMessage());
                throw $exception;
            });
    }

    /**
     * Build a RPC request object.
     *
     * @param string $method The method name from the RPC specification.
     * @param integer $id The method id from the RPC specification.
     * @param array $params
     *
     * @return RequestInterface
     */
    private function getMessage(
        string $method,
        int $id,
        array $params = []
    ): RequestInterface {
        return $this->factory()->makeRequest($method, $id, $params);
    }
}
