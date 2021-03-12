<?php

/**
 * Type Test.
 *
 * @package PHPBlock
 * @category Test
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

declare(strict_types=1);

namespace PHPBlock\Network\Ethereum\Type;

require_once 'EthTypeTest.php';

use PHPBlock\Network\Ethereum\Type\HexAddress;

final class HexAddressTest extends EthTypeTest
{
    #region TypingTest Members

    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return HexAddress::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return '0x36273803306a3c22bc848f8db761e974697ece0d';
    }

    #endregion
}
