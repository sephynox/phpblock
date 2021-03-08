<?php

/**
 * Ethereum Network Client.
 *
 * @package PHPBlock
 * @category Client
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum;

use PHPBlock\JSONRPC\Factory;
use PHPBlock\JSONRPC\Request;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Promise\PromiseInterface;
use React\Promise\Deferred;
use React\Http\Server;
use React\Http\Message\Response;
use PHPBlock\Network\Base;

class Client extends Base
{
    public function __construct(string $uri)
    {
        parent::__construct(new Factory($uri));
    }

    public function protocolVersion() #: PromiseInterface
    {
        // $deferred = new Deferred();
        // $request = $this->factory()->makeRequest(__METHOD__, 67);

        // $this->client->request()
        //     if ($error) {
        //         $deferred->reject($error);
        //     } else {
        //         $deferred->resolve($result);
        //     }
        // });

        // return $deferred->promise();
    }
}
