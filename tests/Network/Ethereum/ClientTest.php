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

use kornrunner\Keccak;
use PHPUnit\Framework\TestCase;
use PHPBlock\Network\Ethereum\Client;
use PHPBlock\Network\Ethereum\Model\Gwei;
use PHPBlock\Network\Ethereum\Model\SyncStatus;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

final class ClientTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client($_ENV['ETH_TEST_CLIENT']);
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
            $this->assertIsInt($price);
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
}
