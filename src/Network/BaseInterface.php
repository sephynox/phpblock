<?php

/**
 * Network Base.
 *
 * @package PHPBlock
 * @category Network
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network;

use Psr\Http\Message\RequestInterface;
use React\Http\Server;
use React\Http\Browser;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use PHPBlock\Network\FactoryInterface;

interface BaseInterface
{
    /**
     * Return a Request/Response factory.
     *
     * @return FactoryInterface
     */
    public function factory(): FactoryInterface;

    /**
     * Return the loop interface.
     *
     * @return LoopInterface
     */
    public function &loop(): LoopInterface;

    /**
     * Run the loop.
     *
     * @return void
     */
    public function run(): void;

    /**
     * Send a request.
     *
     * @param JSONRPCRequestInterface $request
     * @return PromiseInterface
     */
    public function send(RequestInterface $request): PromiseInterface;

    /**
     * Return the server instance.
     *
     * @param callable $response
     *
     * @return Server
     */
    public function server(callable $response): Server;

    /**
     * Return an outbound http client.
     *
     * @return Browser
     */
    public function client(): Browser;
}
