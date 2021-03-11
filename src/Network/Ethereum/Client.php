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

use DateTime;
use Psr\Http\Message\ResponseInterface;
use React\Promise\Promise;
use PHPBlock\JSONRPC\RPCExceptionInterface;
use PHPBlock\JSONRPC\Factory;
use PHPBlock\JSONRPC\RequestInterface;
use PHPBlock\JSONRPC\RPCFactoryInterface;
use PHPBlock\Network\Base;
use PHPBlock\Network\Ethereum\Model\Block;
use PHPBlock\Network\Ethereum\Model\SyncStatus;
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\BlockIdentifier;
use PHPBlock\Network\Ethereum\Type\BlockNumber;
use PHPBlock\Network\Ethereum\Type\ChecksumAddress;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

use function PHPBlock\Helper\hexToInt;
use function PHPBlock\Helper\hexToStr;

class Client extends Base
{
    public const DEFAULT_ENDPOINT = 'http://127.0.0.1:8545';
    public static $dataMap;

    /**
     * A new Ethereum client instance.
     *
     * @param string $uri
     */
    public function __construct(string $uri = '')
    {
        if (!isset(static::$dataMap)) {
            static::$dataMap = [
                \int::class => fn ($v) => (int) $v,
                \bool::class => fn ($v) => (bool) $v,
                \string::class => fn ($v) => (string) $v,
                Address::class => fn ($v) => new Address($v),
                HexAddress::class => fn ($v) => new HexAddress($v),
                ChecksumAddress::class => fn ($v) => new ChecksumAddress($v),
                DateTime::class => fn ($v) => (new DateTime())->setTimestamp(hexToInt($v)),
                SyncStatus::class => fn ($v) => is_bool($v) ? (bool) $v : new SyncStatus($v),
                BlockIdentifier::class => fn ($v) => new BlockIdentifier($v),
                BlockNumber::class => fn ($v) => new BlockNumber($v),
                HexString::class => fn ($v) => new HexString($v),
                Hash32::class => fn ($v) => new Hash32($v),
                Block::class => fn ($v) => new Block($v)
            ];
        }

        parent::__construct(new Factory($uri ?: static::DEFAULT_ENDPOINT));
    }

    /**
     * {@inheritDoc}
     */
    public function factory(): RPCFactoryInterface
    {
        return parent::factory();
    }

    /**
     * Creates new message call transaction or a contract creation for
     * signed transactions.
     * @see https://eth.wiki/json-rpc/API#eth_sendRawTransaction
     *
     * @param string $data The signed transaction data.
     * @return Promise<Hash32> The transaction hash.
     */
    public function ethSendRawTransaction(string $data): Promise
    {
        return $this->callEndpoint(
            'eth_sendRawTransaction',
            1,
            Hash32::class,
            [$data]
        );
    }

    /**
     * Returns the current client version.
     * @see https://eth.wiki/json-rpc/API#web3_clientVersion
     *
     * @return Promise<string> The current client version.
     */
    public function web3ClientVersion(): Promise
    {
        return $this->callEndpoint('web3_clientVersion', 67, \string::class);
    }

    /**
     * Returns the current ethereum protocol version.
     * @see https://eth.wiki/json-rpc/API#eth_protocolversion
     *
     * @return Promise<string> The current ethereum protocol version.
     */
    public function ethProtocolVersion(): Promise
    {
        return $this->callEndpoint('eth_protocolVersion', 67, \string::class);
    }

    /**
     * Returns an object with data about the sync status or false.
     * @see https://eth.wiki/json-rpc/API#eth_syncing
     *
     * @return Promise<SyncStatus|false> Returns a SynStatus object or false.
     */
    public function ethSyncing(): Promise
    {
        return $this->callEndpoint('eth_syncing', 1, SyncStatus::class);
    }

    /**
     * Returns the client coinbase address.
     * @see https://eth.wiki/json-rpc/API#eth_coinbase
     *
     * @return Promise<HexAddress> The current coinbase address.
     */
    public function ethCoinbase(): Promise
    {
        return $this->callEndpoint('eth_coinbase', 64, HexAddress::class);
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
        return $this->send($request)
            ->then(function (ResponseInterface $resp) use ($class) {
                $resp = $this->factory()->makeFromResponse($resp);
                $error = $resp->getRPCError();
                $data = $resp->getRPCResult();

                if ($error === null) {
                    if (isset(Client::$dataMap[$class])) {
                        return Client::$dataMap[$class]($data);
                    }

                    return $data;
                } else {
                    throw $error;
                }
            }, function (\Exception $exception) {
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
