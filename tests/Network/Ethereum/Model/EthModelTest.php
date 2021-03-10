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

use PHPUnit\Framework\TestCase;

abstract class EthModelTest extends TestCase
{
    /**
     * Test the default model functionality.
     */
    public function testModel(): void
    {
        $class = $this->getModelClass();
        $model = new $class($this->getValues());

        $this->assertInstanceOf($class, $model);
    }

    /**
     * Get the model class.
     *
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * Get the data array to test.
     *
     * @return mixed
     */
    abstract protected function getValues(): array;
}
