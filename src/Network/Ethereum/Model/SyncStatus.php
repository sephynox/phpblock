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

class SyncStatus extends EthModel
{
    /**
     * The block at which the import started (will only be reset, after the
     * sync reached his head).
     */
    public int $startingBlock;
    /**
     * The current block, same as eth_blockNumber.
     */
    public int $currentBlock;
    /**
     * The estimated highest block.
     */
    public int $highestBlock;

    private static $map;

    #region BaseModel Members

    /**
     * {@inheritdoc}
     */
    public function map(): array
    {
        if (!isset(static::$map)) {
            static::$map = [
                'startingBlock' => Client::$dataMap[\int::class],
                'currentBlock' => Client::$dataMap[\int::class],
                'highestBlock' => Client::$dataMap[\int::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
