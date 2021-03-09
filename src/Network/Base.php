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
use React\EventLoop\Factory as Loop;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;
use React\Http\Browser;
use React\Http\Server;

abstract class Base implements BaseInterface
{
    private static FactoryInterface $builder;
    private Browser $browser;
    private LoopInterface $looper;

    public function __construct(FactoryInterface $Factory)
    {
        $this->looper = Loop::create();
        $this->browser = new Browser($this->looper);
        static::$builder = $Factory;
    }

    /**
     * {@inheritdoc}
     */
    public function factory(): FactoryInterface
    {
        return static::$builder;
    }

    /**
     * {@inheritdoc}
     */
    public function &loop(): LoopInterface
    {
        return $this->looper;
    }

    /**
     * {@inheritdoc}
     */
    public function run(): void
    {
        $this->looper->run();
    }

    /**
     * {@inheritdoc}
     */
    public function send(RequestInterface $request): PromiseInterface
    {
        return $this->browser->post(
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody()->getContents()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function server(callable $response): Server
    {
        return new Server($this->loop(), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function client(): Browser
    {
        return $this->browser;
    }
}
