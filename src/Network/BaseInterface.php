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

use React\EventLoop\LoopInterface;
use React\Http\Server;
use PHPBlock\Network\FactoryInterface;

interface BaseInterface
{
    public function factory(): FactoryInterface;

    public function loop(): LoopInterface;

    public function server(callable $response): Server;
}
