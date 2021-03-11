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

use PHPUnit\Framework\TestCase;
use PHPBlock\Network\Ethereum\Client;
use PHPBlock\Network\Ethereum\Model\SyncStatus;
use PHPBlock\Network\Ethereum\Type\HexAddress;

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
            ->then(function (string $version) use (&$ver) {
                $ver = $version;
            });

        $this->client->run();
        $this->assertNotNull($ver);
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
    public function testCoinbaseCall(): void
    {
        $addr = null;

        $this->client->ethCoinbase()
            ->then(function ($address) use (&$addr) {
                $addr = $address;
            });

        $this->client->run();
        $this->assertInstanceOf(HexAddress::class, $addr);
    }
}
