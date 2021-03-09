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
require_once 'EthTypeTest.php';

use PHPBlock\Network\Ethereum\Type\Hash32;

final class Hash32Test extends EthTypeTest
{
    #region TypingTest Members

    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return Hash32::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return '0xba6c9192229ef4fc8615b510abd2c602f3805b1e51ff8892fb0964e1988ba1e2';
    }

    #endregion
}
