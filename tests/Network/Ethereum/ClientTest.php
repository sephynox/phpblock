<?php

/**
 * Ethereum Client Test.
 *
 * @package PHPBlock
 * @category Test
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

declare(strict_types=1);

namespace PHPBlock\Network\Ethereum;

use kornrunner\Keccak;
use PHPUnit\Framework\TestCase;
use PHPBlock\Network\Ethereum\Client;
use PHPBlock\Network\Ethereum\Model\Block;
use PHPBlock\Network\Ethereum\Model\Gwei;
use PHPBlock\Network\Ethereum\Model\SyncStatus;
use PHPBlock\Network\Ethereum\Model\Tag;
use PHPBlock\Network\Ethereum\Model\Transaction;
use PHPBlock\Network\Ethereum\Model\TransactionReceipt;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

final class ClientTest extends TestCase
{
    private static Client $client;
    private static HexAddress $to;
    private static HexAddress $from;
    private static HexAddress $testAddress;
    private static Transaction $testSendTransaction;
    private static Hash32 $transactionHash;
    private static Hash32 $testBlockHash;
    private static Gwei $testGwei;
    private static Tag $testTag;
    private static Tag $testBlockTag;
    private static int $testBlockNumber;

    public static function setUpBeforeClass(): void
    {
        static::$testGwei = new Gwei(Gwei::ethToGwei('.0001'));
        static::$client = new Client($_ENV['ETH_TEST_CLIENT']);
        static::$testTag = new Tag(Tag::LATEST);

        static::$client->ethAccounts()
            ->then(function (array $hexAddresses) {
                static::$to = $hexAddresses[0];
                static::$from = $hexAddresses[1];
                static::$testAddress = $hexAddresses[array_rand($hexAddresses)];
                static::$testSendTransaction = Transaction::make(
                    static::$to,
                    static::$from,
                    static::$testGwei
                );
                return static::$client->ethSendTransaction(static::$testSendTransaction);
            })->then(function (Hash32 $hash) {
                static::$transactionHash = $hash;
                return static::$client->ethGetTransactionReceipt($hash);
            })->then(function (TransactionReceipt $receipt) {
                static::$testBlockHash = $receipt->blockHash;
                static::$testBlockNumber = $receipt->blockNumber;
                static::$testBlockTag = new Tag($receipt->blockNumber);
            });

        static::$client->run();
    }

    /**
     * Test initialization of the Ethereum client.
     *
     * @return void
     */
    public function testClientInit(): void
    {
        $this->assertInstanceOf(Client::class, static::$client);
    }

    /**
     * Test  web3_clientVersion call.
     *
     * @return void
     */
    public function testWeb3ClientVersionCall(): void
    {
        /** @var string $ver */
        $ver = null;

        static::$client->web3ClientVersion()
            ->then(function (string $string) use (&$ver) {
                $ver = $string;
            });

        static::$client->run();
        $this->assertNotNull($ver);
    }

    /**
     * Test web3_sha3 call.
     *
     * @return void
     */
    public function testWeb3SHA3Call(): void
    {
        /** @var Hash32 $hash */
        $hash = null;
        $string = "Hello World!";
        $hash32 = new Hash32($string);
        $expect = "0x" . Keccak::hash($string, 256);

        static::$client->web3SHA3(new HexString($string, true))
            ->then(function ($hash32) use (&$hash) {
                $hash = $hash32;
            });

        static::$client->run();
        $this->assertInstanceOf(Hash32::class, $hash);
        $this->assertEquals($hash, $hash32);
        $this->assertEquals($expect, (string) $hash);
    }

    /**
     * Test net_version call.
     *
     * @return void
     */
    public function testNetVersionCall(): void
    {
        /** @var string $ver */
        $ver = null;

        static::$client->netVersion()
            ->then(function (string $string) use (&$ver) {
                $ver = $string;
            });

        static::$client->run();
        $this->assertNotNull($ver);
    }

    /**
     * Test net_listening call.
     *
     * @return void
     */
    public function testNetListeningCall(): void
    {
        /** @var bool $listen */
        $listen = null;

        static::$client->netListening()
            ->then(function (bool $bool) use (&$listen) {
                $listen = $bool;
            });

        static::$client->run();
        $this->assertIsBool($listen);
    }

    /**
     * Test net_peerCount call.
     *
     * @return void
     */
    public function testNetPeerCountCall(): void
    {
        /** @var int $peers */
        $peers = null;

        static::$client->netPeerCount()
            ->then(function (int $int) use (&$peers) {
                $peers = $int;
            });

        static::$client->run();
        $this->assertIsInt($peers);
    }

    /**
     * Test eth_protocolVersion call.
     *
     * @return void
     */
    public function testProtocolVersionCall(): void
    {
        /** @var string $ver */
        $ver = null;

        static::$client->ethProtocolVersion()
            ->then(function (string $version) use (&$ver) {
                $ver = $version;
            });

        static::$client->run();
        $this->assertNotNull($ver);
    }

    /**
     * Test eth_syncing call.
     *
     * @return void
     */
    public function testSyncingCall(): void
    {
        /** @var SyncStatus|bool $stat */
        $stat = null;

        static::$client->ethSyncing()
            ->then(function ($status) use (&$stat) {
                $stat = $status;
            });

        static::$client->run();
        $this->assertTrue($stat instanceof SyncStatus || is_bool($stat));
    }

    /**
     * Test eth_coinbase call.
     *
     * @return void
     */
    public function testEthCoinbaseCall(): void
    {
        /** @var HexAddress $addr */
        $addr = null;

        static::$client->ethCoinbase()
            ->then(function (HexAddress $address) use (&$addr) {
                $addr = $address;
            });

        static::$client->run();
        $this->assertInstanceOf(HexAddress::class, $addr);
    }

    /**
     * Test eth_mining call.
     *
     * @return void
     */
    public function testEthMiningCall(): void
    {
        /** @var bool $mining */
        $mining = null;

        static::$client->ethMining()
            ->then(function (bool $bool) use (&$mining) {
                $mining = $bool;
            });

        static::$client->run();
        $this->assertIsBool($mining);
    }

    /**
     * Test eth_hashrate call.
     *
     * @return void
     */
    public function testEthHashrateCall(): void
    {
        /** @var int $rate */
        $rate = null;

        static::$client->ethHashrate()
            ->then(function (int $int) use (&$rate) {
                $rate = $int;
            });

        static::$client->run();
        $this->assertIsInt($rate);
    }

    /**
     * Test eth_gasPrice call.
     *
     * @return void
     */
    public function testEthGasPriceCall(): void
    {
        /** @var Gwei|int $gas */
        $gas = null;

        static::$client->ethGasPrice()
            ->then(function ($gwei) use (&$gas) {
                $gas = $gwei;
            });

        static::$client->run();

        if (function_exists('bcdiv')) {
            $this->assertInstanceOf(Gwei::class, $gas);
        } else {
            $this->assertIsString($gas);
        }
    }

    /**
     * Test eth_accounts call.
     *
     * @return void
     */
    public function testEthAccountsCall(): void
    {
        /** @var array[HexAddress] */
        $addresses = [];

        static::$client->ethAccounts()
            ->then(function (array $hexAddresses) use (&$addresses) {
                $addresses = $hexAddresses;
            });

        static::$client->run();
        $this->assertIsArray($addresses);

        foreach ($addresses as $address) {
            $this->assertInstanceOf(HexAddress::class, $address);
        }
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testEthBlockNumberCall(): void
    {
        /** @var int $block */
        $block = null;

        static::$client->ethBlockNumber()
            ->then(function (int $int) use (&$block) {
                $block = $int;
            });

        static::$client->run();
        $this->assertIsInt($block);
    }

    /**
     * Test eth_getBalance call.
     *
     * @return void
     */
    public function testEthGetBalanceCall(): void
    {
        $balance = null;

        static::$client->ethGetBalance(static::$testAddress, static::$testTag)
            ->then(function ($gwei) use (&$balance) {
                $balance = $gwei;
            });

        static::$client->run();

        if (function_exists('bcdiv')) {
            $this->assertInstanceOf(Gwei::class, $balance);
        } else {
            $this->assertIsString($balance);
        }
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testEthGetTransactionCountCall(): void
    {
        /** @var int $count */
        $count = null;
        $tag = static::$testTag;

        static::$client->ethGetTransactionCount(static::$testAddress, $tag)
            ->then(function (int $int) use (&$count) {
                $count = $int;
            });

        static::$client->run();
        $this->assertIsInt($count);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthSendTransactionCall(): void
    {
        /** @var Hash32 $hash */
        $hash = null;
        $transaction = Transaction::make(static::$to, static::$from, static::$testGwei);

        static::$client->ethSendTransaction($transaction)
            ->then(function (Hash32 $hash32) use (&$hash) {
                $hash = $hash32;
            });

        static::$client->run();
        $this->assertInstanceOf(Hash32::class, $hash);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthCallCall(): void
    {
        /** @var string $data */
        $data = null;
        $transaction = Transaction::make(static::$to, static::$from);

        static::$client->ethCall($transaction, static::$testTag)
            ->then(function (string $string) use (&$data) {
                $data = $string;
            });

        static::$client->run();
        $this->assertIsString($data);
    }

    /**
     * Test eth_estimateGas call.
     *
     * @return void
     */
    public function testEthEstimateGasCall(): void
    {
        /** @var Gwei|int $gas */
        $gas = null;
        $transaction = Transaction::make(static::$to, static::$from);

        static::$client->ethEstimateGas($transaction, static::$testTag)
            ->then(function ($gwei) use (&$gas) {
                $gas = $gwei;
            });

        static::$client->run();

        if (function_exists('bcdiv')) {
            $this->assertInstanceOf(Gwei::class, $gas);
        } else {
            $this->assertIsString($gas);
        }
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testEthGetBlockTransactionCountByHashCall(): void
    {
        /** @var int $count */
        $count = null;

        static::$client->ethGetBlockTransactionCountByHash(static::$testBlockHash)
            ->then(function (int $int) use (&$count) {
                $count = $int;
            });

        static::$client->run();
        $this->assertIsInt($count);
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testEthGetBlockByNumberCall(): void
    {
        /** @var Block $testBlock */
        $testBlock = null;

        static::$client->ethGetBlockByNumber(static::$testBlockTag, true)
            ->then(function (Block $block) use (&$testBlock) {
                $testBlock = $block;
            });

        static::$client->run();
        $this->assertInstanceOf(Block::class, $testBlock);
        $this->assertEquals(static::$testBlockHash, $testBlock->hash);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthGetTransactionByHashCall(): void
    {
        /** @var Transaction $trans */
        $trans = null;

        static::$client->ethGetTransactionByHash(static::$transactionHash)
            ->then(function (Transaction $transaction) use (&$trans) {
                $trans = $transaction;
            });

        static::$client->run();
        $this->assertInstanceOf(Transaction::class, $trans);
        $this->assertInstanceOf(Hash32::class, $trans->hash);
        $this->assertEquals(static::$transactionHash, $trans->hash);
        $this->assertEquals(static::$testGwei, $trans->value);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthGetTransactionByBlockHashAndIndexCall(): void
    {
        /** @var Transaction $trans */
        $trans = null;

        static::$client->ethGetTransactionByBlockHashAndIndex(static::$testBlockHash, 0)
            ->then(function (Transaction $transaction) use (&$trans) {
                $trans = $transaction;
            });

        static::$client->run();
        $this->assertInstanceOf(Transaction::class, $trans);
        $this->assertInstanceOf(Hash32::class, $trans->hash);
        $this->assertEquals(static::$testGwei, $trans->value);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthGetTransactionByBlockNumberAndIndexCall(): void
    {
        /** @var Transaction $trans */
        $trans = null;

        static::$client->ethGetTransactionByBlockNumberAndIndex(static::$testTag, 0)
            ->then(function (Transaction $transaction) use (&$trans) {
                $trans = $transaction;
            });

        static::$client->run();
        $this->assertInstanceOf(Transaction::class, $trans);
        $this->assertInstanceOf(Hash32::class, $trans->hash);
        $this->assertEquals(static::$testGwei, $trans->value);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthGetTransactionReceipt(): void
    {
        /** @var TransactionReceipt $receipt */
        $receipt = null;

        static::$client->ethGetTransactionReceipt(static::$transactionHash)
            ->then(function (TransactionReceipt $transactionReceipt) use (&$receipt) {
                $receipt = $transactionReceipt;
            });

        static::$client->run();
        $this->assertInstanceOf(TransactionReceipt::class, $receipt);
        $this->assertInstanceOf(Hash32::class, $receipt->transactionHash);
        $this->assertIsInt($receipt->status);
        $this->assertEquals(static::$transactionHash, $receipt->transactionHash);
        $this->assertEquals(static::$to, $receipt->to);
        $this->assertEquals(static::$from, $receipt->from);
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testGetUncleByBlockHashAndIndexCall(): void
    {
        /** @var Block $testBlock */
        $testBlock = null;

        static::$client->ethGetUncleByBlockHashAndIndex(static::$testBlockHash, 0)
            ->then(function (Block $block) use (&$testBlock) {
                $testBlock = $block;
            });

        static::$client->run();
        $this->assertInstanceOf(Block::class, $testBlock);
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testGetUncleByBlockNumberAndIndexCall(): void
    {
        /** @var Block $testBlock */
        $testBlock = null;

        static::$client->ethGetUncleByBlockNumberAndIndex(static::$testTag, 0)
            ->then(function (Block $block) use (&$testBlock) {
                $testBlock = $block;
            });

        static::$client->run();
        $this->assertInstanceOf(Block::class, $testBlock);
    }
}
