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

class TransactionReceipt extends EthModel
{
    /**
     * Hash of the transaction.
     */
    public Hash32 $transactionHash;
    /**
     * Integer of the transactions index position in the block.
     */
    public int $transactionIndex;
    /**
     * Hash of the block where this transaction was in.
     */
    public Hash32 $blockHash;
    /**
     * Block number where this transaction was in.
     */
    public int $blockNumber;
    /**
     * Address of the sender.
     */
    public Address $from;
    /**
     * Address of the receiver. null when it is a contract creation transaction.
     */
    public Address $to;
    /**
     * The total amount of gas used when this transaction was executed in
     * the block.
     *
     * @var Gwei|string
     */
    public $cumulativeGasUsed;
    /**
     * The amount of gas used by this specific transaction alone.
     *
     * @var Gwei|string
     */
    public $gasUsed;
    /**
     * The contract address created, if the transaction was a contract
     * creation, otherwise null.
     */
    public ?Address $contractAddress;
    /**
     * Array of log objects, which this transaction generated.
     *
     * @var Log[]
     */
    public array $logs;  # TODO
    /**
     * 256 Bytes - Bloom filter for light clients to quickly retrieve
     * related logs.
     */
    public int $logsBloom;  #TODO
    /**
     * Either 1 (success) or 0 (failure)
     */
    public int $status;

    private static $map;

    /**
     * Mutate the transactions.
     *
     * @param array $data
     *
     * @return array
     */
    public function mutateLogs(array $data): array
    {
        return [];  # TODO
    }

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        if (!isset(static::$map)) {
            static::$map = [
                'transactionHash' => Client::$dataMap[Hash32::class],
                'transactionIndex' => Client::$dataMap[\int::class],
                'blockHash' => Client::$dataMap[Hash32::class],
                'blockNumber' => Client::$dataMap[\int::class],
                'from' => Client::$dataMap[HexAddress::class],
                'to' => Client::$dataMap[HexAddress::class],
                'gasUsed' => Client::$dataMap[Gwei::class],
                'cumulativeGasUsed' => Client::$dataMap[Gwei::class],
                'contractAddress' => Client::$dataMap[HexAddress::class],
                'logsBloom' => Client::$dataMap[\int::class],
                'status' => Client::$dataMap[\int::class],
            ];
        }

        return static::$map;
    }

    #endregion
}
