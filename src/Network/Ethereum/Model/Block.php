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

class Block extends EthModel
{
    /**
     * Hash of the parent block.
     */
    public Hash32 $parentHash;
    /**
     * SHA3 of the uncles data in the block.
     */
    public Hash32 $sha3Uncles;
    /**
     * The root of the transaction trie of the block.
     */
    public Hash32 $transactionsRoot;
    /**
     * The root of the final state trie of the block.
     */
    public Hash32 $stateRoot;
    /**
     * The root of the receipts trie of the block.
     */
    public Hash32 $receiptsRoot;
    /**
     * the address of the beneficiary to whom the mining rewards were given.
     */
    public Address $miner;
    /**
     * Integer of the difficulty for this block.
     */
    public int $difficulty;
    /**
     * Integer of the total difficulty of the chain until this block.
     */
    public int $totalDifficulty;
    /**
     * The “extra data” field of this block.
     */
    public HexString $extraData;
    /**
     * Integer the size of this block in bytes.
     */
    public int $size;
    /**
     * The maximum gas allowed in this block.
     */
    public int $gasLimit;
    /**
     * The total used gas by all transactions in this block.
     */
    public int $gasUsed;
    /**
     * The unix timestamp (DateTime object) for when the block was collated.
     */
    public DateTime $timestamp;
    /**
     * Array of transaction objects, or 32 Bytes transaction hashes depending
     * on the last given parameter.
     *
     * @var array[string|Transaction]
     */
    public array $transactions;
    /**
     * Array of uncle hashes.
     *
     * @var array[Hash32]
     */
    public array $uncles;
    /**
     * The block number.
     * Null when it is pending block.
     */
    public ?int $number = null;
    /**
     * Hash of the block.
     * Null when it is pending block.
     */
    public ?Hash32 $hash = null;
    /**
     * Hash of the generated proof-of-work.
     * Null when it is pending block.
     */
    public ?int $nonce = null;
    /**
     * The bloom filter for the logs of the block.
     * Null when its pending block.
     */
    public ?int $logsBloom = null;

    private static $map;

    /**
     * Mutate the transactions.
     *
     * @param array $data
     *
     * @return array
     */
    public function mutateTransactions(array $data): array
    {
        foreach ($data as &$v) {
            if (is_array($v)) {
                $v = new Transaction($v);
            } else {
                $v = new Hash32($v);
            }
        }
        return $data;
    }

    /**
     * Mutate the uncles.
     *
     * @param array $data
     *
     * @return array
     */
    public function mutateUncles(array $data): array
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
                'number' => Client::$dataMap[\int::class],
                'hash' => Client::$dataMap[Hash32::class],
                'parentHash' => Client::$dataMap[Hash32::class],
                'nonce' => Client::$dataMap[\int::class],
                'sha3Uncles' => Client::$dataMap[Hash32::class],
                'logsBloom' => Client::$dataMap[\int::class],
                'transactionsRoot' => Client::$dataMap[Hash32::class],
                'stateRoot' => Client::$dataMap[Hash32::class],
                'receiptsRoot' => Client::$dataMap[Hash32::class],
                'miner' => Client::$dataMap[HexAddress::class],
                'difficulty' => Client::$dataMap[\int::class],
                'totalDifficulty' => Client::$dataMap[\int::class],
                'extraData' => Client::$dataMap[HexString::class],
                'size' => Client::$dataMap[\int::class],
                'gasLimit' => Client::$dataMap[\int::class],
                'gasUsed' => Client::$dataMap[\int::class],
                'timestamp' => Client::$dataMap[DateTime::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
