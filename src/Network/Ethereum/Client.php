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
use PHPBlock\Network\Ethereum\Model\Gwei;
use PHPBlock\Network\Ethereum\Model\SyncStatus;
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\BlockIdentifier;
use PHPBlock\Network\Ethereum\Type\BlockNumber;
use PHPBlock\Network\Ethereum\Type\ChecksumAddress;
use PHPBlock\Network\Ethereum\Type\EthType;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

use function PHPBlock\Helper\hexToBigInt;
use function PHPBlock\Helper\hexToInt;
use function PHPBlock\Helper\intToHex;

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
                Gwei::class => fn ($v) => Client::gweiOrString($v),
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
     * Return a Gwei object or string.
     *
     * @param string $hex
     *
     * @return Gwei|string
     */
    public static function gweiOrString(string $hex, bool $wei = true)
    {
        if (function_exists('bcdiv')) {
            return new Gwei(hexToBigInt($hex), $wei);
        } else {
            return hexToBigInt(EthType::stripPrefix($hex));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function factory(): RPCFactoryInterface
    {
        return parent::factory();
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
     * Returns Keccak-256 (not the standardized SHA3-256) of the given data.
     * @see https://eth.wiki/json-rpc/API#web3_sha3
     *
     * @param HexString $data
     *
     * @return Promise<string> The SHA3 result of the given string.
     */
    public function web3SHA3(HexString $data): Promise
    {
        return $this->callEndpoint('web3_sha3', 64, Hash32::class, [(string) $data]);
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
     * Returns the current network id.
     * @see https://eth.wiki/json-rpc/API#net_version
     *
     * @return Promise<string> The current network id.
     */
    public function netVersion(): Promise
    {
        return $this->callEndpoint('net_version', 67, \string::class);
    }

    /**
     * Returns true if client is actively listening for network connections.
     * @see https://eth.wiki/json-rpc/API#net_listening
     *
     * @return Promise<bool> true when listening, otherwise false.
     */
    public function netListening(): Promise
    {
        return $this->callEndpoint('net_listening', 67, \bool::class);
    }

    /**
     * Returns true if client is actively listening for network connections.
     * @see https://eth.wiki/json-rpc/API#net_peerCount
     *
     * @return Promise<int> Returns true when listening, otherwise false.
     */
    public function netPeerCount(): Promise
    {
        return $this->callEndpoint('net_peerCount', 67, \int::class);
    }

    /**
     * Returns the current ethereum protocol version.
     * @see https://eth.wiki/json-rpc/API#eth_protocolversion
     *
     * @return Promise<string> The current ethereum protocol version.
     */
    public function ethProtocolVersion(): Promise
    {
        return $this->callEndpoint('net_version', 67, \string::class);
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
     * Returns true if client is actively mining new blocks.
     * @see https://eth.wiki/json-rpc/API#eth_mining
     *
     * @return Promise<bool> Returns true if client is mining, otherwise false.
     */
    public function ethMining(): Promise
    {
        return $this->callEndpoint('eth_mining', 71, \bool::class);
    }

    /**
     * Returns the number of hashes per second that the node is mining with.
     * @see https://eth.wiki/json-rpc/API#eth_hashrate
     *
     * @return Promise<int> Number of hashes per second.
     */
    public function ethHashrate(): Promise
    {
        return $this->callEndpoint('eth_hashrate', 71, \int::class);
    }

    /**
     * Returns the current price per gas.
     * @see https://eth.wiki/json-rpc/API#eth_gasPrice
     *
     * @return Promise<Gwei|string> Gwei of the gas price (string without bcmath).
     */
    public function ethGasPrice(): Promise
    {
        return $this->callEndpoint('eth_gasPrice', 73, Gwei::class);
    }

    /**
     * Returns a list of addresses owned by client.
     * @see https://eth.wiki/json-rpc/API#eth_accounts
     *
     * @return Promise<array[HexAddress]> Addresses owned by the client.
     */
    public function ethAccounts(): Promise
    {
        return $this->callEndpoint('eth_accounts', 1, HexAddress::class);
    }

    /**
     * Returns the number of most recent block.
     * @see https://eth.wiki/json-rpc/API#eth_blockNumber
     *
     * @return Promise<int> The current block number the client is on.
     */
    public function ethBlockNumber(): Promise
    {
        return $this->callEndpoint('eth_blockNumber', 83, \int::class);
    }

    /**
     * Returns the balance of the account of given address.
     * @see https://eth.wiki/json-rpc/API#eth_getBalance
     *
     * @param HexAddress $address Address to check for balance.
     * @param int|string $data Block number, or "latest", "earliest", "pending"
     *
     * @return Promise<Gwei|string> Gwei of the current balance (string without bcmath).
     */
    public function ethGetBalance(HexAddress $address, $data): Promise
    {
        if (is_int($data)) {
            $data = intToHex($data);
        } else {
            $data = trim($data);
        }

        $address = (string) $address;
        return $this->callEndpoint('eth_getBalance', 1, Gwei::class, [$address, $data]);
    }

    /**
     * Returns information about a block by hash.
     *
     * @param Hash32 $hash Hash of a block.
     * @param bool $Full If true it returns the full transaction objects.
     *
     * @return Promise<Block>
     */
    public function ethGetBlockByHash(Hash32 $hash, bool $Full = true): Promise
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
                        if (is_array($data)) {
                            return array_map(Client::$dataMap[$class], $data);
                        } else {
                            return Client::$dataMap[$class]($data);
                        }
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
