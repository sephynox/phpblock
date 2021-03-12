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
    public HexAddress $to;
    public HexAddress $from;
    public $value = null;
    public $gas = null;
    public $gasPrice = null;
    public ?int $blockNumber = null;
    public ?Hash32 $blockHash = null;
    public ?Hash32 $data = null;
    public ?HexString $input = null;
    public ?int $nonce = null;
    public ?int $transactionIndex = null;
    public ?int $v = null;
    public ?Hash32 $r = null;
    public ?Hash32 $s = null;

    private static $map;

    /**
     * Alias for constructor.
     *
     * @param HexAddress $to
     * @param HexAddress $from
     * @param Gwei|string $gas
     * @param Gwei|string $gasPrice
     * @param Gwei|string $value
     * @param Hash32 $data
     * @return Transaction
     */
    public static function make(
        HexAddress $to,
        HexAddress $from,
        $value = null,
        Hash32 $data = null,
        $gas = null,
        $gasPrice = null
    ): Transaction {
        $transaction = new static([]);

        $transaction->to = $to;
        $transaction->from = $from;
        $transaction->value = $value;
        $transaction->data = $data;
        $transaction->gas = $gas;
        $transaction->gasPrice = $gasPrice;

        return $transaction;
    }

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        if (!isset(static::$map)) {
            static::$map = [
                'to' => Client::$dataMap[\int::class],
                'from' => Client::$dataMap[HexAddress::class],
                'blockNumber' => Client::$dataMap[\int::class],
                'blockHash' => Client::$dataMap[Hash32::class],
                'gas' => Client::$dataMap[Gwei::class],
                'gasPrice' => Client::$dataMap[Gwei::class],
                'input' => Client::$dataMap[Hash32::class],
                'none' => Client::$dataMap[HexString::class],
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
