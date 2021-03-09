<?php

/**
 * Network Abstract Base Class.
 *
 * @package PHPBlock
 * @category Network
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @version $Revision: 0.1 $
 * @access public
 * @see https://github.com/sephynox/phpblock
 */

namespace PHPBlock\Network;

use PHPBlock\JSONRPC\Request;
use PHPBlock\JSONRPC\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory as Loop;
use React\EventLoop\LoopInterface;
use React\Http\Browser;
use React\Http\Server;

abstract class Base implements BaseInterface
{
    private static FactoryInterface $builder;
    protected Browser $browser;
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
        return $this->builder;
    }

    /**
     * {@inheritdoc}
     */
    public function loop(): LoopInterface
    {
        return $this->looper;
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
