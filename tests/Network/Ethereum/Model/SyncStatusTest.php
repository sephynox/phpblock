<?php

/**
 * Model Test.
 *
 * @package PHPBlock
 * @category Test
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

declare(strict_types=1);

namespace PHPBlock\Network\Ethereum\Model;

require_once 'EthModelTest.php';

use PHPBlock\Network\Ethereum\Model\SyncStatus;

use function PHPBlock\Helper\intToHex;

final class SyncStatusTest extends EthModelTest
{
    #region EthModelTest Members

    /**
     * {@inheritdoc}
     */
    protected function getModelClass(): string
    {
        return SyncStatus::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValues(): array
    {
        return [
            'startingBlock' => intToHex(234),
            'currentBlock' => intToHex(34534636),
            'highestBlock' => intToHex(2423445352212)
        ];
    }

    #endregion
}
