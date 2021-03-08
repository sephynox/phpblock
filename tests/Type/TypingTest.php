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

use PHPUnit\Framework\TestCase;
use PHPBlock\Type\Typing;

abstract class TypingTest extends TestCase
{
    /**
     * @var string
     */
    protected string $type;

    /**
     * @var Typing
     */
    protected $instance;

    /**
     * @var mixed
     */
    protected $value;

    protected function setUp(): void
    {
        $this->type = $this->getType();
        $this->value = $this->getValue();
        $this->instance = new $this->type($this->value);
    }

    /**
     * Test the conversion.
     *
     * @return void
     */
    public function testConvert(): void
    {
        $byteUnpack = $this->instance->value();

        $this->assertInstanceOf($this->type, $this->instance);
        $this->assertEquals($this->value, $this->instance->pack($byteUnpack));
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
