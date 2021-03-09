<?php

/**
 * JSON RPC Request Interface.
 *
 * @package PHPBlock
 * @category Network
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network;

use React\Http\Server;
use React\Http\Browser;
use React\EventLoop\LoopInterface;
use PHPBlock\Network\FactoryInterface;

interface BaseInterface
{
    public function factory(): FactoryInterface;

    public function loop(): LoopInterface;

    public function server(callable $response): Server;

    public function client(): Browser;
}
