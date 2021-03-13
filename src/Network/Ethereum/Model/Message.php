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
use PHPBlock\Network\Ethereum\Client;
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

class Message extends EthModel
{
    /**
     * The hash of the message.
     */
    public Hash32 $hash;
    /**
     * The sender of the message, if a sender was specified.
     */
    public Address $from;
    /**
     * The receiver of the message, if a receiver was specified.
     */
    public Address $to;
    /**
     * Integer of the time in seconds when this message should expire (?).
     */
    public DateTime $expiry;
    /**
     * Integer of the time the message should float in the system in
     * seconds (?).
     */
    public int $ttl;
    /**
     * Integer of the unix timestamp when the message was sent.
     */
    public int $sent;
    /**
     * Array of DATA topics the message contained.
     *
     * @var HexString[]
     */
    public array $topics;
    /**
     * The payload of the message.
     */
    public HexString $payload;
    /**
     * Integer of the work this message required before it was send (?).
     */
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
        return array_map(fn ($v) => new HexString($v), $data);
    }

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        if (!isset(static::$map)) {
            static::$map = [
                'hash' => Client::$dataMap[Hash32::class],
                'from' => Client::$dataMap[HexAddress::class],
                'to' => Client::$dataMap[HexAddress::class],
                'expiry' => Client::$dataMap[DateTime::class],
                'ttl' => Client::$dataMap[\int::class],
                'sent' => Client::$dataMap[\int::class],
                'payload' => Client::$dataMap[HexString::class],
                'workProved' => Client::$dataMap[\int::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
