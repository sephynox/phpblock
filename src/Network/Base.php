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

use GuzzleHttp\ClientInterface as HttpClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Base
{
    protected $httpClient;

    public function __construct(string $endpoint, HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new \GuzzleHttp\Client([
            'base_uri' => $endpoint
        ]);
    }

    public function call(string $method)
    {
    }

    private function send(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->send($request);
    }
}
