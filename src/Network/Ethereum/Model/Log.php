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

use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

class Log extends EthModel
{
    /** @var int|bool */
    public $removed;
    public int $logIndex = null;
    public int $transactionIndex = null;
    public Hash32 $transactionHash = null;
    public Hash32 $blockHash = null;
    public int $blockNumber = null;
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
                'logIndex' => EthModel::$dataMap[int::class],
                'transactionIndex' => EthModel::$dataMap[int::class],
                'transactionHash' => EthModel::$dataMap[Hash32::class],
                'blockHash' => EthModel::$dataMap[Hash32::class],
                'blockNumber' => EthModel::$dataMap[int::class],
                'address' => EthModel::$dataMap[HexAddress::class],
                'data' => EthModel::$dataMap[HexString::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
