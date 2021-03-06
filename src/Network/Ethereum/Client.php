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
use Exception;
use Psr\Http\Message\ResponseInterface;
use React\Promise\Promise;
use PHPBlock\JSONRPC\RPCExceptionInterface;
use PHPBlock\JSONRPC\Factory;
use PHPBlock\JSONRPC\RequestInterface;
use PHPBlock\JSONRPC\RPCFactoryInterface;
use PHPBlock\Network\Base;
use PHPBlock\Network\Ethereum\Model\Block;
use PHPBlock\Network\Ethereum\Model\Filter;
use PHPBlock\Network\Ethereum\Model\Gwei;
use PHPBlock\Network\Ethereum\Model\Log;
use PHPBlock\Network\Ethereum\Model\SyncStatus;
use PHPBlock\Network\Ethereum\Model\Tag;
use PHPBlock\Network\Ethereum\Model\Transaction;
use PHPBlock\Network\Ethereum\Model\TransactionReceipt;
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\BlockIdentifier;
use PHPBlock\Network\Ethereum\Type\BlockNumber;
use PHPBlock\Network\Ethereum\Type\ChecksumAddress;
use PHPBlock\Network\Ethereum\Type\EthType;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;
use PHPBlock\Network\Ethereum\Type\Signature;

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
                Signature::class => fn ($v) => new Signature($v),
                HexAddress::class => fn ($v) => new HexAddress($v),
                Gwei::class => fn ($v) => EthType::gweiOrString($v),
                Transaction::class => fn ($v) => new Transaction($v),
                \int::class => fn ($v) => hexToInt(EthType::stripPrefix($v)),
                ChecksumAddress::class => fn ($v) => new ChecksumAddress($v),
                TransactionReceipt::class => fn ($v) => new TransactionReceipt($v),
                Log::class => fn ($v) => is_object($v) ? new Log($v) : new Hash32($v),
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
     * @param Address $address Address to check for balance.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<Gwei|string> Gwei of the current balance (string without bcmath).
     */
    public function ethGetBalance(Address $address, Tag $tag): Promise
    {
        $data = [(string) $address, (string) $tag];
        return $this->callEndpoint('eth_getBalance', 1, Gwei::class, $data);
    }

    /**
     * Returns the value from a storage position at a given address.
     * @see https://eth.wiki/json-rpc/API#eth_getStorageAt
     *
     * @param Address $address Address of the storage.
     * @param int $position Position in the storage.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<string> The value at this storage position.
     */
    public function ethGetStorageAt(Address $address, int $position, Tag $tag): Promise
    {
        $data = [(string) $address, EthType::appendPrefix(intToHex($position)), (string) $tag];
        return $this->callEndpoint('eth_getBalance', 1, \string::class, $data);
    }

    /**
     * Returns the number of transactions sent from an address.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionCount
     *
     * @param Address $address Address.
     * @param Tag $data Block number, or "latest", "earliest", "pending" as Tag.
     *
     * @return Promise<int> Number of transactions sent from this address.
     */
    public function ethGetTransactionCount(Address $address, Tag $tag): Promise
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
     * @return Promise<Block> A block object, or null when no block was found.
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

    /**
     * Returns the receipt of a transaction by transaction hash.
     * @see https://eth.wiki/json-rpc/API#eth_getTransactionReceipt
     *
     * @param Hash32 $hash Hash of a transaction.
     *
     * @return Promise<TransactionReceipt|null> A transaction receipt object, or null.
     */
    public function ethGetTransactionReceipt(Hash32 $hash): Promise
    {
        $data = [(string) $hash];
        return $this->callEndpoint('eth_getTransactionReceipt', 1, TransactionReceipt::class, $data);
    }

    /**
     * Returns information about a uncle of a block by hash and
     * uncle index position.
     * @see https://eth.wiki/json-rpc/API#eth_getUncleByBlockHashAndIndex
     *
     * @param Hash32 $hash Hash of a block.
     * @param int $position Integer of the the uncle???s index position.
     *
     * @return Promise<Block> A block object, or null when no block was found.
     */
    public function ethGetUncleByBlockHashAndIndex(Hash32 $hash, int $position): Promise
    {
        $data = [(string) $hash, EthType::appendPrefix(intToHex($position))];
        return $this->callEndpoint('eth_getUncleByBlockHashAndIndex', 1, Block::class, $data);
    }

    /**
     * Returns information about a uncle of a block by number and uncle
     * index position.
     * @see https://eth.wiki/json-rpc/API#eth_getUncleByBlockNumberAndIndex
     *
     * @param Tag $tag Block number, or "latest", "earliest", "pending" as Tag.
     * @param int The uncle???s index position.
     *
     * @return Promise<Block> A block object, or null when no block was found.
     */
    public function ethGetUncleByBlockNumberAndIndex(Tag $tag, int $position): Promise
    {
        $data = [(string) $tag, EthType::appendPrefix(intToHex($position))];
        return $this->callEndpoint('eth_getUncleByBlockNumberAndIndex', 1, Block::class, $data);
    }

    /**
     * Returns a list of available compilers in the client.
     * @see https://eth.wiki/json-rpc/API#eth_getCompilers
     *
     * @return Promise<array[string]> Array of available compilers.
     */
    public function ethGetCompilers(): Promise
    {
        return $this->callEndpoint('eth_getCompilers', 1, \string::class, [], true);
    }

    /**
     * TODO Solidity
     * Returns compiled solidity code.
     * @see https://eth.wiki/json-rpc/API#eth_compileSolidity
     *
     * @param string $source The source code.
     *
     * @return Promise<string> The compiled source code.
     */
    public function ethCompileSolidity(string $source): Promise
    {
        return $this->callEndpoint('eth_compileSolidity', 1, \string::class, [$source]);
    }

    /**
     * Returns compiled LLL code.
     * @see https://eth.wiki/json-rpc/API#eth_compileLLL
     *
     * @param string $source The source code.
     *
     * @return Promise<string> The compiled source code.
     */
    public function ethCompileLLL(string $source): Promise
    {
        return $this->callEndpoint('eth_compileLLL', 1, \string::class, [$source]);
    }

    /**
     * Returns compiled serpent code
     * @see https://eth.wiki/json-rpc/API#eth_compileSerpent
     *
     * @param string $source The source code.
     *
     * @return Promise<string> The compiled source code.
     */
    public function ethCompileSerpent(string $source): Promise
    {
        return $this->callEndpoint('eth_compileSerpent', 1, \string::class, [$source]);
    }

    /**
     * Creates a filter object, based on filter options, to notify when the
     * state changes (logs). To check if the state has changed,
     * call eth_getFilterChanges.
     * @see https://eth.wiki/json-rpc/API#eth_newFilter
     *
     * @param Filter $filter The filter options.
     *
     * @return Promise<int> A filter id.
     */
    public function ethNewFilter(Filter $filter): Promise
    {
        return $this->callEndpoint('eth_newFilter', 73, \int::class, [$filter]);
    }

    /**
     * Creates a filter in the node, to notify when a new block arrives.
     * To check if the state has changed, call eth_getFilterChanges.
     * @see https://eth.wiki/json-rpc/API#eth_newBlockFilter
     *
     * @return Promise<int> A filter id.
     */
    public function ethNewBlockFilter(): Promise
    {
        return $this->callEndpoint('eth_newBlockFilter', 73, \int::class);
    }

    /**
     * Creates a filter in the node, to notify when new pending transactions
     * arrive. To check if the state has changed, call eth_getFilterChanges.
     * @see https://eth.wiki/json-rpc/API#eth_newPendingTransactionFilter
     *
     * @return Promise<int> A filter id.
     */
    public function ethNewPendingTransactionFilter(): Promise
    {
        return $this->callEndpoint('eth_newPendingTransactionFilter', 73, \int::class);
    }

    /**
     * Uninstalls a filter with given id. Should always be called when watch
     * is no longer needed. Additionally, filters timeout when they are not
     * requested with eth_getFilterChanges for a period of time.
     * @see https://eth.wiki/json-rpc/API#eth_uninstallFilter
     *
     * @param int A filter id.
     *
     * @return Promise<bool> True if filter was uninstalled, otherwise false.
     */
    public function ethUninstallFilter(int $filter): Promise
    {
        $data = [EthType::appendPrefix(intToHex($filter))];
        return $this->callEndpoint('eth_uninstallFilter', 73, \bool::class, $data);
    }

    /**
     * Polling method for a filter, which returns an array of logs which
     * occurred since last poll.
     * @see https://eth.wiki/json-rpc/API#eth_getFilterChanges
     *
     * @param int A filter id.
     *
     * @return Promise<array[Hash32|Log]> Array of log objects or hashes.
     * For filters created with eth_newBlockFilter the return are block hashes
     * For filters created with eth_newPendingTransactionFilter the return
     * are transaction hashes.
     * For filters created with eth_newFilter logs are objects
     */
    public function ethGetFilterChanges(int $filter): Promise
    {
        $data = [EthType::appendPrefix(intToHex($filter))];
        return $this->callEndpoint('eth_getFilterChanges', 73, Log::class, $data, true);
    }

    /**
     * Returns an array of all logs matching filter with given id.
     * @see https://eth.wiki/json-rpc/API#eth_getFilterLogs
     *
     * @param int A filter id.
     *
     * @return Promise<array[Hash32|Log]> Array of log objects or hashes.
     * For filters created with eth_newBlockFilter the return are block hashes
     * For filters created with eth_newPendingTransactionFilter the return
     * are transaction hashes.
     * For filters created with eth_newFilter logs are objects
     */
    public function ethGetFilterLogs(int $filter): Promise
    {
        $data = [EthType::appendPrefix(intToHex($filter))];
        return $this->callEndpoint('eth_getFilterLogs', 74, Log::class, $data, true);
    }

    /**
     * Returns an array of all logs matching a given filter object.
     * @see https://eth.wiki/json-rpc/API#eth_getLogs
     *
     * @param Filter $filter The filter options.
     *
     * @return Promise<array[Hash32|Log]> Array of log objects or hashes.
     * For filters created with eth_newBlockFilter the return are block hashes
     * For filters created with eth_newPendingTransactionFilter the return
     * are transaction hashes.
     * For filters created with eth_newFilter logs are objects
     */
    public function ethGetLogs(Filter $filter): Promise
    {
        return $this->callEndpoint('eth_getLogs', 74, Log::class, [$filter], true);
    }

    /**
     * Returns the hash of the current block, the seedHash, and the boundary
     * condition to be met (???target???).
     * @see https://eth.wiki/json-rpc/API#eth_getWork
     *
     * @return Promise<array[Hash32]> Array with the following properties:
     * Current block header pow-hash.
     * The seed hash used for the DAG.
     * The boundary condition (???target???), 2^256 / difficulty.
     */
    public function ethGetWork(): Promise
    {
        return $this->callEndpoint('eth_getWork', 73, Hash32::class, [], true);
    }

    /**
     * Used for submitting a proof-of-work solution.
     * @see https://eth.wiki/json-rpc/API#eth_submitWork
     *
     * @param HexString $nonce The nonce found (64 bits)
     * @param Hash32 $pow The header???s pow-hash (256 bits)
     * @param Hash32 $digest The mix digest (256 bits)
     *
     * @return Promise<bool> Returns true if solution is valid, otherwise false.
     */
    public function ethSubmitWork(HexString $nonce, Hash32 $pow, Hash32 $digest): Promise
    {
        $data = [(string) $nonce, (string) $pow, (string) $digest];
        return $this->callEndpoint('eth_submitWork', 73, \bool::class, $data);
    }

    /**
     * Used for submitting a proof-of-work solution.
     * @see https://eth.wiki/json-rpc/API#eth_submitHashrate
     *
     * @return Promise<bool> Returns true if submit successful, otherwise false.
     */
    public function ethSubmitHashrate(HexString $hashrate, HexString $id): Promise
    {
        $data = [(string) $hashrate, (string) $id];
        return $this->callEndpoint('eth_submitHashrate', 73, \bool::class, $data);
    }

    #endregion

    #region Whisper (shh) Calls

    /**
     * Returns the current whisper protocol version.
     * @see https://eth.wiki/json-rpc/API#shh_version
     *
     * @return Promise<string> The current whisper protocol version.
     */
    public function shhVersion(): Promise
    {
        return $this->callEndpoint('shh_version', 67, \string::class);
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
     * @throws RPCExceptionInterface|Exception
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
            }, function (Exception $exception) {
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
