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

class Filter extends EthModel
{
    /**
     * Integer block number, or "latest" for the last mined block or "pending",
     * "earliest" for not yet mined transactions.
     */
    public ?Tag $fromBlock = null;
    /**
     * Integer block number, or "latest" for the last mined block or "pending",
     * "earliest" for not yet mined transactions.
     */
    public ?Tag $toBlock = null;
    /**
     * Contract address or a list of addresses from which logs should originate.
     * @var Address[]
     */
    public ?array $address = null;
    /**
     * Array of 32 Bytes DATA topics. Topics are order-dependent. Each topic
     * can also be an array of DATA with “or” options.
     * @var HexString[]
     */
    public ?array $topics = null;
    /**
     * With the addition of EIP-234, blockHash will be a new filter option
     * which restricts the logs returned to the single block with the 32-byte
     * hash blockHash. Using blockHash is equivalent to
     * fromBlock = toBlock = the block number with hash blockHash.
     * If blockHash is present in in the filter criteria, then neither
     * fromBlock nor toBlock are allowed.
     */
    public ?Hash32 $blockhash = null;

    /**
     * Alias for constructor.
     *
     * @param Tag $fromBlock Integer block number, or "latest" for the last
     * mined block or "pending", "earliest" for not yet mined transactions.
     * @param Tag $toBlock Integer block number, or "latest" for the last
     * mined block or "pending", "earliest" for not yet mined transactions.
     * @param array $address Contract address or a list of addresses from
     * which logs should originate.
     * @param array $data Array of 32 Bytes DATA topics. Topics are
     * order-dependent. Each topic can also be an array of DATA
     * with “or” options.
     *
     * @return Filter
     */
    public static function make(
        Tag $fromBlock = null,
        Tag $toBlock = null,
        array $address = null,
        array $data = null
    ): Filter {
        $filter = new static([]);

        $filter->fromBlock = $fromBlock;
        $filter->toBlock = $toBlock;
        $filter->address = $address;
        $filter->data = $data;

        return $filter;
    }

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        return [];
    }

    #endregion
}
