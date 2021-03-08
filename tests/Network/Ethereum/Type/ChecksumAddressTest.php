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

use PHPBlock\Network\Ethereum\Type\ChecksumAddress;

final class ChecksumAddressTest extends EthTypeTest
{
    public function benchChecksumEncode(): void
    {
        $Address = new ChecksumAddress($this->getValue());
        $Address->checksumEncode($this->getValue());
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