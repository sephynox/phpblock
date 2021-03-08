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

use PHPBlock\Network\Ethereum\Type\Address;
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
     * Test checksum matches EIP-55.
     * @see https://eips.ethereum.org/EIPS/eip-55
     *
     * @return void
     */
    public function testChecksumEncode(): void
    {
        $this->instance = new Address($_ENV['ETH_CHECKSUM_ADDRESS']);
        $this->assertEquals(
            $_ENV['ETH_CHECKSUM_ADDRESS'],
            $this->instance->checksumEncode($_ENV['ETH_CHECKSUM_ADDRESS'])
        );
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
