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
use PHPBlock\Network\Ethereum\Type\HexString;

class Transaction extends EthModel
{
    public ?int $blockNumber;
    public ?Hash32 $blockHash;
    public HexAddress $from;
    public int $gas;
    public int $gasPrice;
    public Hash32 $hash;
    public HexString $input;
    public int $none;
    public ?HexAddress $to;
    public ?int $transactionIndex;
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
                'blockNumber' => Client::$dataMap[\int::class],
                'blockHash' => Client::$dataMap[Hash32::class],
                'from' => Client::$dataMap[HexAddress::class],
                'gas' => Client::$dataMap[\int::class],
                'gasPrice' => Client::$dataMap[\int::class],
                'input' => Client::$dataMap[Hash32::class],
                'none' => Client::$dataMap[HexString::class],
                'to' => Client::$dataMap[\int::class],
                'transactionIndex' => Client::$dataMap[HexAddress::class],
                'value' => Client::$dataMap[\int::class],
                'v' => Client::$dataMap[\int::class],
                'r' => Client::$dataMap[\int::class],
                's' => Client::$dataMap[Hash32::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
