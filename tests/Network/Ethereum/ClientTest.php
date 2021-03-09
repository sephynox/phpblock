<?php

/**
 * Etherem Client Test.
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
     * eth_protocolVersion
     *
     * @return void
     */
    public function testProtocolVersionCall(): void
    {
        $this->assertTrue(true);

        $this->client->protocolVersion()
            ->then(function (string $version) {
                var_dump("Run5");
                var_dump($version);
            });

        $this->client->run();
    }
}
