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
use PHPBlock\Network\Ethereum\Model\Tag;
use PHPBlock\Network\Ethereum\Model\Transaction;
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\BlockIdentifier;
use PHPBlock\Network\Ethereum\Type\BlockNumber;
use PHPBlock\Network\Ethereum\Type\ChecksumAddress;
use PHPBlock\Network\Ethereum\Type\EthType;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

use function PHPBlock\Helper\hexToInt;
use function PHPBlock\Helper\intToHex;

final class Client extends Base
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
                \bool::class => fn ($v) => (bool) $v,
                \string::class => fn ($v) => (string) $v,
                Address::class => fn ($v) => new Address($v),
                HexAddress::class => fn ($v) => new HexAddress($v),
                Gwei::class => fn ($v) => EthType::gweiOrString($v),
                Transaction::class => fn ($v) => new Transaction($v),
                \int::class => fn ($v) => hexToInt(EthType::stripPrefix($v)),
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

    #region Web3 Calls

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

    #endregion

    #region Net Calls

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

    #endregion

    #region Eth Calls

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
        return $this->callEndpoint('eth_accounts', 1, HexAddress::class, [], true);
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
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<Gwei|string> Gwei of the current balance (string without bcmath).
     */
    public function ethGetBalance(HexAddress $address, Tag $tag): Promise
    {
        $data = [(string) $address, (string) $tag];
        return $this->callEndpoint('eth_getBalance', 1, Gwei::class, $data);
    }

    /**
     * Returns the value from a storage position at a given address.
     * @see https://eth.wiki/json-rpc/API#eth_getStorageAt
     *
     * @param HexAddress $address Address of the storage.
     * @param int $position Position in the storage.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<string> The value at this storage position.
     */
    public function ethGetStorageAt(HexAddress $address, int $position, Tag $tag): Promise
    {
        $data = [(string) $address, EthType::appendPrefix(intToHex($position)), (string) $tag];
        return $this->callEndpoint('eth_getBalance', 1, \string::class, $data);
    }

    /**
     * Returns the number of transactions sent from an address.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionCount
     *
     * @param HexAddress $address Address.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<int> Number of transactions sent from this address.
     */
    public function ethGetTransactionCount(HexAddress $address, Tag $tag): Promise
    {
        $data = [(string) $address, (string) $tag];
        return $this->callEndpoint('eth_getTransactionCount', 1, \int::class, $data);
    }

    /**
     * Returns the number of transactions sent from an address.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionCount
     *
     * @param Hash32 $hash Hash of a block.
     *
     * @return Promise<int> Number of transactions in this block.
     */
    public function ethGetBlockTransactionCountByHash(Hash32 $hash): Promise
    {
        $data = [(string) $hash];
        return $this->callEndpoint('eth_getBlockTransactionCountByHash', 1, \int::class, $data);
    }

    /**
     * Executes a new message call immediately without creating a transaction
     * on the block chain.
     * @see https://eth.wiki/json-rpc/API#eth_sendTransaction
     *
     * @param Transaction $transaction The transaction object.
     *
     * @return Promise<Hash32> The transaction hash, or zero hash if not yet available.
     */
    public function ethSendTransaction(Transaction $transaction): Promise
    {
        $data = [$transaction];
        return $this->callEndpoint('eth_sendTransaction', 1, Hash32::class, $data);
    }

    /**
     * Executes a new message call immediately without creating a transaction
     * on the block chain.
     * @see https://eth.wiki/json-rpc/API#eth_sendRawTransaction
     *
     * @param string $data The signed transaction data.
     *
     * @return Promise<Hash32> The transaction hash, or zero hash if not yet available.
     */
    public function ethSendRawTransaction(string $data): Promise
    {
        return $this->callEndpoint('eth_sendRawTransaction', 1, Hash32::class, [$data]);
    }

    /**
     * Executes a new message call immediately without creating a transaction
     * on the block chain.
     * @see https://eth.wiki/json-rpc/API#eth_call
     *
     * @param Transaction $transaction The transaction call object.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<string> The return value of executed contract.
     */
    public function ethCall(Transaction $transaction, Tag $tag): Promise
    {
        $data = [$transaction, (string) $tag];
        return $this->callEndpoint('eth_call', 1, \string::class, $data);
    }

    /**
     * Generates and returns an estimate of how much gas is necessary to allow
     * the transaction to complete. The transaction will not be added to the
     * blockchain. Note that the estimate may be significantly more than the
     * amount of gas actually used by the transaction, for a variety of reasons
     * including EVM mechanics and node performance.
     * @see https://eth.wiki/json-rpc/API#eth_estimateGas
     *
     * @param Transaction $transaction The transaction call object.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<Gwei|string> Gwei of the gas estimate (string without bcmath).
     */
    public function ethEstimateGas(Transaction $transaction, Tag $tag): Promise
    {
        $data = [$transaction, (string) $tag];
        return $this->callEndpoint('eth_estimateGas', 1, Gwei::class, $data);
    }

    /**
     * Returns information about a block by hash.
     * @see https://eth.wiki/json-rpc/API#eth_getBlockByHash
     *
     * @param Hash32 $hash Hash of a block.
     * @param bool $Full If true it returns the full transaction objects.
     *
     * @return Promise<Block>
     */
    public function ethGetBlockByHash(Hash32 $hash, bool $Full = true): Promise
    {
        $data = [(string) $hash, $Full];
        return $this->callEndpoint('eth_getBlockByHash', 1, Block::class, $data);
    }

    /**
     * Returns information about a block by block number.
     * @see https://eth.wiki/json-rpc/API#eth_getBlockByNumber
     *
     * @param Tag $tag Block number, or "latest", "earliest", "pending" as Tag.
     * @param bool $Full If true it returns the full transaction objects.
     *
     * @return Promise<Block>
     */
    public function ethGetBlockByNumber(Tag $tag, bool $Full = true): Promise
    {
        $data = [(string) $tag, $Full];
        return $this->callEndpoint('eth_getBlockByNumber', 1, Block::class, $data);
    }

    /**
     * Returns the information about a transaction requested by
     * transaction hash.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionByHash
     *
     * @param Hash32 $hash Hash of a transaction.
     *
     * @return Promise<Transaction|null> A transaction object, or null.
     */
    public function ethGetTransactionByHash(Hash32 $hash): Promise
    {
        $data = [(string) $hash];
        return $this->callEndpoint('eth_getTransactionByHash', 1, Transaction::class, $data);
    }

    /**
     * Returns the information about a transaction requested by
     * transaction hash.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionByBlockHashAndIndex
     *
     * @param Hash32 $hash Hash of a block.
     * @param int $position Integer of the transaction index position.
     *
     * @return Promise<Transaction|null> A transaction object, or null.
     */
    public function ethGetTransactionByBlockHashAndIndex(Hash32 $hash, int $position): Promise
    {
        $data = [(string) $hash, EthType::appendPrefix(intToHex($position))];
        return $this->callEndpoint('eth_getTransactionByBlockHashAndIndex', 1, Transaction::class, $data);
    }

    /**
     * Returns information about a transaction by block number and
     * transaction index position.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionByBlockNumberAndIndex
     *
     * @param Tag $tag Block number, or "latest", "earliest", "pending" as Tag.
     * @param int $position Integer of the transaction index position.
     *
     * @return Promise<Transaction|null> A transaction object, or null.
     */
    public function ethGetTransactionByBlockNumberAndIndex(Tag $tag, int $position): Promise
    {
        $data = [(string) $tag, EthType::appendPrefix(intToHex($position))];
        return $this->callEndpoint('eth_getTransactionByBlockNumberAndIndex', 1, Transaction::class, $data);
    }

    #endregion

    /**
     * Call an Ethereum RPC endpoint.
     *
     * @param string $method The method name from the RPC specification.
     * @param integer $id The method id from the RPC specification.
     * @param string $class The response result type.
     * @param array $data The RPC response data.
     * @param bool $iterate Whether to iterate the result data.
     *
     * @return Promise<mixed>
     */
    public function callEndpoint(
        string $method,
        int $id,
        string $class,
        array $data = [],
        bool $iterate = false
    ): Promise {
        return $this->getResult($this->getMessage($method, $id, $data), $class, $iterate);
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
    public function getResult(
        RequestInterface $request,
        string $class,
        bool $iterate = false
    ): Promise {
        return $this->send($request)
            ->then(function (ResponseInterface $resp) use ($class, $iterate) {
                $resp = $this->factory()->makeFromResponse($resp);
                $error = $resp->getRPCError();

                if ($error === null) {
                    $data = $resp->getRPCResult();

                    if (isset(Client::$dataMap[$class])) {
                        if ($iterate) {
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

    #region BaseInterface Members

    /**
     * {@inheritDoc}
     */
    public function factory(): RPCFactoryInterface
    {
        return parent::factory();
    }

    #endregion
}
