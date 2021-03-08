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
require_once __DIR__ . '/../../../Type/TypingTest.php';

use PHPBlock\Network\Ethereum\Type\EthType;

abstract class EthTypeTest extends TypingTest
{
    /**
     * @var EthType
     */
    protected $instance;

    /**
     * Test the conversion.
     *
     * @return void
     */
    public function testToEth(): void
    {
        $this->assertEquals($this->value, $this->instance->toEth());
    }

    /**
     * Get the type class.
     *
     * @return string
     */
    abstract protected function getType(): string;

    /**
     * Get the type value to test.
     *
     * @return mixed
     */
    abstract protected function getValue();
}
