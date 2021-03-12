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

use PHPBlock\Network\Ethereum\Model\Block;

use function PHPBlock\Helper\intToHex;

final class BlockTest extends EthModelTest
{
    #region EthModelTest Members

    /**
     * {@inheritdoc}
     */
    protected function getModelClass(): string
    {
        return Block::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValues(): array
    {
        return [
            'number' => intToHex((int) $_ENV['ETH_BLOCK_HEIGHT']),
            'hash' => $_ENV['ETH_BLOCK_HASH'],
            'parentHash' => $_ENV['ETH_BLOCK_PARENT_HASH'],
            'nonce' => intToHex((int) $_ENV['ETH_BLOCK_NONCE']),
            'sha3Uncles' => $_ENV['ETH_SHA3_UNCLES'],
            'logsBloom' => $_ENV['ETH_LOG_BLOOM'],
            'transactionsRoot' => $_ENV['ETH_BLOCK_TRANSACTION_ROOT'],
            'stateRoot' => $_ENV['ETH_BLOCK_STATE_ROOT'],
            'receiptsRoot' => $_ENV['ETH_BLOCK_RECEIPTS_ROOT'],
            'miner' => $_ENV['ETH_BLOCK_MINER_ADDRESS'],
            'difficulty' => intToHex(5456494928761240),
            'totalDifficulty' => intToHex((int) '21873526827438340765149'),
            'extraData' => $_ENV['ETH_BLOCK_EXTRA_DATA'],
            'size' => intToHex(65751),
            'gasLimit' => intToHex(12500000),
            'gasUsed' => intToHex(12480186),
            'timestamp' => intToHex(1615333546),
            'transactions' => [
                $_ENV['ETH_BLOCK_TRANSACTION_HASH'],
                $_ENV['ETH_BLOCK_TRANSACTION_HASH'],
                $_ENV['ETH_BLOCK_TRANSACTION_HASH'],
            ],
            'uncles' => [$_ENV['ETH_SHA3_UNCLES'], $_ENV['ETH_SHA3_UNCLES']]
        ];
    }

    #endregion
}
