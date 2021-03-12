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
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

final class ClientTest extends TestCase
{
    private Client $client;
    private HexAddress $to;
    private HexAddress $from;
    private HexAddress $testAddress;

    public function setUp(): void
    {
        $this->client = new Client($_ENV['ETH_TEST_CLIENT']);

        $this->client->ethAccounts()
            ->then(function (array $hexAddresses) use (&$addresses) {
                $this->to = $hexAddresses[0];
                $this->from = $hexAddresses[1];
                $this->testAddress = $hexAddresses[array_rand($hexAddresses)];
            });

        $this->client->run();
    }

    /**
     * Test initialization of the Ethereum client.
     *
     * @return void
     */
    public function testClientInit(): void
    {
        $this->assertInstanceOf(Client::class, $this->client);
    }

    /**
     * Test  web3_clientVersion call.
     *
     * @return void
     */
    public function testweb3ClientVersionCall(): void
    {
        $ver = null;

        $this->client->web3ClientVersion()
            ->then(function (string $string) use (&$ver) {
                $ver = $string;
            });

        $this->client->run();
        $this->assertNotNull($ver);
    }

    /**
     * Test net_version call.
     *
     * @return void
     */
    public function testNetVersionCall(): void
    {
        $ver = null;

        $this->client->netVersion()
            ->then(function (string $string) use (&$ver) {
                $ver = $string;
            });

        $this->client->run();
        $this->assertNotNull($ver);
    }

    /**
     * Test net_listening call.
     *
     * @return void
     */
    public function testNetListeningCall(): void
    {
        $listen = null;

        $this->client->netListening()
            ->then(function (bool $bool) use (&$listen) {
                $listen = $bool;
            });

        $this->client->run();
        $this->assertIsBool($listen);
    }

    /**
     * Test net_peerCount call.
     *
     * @return void
     */
    public function testNetPeerCountCall(): void
    {
        $peers = null;

        $this->client->netPeerCount()
            ->then(function (int $int) use (&$peers) {
                $peers = $int;
            });

        $this->client->run();
        $this->assertIsInt($peers);
    }

    /**
     * Test eth_protocolVersion call.
     *
     * @return void
     */
    public function testProtocolVersionCall(): void
    {
        $ver = null;

        $this->client->ethProtocolVersion()
            ->then(function (string $version) use (&$ver) {
                $ver = $version;
            });

        $this->client->run();
        $this->assertNotNull($ver);
    }

    /**
     * Test web3_sha3 call.
     *
     * @return void
     */
    public function testWeb3SHA3Call(): void
    {
        $hash = null;
        $string = "Hello World!";
        $hash32 = new Hash32($string);
        $expect = "0x" . Keccak::hash($string, 256);

        $this->client->web3SHA3(new HexString($string, true))
            ->then(function ($hash32) use (&$hash) {
                $hash = $hash32;
            });

        $this->client->run();
        $this->assertInstanceOf(Hash32::class, $hash);
        $this->assertEquals($hash, $hash32);
        $this->assertEquals($expect, (string) $hash);
    }

    /**
     * Test eth_syncing call.
     *
     * @return void
     */
    public function testSyncingCall(): void
    {
        $stat = null;

        $this->client->ethSyncing()
            ->then(function ($status) use (&$stat) {
                $stat = $status;
            });

        $this->client->run();
        $this->assertTrue($stat instanceof SyncStatus || is_bool($stat));
    }

    /**
     * Test eth_coinbase call.
     *
     * @return void
     */
    public function testEthCoinbaseCall(): void
    {
        $addr = null;

        $this->client->ethCoinbase()
            ->then(function (HexAddress $address) use (&$addr) {
                $addr = $address;
            });

        $this->client->run();
        $this->assertInstanceOf(HexAddress::class, $addr);
    }

    /**
     * Test eth_mining call.
     *
     * @return void
     */
    public function testEthMiningCall(): void
    {
        $mining = null;

        $this->client->ethMining()
            ->then(function (bool $bool) use (&$mining) {
                $mining = $bool;
            });

        $this->client->run();
        $this->assertIsBool($mining);
    }

    /**
     * Test eth_hashrate call.
     *
     * @return void
     */
    public function testEthHashrateCall(): void
    {
        $rate = null;

        $this->client->ethHashrate()
            ->then(function (int $int) use (&$rate) {
                $rate = $int;
            });

        $this->client->run();
        $this->assertIsInt($rate);
    }

    /**
     * Test eth_gasPrice call.
     *
     * @return void
     */
    public function testEthGasPriceCall(): void
    {
        $price = null;

        $this->client->ethGasPrice()
            ->then(function ($gwei) use (&$price) {
                $price = $gwei;
            });

        $this->client->run();

        if (function_exists('bcdiv')) {
            $this->assertInstanceOf(Gwei::class, $price);
        } else {
            $this->assertIsString($price);
        }
    }

    /**
     * Test eth_accounts call.
     *
     * @return void
     */
    public function testEthAccountsCall(): void
    {
        $addresses = [];

        $this->client->ethAccounts()
            ->then(function (array $hexAddresses) use (&$addresses) {
                $addresses = $hexAddresses;
            });

        $this->client->run();
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
        $block = null;

        $this->client->ethBlockNumber()
            ->then(function (int $int) use (&$block) {
                $block = $int;
            });

        $this->client->run();
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

        $this->client->ethGetBalance($this->testAddress, new Tag(Tag::LATEST))
            ->then(function ($gwei) use (&$balance) {
                $balance = $gwei;
            });

        $this->client->run();

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
        $count = null;
        $tag = new Tag(Tag::LATEST);

        $this->client->ethGetTransactionCount($this->testAddress, $tag)
            ->then(function (int $int) use (&$count) {
                $count = $int;
            });

        $this->client->run();
        $this->assertIsInt($count);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthSendTransactionCall(): void
    {
        $hash = null;
        $transaction = Transaction::make(
            $this->to,
            $this->from,
            new Gwei(Gwei::ethToGwei('.0001'))
        );

        $this->client->ethSendTransaction($transaction)
            ->then(function (Hash32 $hash32) use (&$hash) {
                $hash = $hash32;
            });

        $this->client->run();
        $this->assertInstanceOf(Hash32::class, $hash);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthCallCall(): void
    {
        $data = null;
        $transaction = Transaction::make($this->to, $this->from);

        $this->client->ethCall($transaction, new Tag(Tag::LATEST))
            ->then(function (string $string) use (&$data) {
                $data = $string;
            });

        $this->client->run();
        $this->assertIsString($data);
    }

    /**
     * Test eth_estimateGas call.
     *
     * @return void
     */
    public function testEthEstimateGasCall(): void
    {
        $gas = null;
        $transaction = Transaction::make($this->to, $this->from);

        $this->client->ethEstimateGas($transaction, new Tag(Tag::LATEST))
            ->then(function ($gwei) use (&$gas) {
                $gas = $gwei;
            });

        $this->client->run();

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
        $count = null;

        $this->client->ethGetBlockByNumber(new Tag(Tag::EARLIEST), true)
            ->then(function (Block $block) {
                return $this->client->ethGetBlockTransactionCountByHash($block->hash);
            })->then(function (int $int) use (&$count) {
                $count = $int;
            });

        $this->client->run();
        $this->assertIsInt($count);
    }

    /**
     * Test eth_blockNumber call.
     *
     * @return void
     */
    public function testEthGetBlockByNumberCall(): void
    {
        $blck = null;

        $this->client->ethGetBlockByNumber(new Tag(Tag::EARLIEST), true)
            ->then(function (Block $block) use (&$blck) {
                $blck = $block;
            });

        $this->client->run();
        $this->assertInstanceOf(Block::class, $blck);
    }

    /**
     * Test eth_call call.
     *
     * @return void
     */
    public function testEthGetTransactionByHashCall(): void
    {
        /** @var Hash32 */
        $hash = null;
        /** @var Transaction */
        $trans = null;
        $value = new Gwei(Gwei::ethToGwei('.0001'));
        $transact = Transaction::make($this->to, $this->from, $value);

        $this->client->ethSendTransaction($transact)
            ->then(function (Hash32 $hash32) use (&$hash) {
                $hash = $hash32;
                return $hash;
            })->then(function (Hash32 $hash32) {
                return $this->client->ethGetTransactionByHash($hash32);
            })->then(function ($transaction) use (&$trans) {
                $trans = $transaction;
            });

        $this->client->run();
        $this->assertInstanceOf(Transaction::class, $trans);
        $this->assertInstanceOf(Hash32::class, $trans->hash);
        $this->assertEquals($hash, $trans->hash);
        $this->assertEquals($value, $trans->value);
    }
}
