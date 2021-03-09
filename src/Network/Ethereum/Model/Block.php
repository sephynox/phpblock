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

class Block extends EthModel
{
    public int $number = null;
    public Hash32 $hash = null;
    public Hash32 $parentHash;
    public int $nonce = null;
    public Hash32 $sha3Uncles;
    public int $logsBloom = null;  #TODO
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
        return [];  # TODO
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
                'number' => EthModel::$dataMap[int::class],
                'hash' => EthModel::$dataMap[Hash32::class],
                'parentHash' => EthModel::$dataMap[Hash32::class],
                'nonce' => EthModel::$dataMap[int::class],
                'sha3Uncles' => EthModel::$dataMap[Hash32::class],
                'logsBloom' => EthModel::$dataMap[int::class],
                'transactionsRoot' => EthModel::$dataMap[Hash32::class],
                'stateRoot' => EthModel::$dataMap[Hash32::class],
                'receiptsRoot' => EthModel::$dataMap[Hash32::class],
                'miner' => EthModel::$dataMap[HexAddress::class],
                'difficulty' => EthModel::$dataMap[int::class],
                'totalDifficulty' => EthModel::$dataMap[int::class],
                'extraData' => EthModel::$dataMap[HexString::class],
                'size' => EthModel::$dataMap[int::class],
                'gasLimit' => EthModel::$dataMap[int::class],
                'gasUsed' => EthModel::$dataMap[int::class],
                'timestamp' => EthModel::$dataMap[DateTime::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
