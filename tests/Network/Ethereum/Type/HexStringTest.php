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

use PHPBlock\Network\Ethereum\Type\HexString;

final class HexStringTest extends EthTypeTest
{
    /**
     * Test the conversion.
     *
     * @return void
     */
    public function testString(): void
    {
        $this->assertIsString($this->instance->value());
        $this->assertEquals($this->instance->value(), 'test');
    }

    #region TypingTest Members

    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return HexString::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return '0x74657374';  # test
    }

    #endregion
}
