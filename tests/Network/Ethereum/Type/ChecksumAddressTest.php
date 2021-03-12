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

use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\ChecksumAddress;

final class ChecksumAddressTest extends EthTypeTest
{
    /**
     * Benchmark the checksumEncode functionality.
     * @Revs(1000)
     */
    public function benchChecksumEncode(): void
    {
        $Address = new ChecksumAddress($this->getValue());
        $Address->checksumEncode($this->getValue());
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

    #region TypingTest Members

    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return ChecksumAddress::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return '0xdbF03B407c01E7cD3CBea99509d93f8DDDC8C6FB';
    }

    #endregion
}
