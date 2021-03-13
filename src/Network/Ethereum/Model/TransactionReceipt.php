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

class TransactionReceipt extends EthModel
{
    public Hash32 $transactionHash;
    public int $transactionIndex;
    public Hash32 $blockHash;
    public int $blockNumber;
    public HexAddress $from;
    public HexAddress $to;
    public $cumulativeGasUsed;
    public $gasUsed;
    public ?HexAddress $contractAddress;
    public array $logs;  # TODO
    public int $logsBloom;  #TODO
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
