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

class TransactionReceipt extends EthModel
{
    public Hash32 $transactionHash;
    public int $transactionIndex;
    public Hash32 $blockHash;
    public int $blockNumber;
    public HexAddress $from;
    public HexAddress $to;
    public int $cumulativeGasUsed;
    public int $gasUsed;
    public ?HexAddress $contractAddress;
    public array $logs;
    public int $logsBloom;  #TODO

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
                'transactionHash' => EthModel::$dataMap[Hash32::class],
                'transactionIndex' => EthModel::$dataMap[int::class],
                'blockHash' => EthModel::$dataMap[Hash32::class],
                'blockNumber' => EthModel::$dataMap[int::class],
                'from' => EthModel::$dataMap[HexAddress::class],
                'to' => EthModel::$dataMap[HexAddress::class],
                'cumulativeGasUsed' => EthModel::$dataMap[int::class],
                'gasUsed' => EthModel::$dataMap[int::class],
                'contractAddress' => EthModel::$dataMap[HexAddress::class],
                'logsBloom' => EthModel::$dataMap[int::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
