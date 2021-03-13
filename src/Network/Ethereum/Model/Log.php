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
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

class Log extends EthModel
{
    /**
     * Address from which this log originated.
     */
    public Address $address;
    /**
     * Contains one or more 32 Bytes non-indexed arguments of the log.
     */
    public HexString $data;
    /**
     * Array of 0 to 4 32 Bytes DATA of indexed log arguments.
     * (In solidity: The first topic is the hash of the signature of the
     * event (e.g. Deposit(address,bytes32,uint256)), except you declared
     * the event with the anonymous specifier.)
     *
     * @var HexString[]
     */
    public array $topics;
    /**
     * True when the log was removed, due to a chain reorganization.
     * False if it is a valid log.
     *
     * @var int|bool
     */
    public $removed;
    /**
     * Integer of the log index position in the block.
     * Null when it is pending log.
     */
    public ?int $logIndex = null;
    /**
     * Integer of the transactions index position log was created from.
     * Null when it is pending log.
     */
    public ?int $transactionIndex = null;
    /**
     * Hash of the transactions this log was created from.
     * Null when it is pending log.
     */
    public ?Hash32 $transactionHash = null;
    /**
     * Hash of the block where this log was in.
     * Null when it is pending.
     * Null when it is pending log.
     */
    public ?Hash32 $blockHash = null;
    /**
     * The block number where this log was in.
     * Null when it is pending.
     * Null when it is pending log.
     */
    public ?int $blockNumber = null;

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
