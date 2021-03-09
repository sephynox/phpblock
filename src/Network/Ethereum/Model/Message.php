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

use DateTime;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

class Message extends EthModel
{
    public Hash32 $hash;
    public HexAddress $from;
    public HexAddress $to;
    public DateTime $expiry;
    public int $ttl;
    public int $sent;
    public array $topics;
    public HexString $payload;  # TODO
    public int $workProved;

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
                'hash' => EthModel::$dataMap[Hash32::class],
                'from' => EthModel::$dataMap[HexAddress::class],
                'to' => EthModel::$dataMap[HexAddress::class],
                'expiry' => EthModel::$dataMap[DateTime::class],
                'ttl' => EthModel::$dataMap[int::class],
                'sent' => EthModel::$dataMap[int::class],
                'payload' => EthModel::$dataMap[HexString::class],
                'workProved' => EthModel::$dataMap[int::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
