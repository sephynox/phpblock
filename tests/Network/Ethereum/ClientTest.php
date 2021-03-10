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
     * Test eth_protocolVersion call.
     *
     * @return void
     */
    public function testProtocolVersionCall(): void
    {
        $ver = null;

        $this->client->protocolVersion()
            ->then(function (string $version) use (&$ran, &$ver) {
                $ver = $version;
            });

        $this->client->run();
        $this->assertNotNull($ver);
    }
}
