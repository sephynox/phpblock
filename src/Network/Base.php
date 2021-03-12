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
use React\Http\Message\Response;
use React\Http\Server;
use React\Promise\Deferred;

abstract class Base implements BaseInterface
{
    private static FactoryInterface $builder;
    private Browser $browser;
    private LoopInterface $looper;
    private bool $swooled = false;

    public function __construct(FactoryInterface $Factory)
    {
        if (class_exists(\Swoole\Coroutine\Http\Client::class)) {
            #TODO
            //$this->swooled = true;
        }

        $this->looper = Loop::create();
        $this->browser = new Browser($this->looper);
        static::$builder = $Factory;
    }

    #region BaseInterface Members

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
        $promise = null;

        if ($this->swooled) {
            $deferred = new Deferred();
            $intStatus = null;
            $strBody = null;

            \Swoole\Coroutine\run(function () use ($request, &$intStatus, &$strBody) {
                $cli = new \Swoole\Coroutine\Http\Client(
                    $this->factory()->getBaseUri(),
                    $this->factory()->getPort()
                );
                $cli->post(
                    $request->getUri(),
                    $request->getBody()->getContents()
                );

                $intStatus = $cli->statusCode;
                $strBody = $cli->body;

                $cli->close();
            });

            if ($this->swoole->statusCode != 200) {
                $except = new NetworkException('', $this->swoole->errCode);
                $deferred->reject($except);
            } else {
                $response = new Response(
                    $this->swoole->statusCode,
                    $this->swoole->headers,
                    $this->swoole->body
                );
                $deferred->resolve($response);
            }

            $promise = $deferred->promise();
        } else {
            $promise = $this->browser->post(
                $request->getUri(),
                $request->getHeaders(),
                $request->getBody()->getContents()
            );
        }

        return $promise;
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

    #endregion
}
