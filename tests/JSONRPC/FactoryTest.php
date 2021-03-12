<?php

/**
 * JSON RPC Test.
 *
 * @package PHPBlock
 * @category Test
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

declare(strict_types=1);

namespace PHPBlock\JSONRPC;

use PHPUnit\Framework\TestCase;
use PHPBlock\JSONRPC\Factory;
use PHPBlock\JSONRPC\RequestInterface;
use PHPBlock\JSONRPC\Response;
use PHPBlock\JSONRPC\ResponseInterface;

final class FactoryTest extends TestCase
{
    private Factory $factory;

    public function setUp(): void
    {
        $this->factory = new Factory($_ENV['ETH_TEST_CLIENT']);
    }

    /**
     * Test making a JSON RPC Request Object.
     *
     * @return void
     */
    public function testMakeRequest(): void
    {
        $request = $this->factory->makeRequest('eth_protocolVersion', 67);
        $this->assertInstanceOf(RequestInterface::class, $request);
    }

    /**
     * Test making a JSON RPC Response Object.
     *
     * @return void
     */
    public function testMakeResponse(): void
    {
        $data = Response::getRPCResultTemplate();
        $data[Factory::KEY_RESULT] = ['test' => true];
        $response = $this->factory->makeResponse(200, json_encode($data));

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals($data[Factory::KEY_RESULT], $response->getRPCResult());
        $this->assertEquals($data[Factory::KEY_VERSION], $data[Factory::KEY_VERSION]);
        $this->assertEquals($data[Factory::KEY_ID], $data[Factory::KEY_ID]);
    }
}
