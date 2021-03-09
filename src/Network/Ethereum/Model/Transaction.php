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

class Transaction extends EthModel
{
    public int $blockNumber = null;
    public Hash32 $blockHash = null;
    public HexAddress $from;
    public int $gas;
    public int $gasPrice;
    public Hash32 $hash;
    public HexString $input;
    public int $none;
    public HexAddress $to = null;
    public int $transactionIndex = null;
    public int $value;
    public int $v;
    public Hash32 $r;
    public Hash32 $s;

    private static $map;

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        if (!isset(static::$map)) {
            static::$map = [
                'blockNumber' => EthModel::$dataMap[int::class],
                'blockHash' => EthModel::$dataMap[Hash32::class],
                'from' => EthModel::$dataMap[HexAddress::class],
                'gas' => EthModel::$dataMap[int::class],
                'gasPrice' => EthModel::$dataMap[int::class],
                'input' => EthModel::$dataMap[Hash32::class],
                'none' => EthModel::$dataMap[HexString::class],
                'to' => EthModel::$dataMap[int::class],
                'transactionIndex' => EthModel::$dataMap[HexAddress::class],
                'value' => EthModel::$dataMap[int::class],
                'v' => EthModel::$dataMap[int::class],
                'r' => EthModel::$dataMap[int::class],
                's' => EthModel::$dataMap[Hash32::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
