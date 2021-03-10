<?php

/**
 * Ethereum Network Model.
 *
 * @package PHPBlock
 * @category Ethereum
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum\Model;

use PHPBlock\Network\Ethereum\Client;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

class Log extends EthModel
{
    /** @var int|bool */
    public $removed;
    public ?int $logIndex;
    public ?int $transactionIndex;
    public ?Hash32 $transactionHash;
    public ?Hash32 $blockHash;
    public ?int $blockNumber;
    public HexAddress $address;
    public HexString $data;  # TODO
    public array $topics;

    private static $map;

    /**
     * Mutate the transactions.
     *
     * @param array $data
     *
     * @return array
     */
    public function mutateTopics(array $data): array
    {
        return array_map(fn ($v) => new Hash32($v), $data);
    }

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        if (!isset(static::$map)) {
            static::$map = [
                'logIndex' => Client::$dataMap[\int::class],
                'transactionIndex' => Client::$dataMap[\int::class],
                'transactionHash' => Client::$dataMap[Hash32::class],
                'blockHash' => Client::$dataMap[Hash32::class],
                'blockNumber' => Client::$dataMap[\int::class],
                'address' => Client::$dataMap[HexAddress::class],
                'data' => Client::$dataMap[HexString::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
