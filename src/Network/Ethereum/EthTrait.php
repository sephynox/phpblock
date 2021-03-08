<?php

/**
 * Ethereum Network Client.
 *
 * @package PHPBlock
 * @category Network
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Networks\Ethereum;

trait EthTrait
{
    public function sendRawTransaction(string $data): TransactionHash
    {
        $response = $this->client->send(
            $this->client->request(1, 'eth_sendRawTransaction', [$data])
        );

        return $response->getRpcResult();
    }
}
