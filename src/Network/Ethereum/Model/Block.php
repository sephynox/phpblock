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
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

class Block extends EthModel
{
    public ?int $number;
    public ?Hash32 $hash;
    public Hash32 $parentHash;
    public ?int $nonce;
    public Hash32 $sha3Uncles;
    public ?int $logsBloom;
    public Hash32 $transactionsRoot;
    public Hash32 $stateRoot;
    public Hash32 $receiptsRoot;
    public HexAddress $miner;
    public int $difficulty;
    public int $totalDifficulty;
    public HexString $extraData;
    public int $size;
    public int $gasLimit;
    public int $gasUsed;
    public DateTime $timestamp;
    /** @var array[string|Transaction] */
    public array $transactions;
    public array $uncles;

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
