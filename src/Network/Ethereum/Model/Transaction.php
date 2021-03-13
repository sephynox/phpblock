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
use PHPBlock\Network\Ethereum\Type\Signature;

class Transaction extends EthModel
{
    /**
     * Address of the receiver. null when it is a contract creation transaction.
     */
    public Address $to;
    /**
     * Address of the sender.
     */
    public Address $from;
    /**
     * Hash of the block where this transaction was in.
     * Null when it is pending.
     */
    public ?Hash32 $hash = null;
    /**
     * Block number where this transaction was in.
     * Null when it is pending.
     */
    public ?int $blockNumber = null;
    /**
     * Gas provided by the sender.
     *
     * @var Gwei|string
     */
    public $gas = null;
    /**
     * Gas price provided by the sender in Wei (Gwei object with bcmath).
     *
     * @var Gwei|string
     */
    public $gasPrice = null;
    /**
     * Hash of the block where this transaction was in.
     * Null when it is pending.
     */
    public ?Hash32 $blockHash = null;
    /**
     * The data send along with the transaction.
     */
    public ?HexString $input = null;
    /**
     * The number of transactions made by the sender prior to this one.
     */
    public ?int $nonce = null;
    /**
     * Integer of the transactions index position in the block.
     * Null when it is pending.
     */
    public ?int $transactionIndex = null;
    /**
     * Value transferred in Wei (Gwei object with bcmath).
     *
     * @var Gwei|string
     */
    public $value = null;
    /**
     * ECDSA recovery id.
     */
    public ?int $v = null;
    /**
     * ECDSA signature r.
     */
    public ?Signature $r = null;
    /**
     * ECDSA signature s.
     */
    public ?Signature $s = null;

    private static $map;

    /**
     * Alias for constructor.
     *
     * @param Address $to Address of the receiver. null when it is a contract
     * creation transaction.
     * @param Address $from Address of the sender.
     * @param Gwei|string $gas Gas provided by the sender
     * (Gwei object with bcmath).
     * @param Gwei|string $gasPrice Gas price provided by the sender in Wei
     * (Gwei object with bcmath).
     * @param Gwei|string $value Value transferred in Wei
     * (Gwei object with bcmath).
     * @param Hash32 $data The data send along with the transaction.
     *
     * @return Transaction
     */
    public static function make(
        Address $to,
        Address $from,
        $value = null,
        Hash32 $data = null,
        $gas = null,
        $gasPrice = null
    ): Transaction {
        $transaction = new static([]);

        $transaction->to = $to;
        $transaction->from = $from;
        $transaction->value = $value;
        $transaction->input = $data;
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
                'to' => Client::$dataMap[HexAddress::class],
                'from' => Client::$dataMap[HexAddress::class],
                'blockNumber' => Client::$dataMap[\int::class],
                'blockHash' => Client::$dataMap[Hash32::class],
                'gas' => Client::$dataMap[Gwei::class],
                'gasPrice' => Client::$dataMap[Gwei::class],
                'hash' => Client::$dataMap[Hash32::class],
                'input' => Client::$dataMap[HexString::class],
                'nonce' => Client::$dataMap[\int::class],
                'transactionIndex' => Client::$dataMap[\int::class],
                'value' => Client::$dataMap[Gwei::class],
                'v' => Client::$dataMap[\int::class],
                'r' => Client::$dataMap[Signature::class],
                's' => Client::$dataMap[Signature::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
