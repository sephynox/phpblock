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

class SyncStatus extends EthModel
{
    public int $startingBlock;
    public int $currentBlock;
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
                'startingBlock' => EthModel::$dataMap[int::class],
                'currentBlock' => EthModel::$dataMap[int::class],
                'highestBlock' => EthModel::$dataMap[int::class]
            ];
        }

        return static::$map;
    }

    #endregion
}
