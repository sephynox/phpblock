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

use PHPBlock\Network\Ethereum\Type\BlockNumber;

final class BlockNumberTest extends EthTypeTest
{
    /**
     * Test the conversion.
     *
     * @return void
     */
    public function testNumber(): void
    {
        $this->assertIsInt($this->instance->value());
        $this->assertEquals($this->instance->value(), 8149882);
    }

    #region TypingTest Members

    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return BlockNumber::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return '0x7c5b7a';  # 8149882
    }

    #endregion
}
