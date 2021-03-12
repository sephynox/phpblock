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

use kornrunner\Keccak;
use PHPBlock\Network\Ethereum\Type\Hash32;

final class Hash32Test extends EthTypeTest
{
    /**
     * Benchmark the Hash32 string to hash functionality.
     * @Revs(1000)
     */
    public function benchHash32String(): void
    {
        $hash32 = new Hash32("Hello World!");
    }

    /**
     * Benchmark the Hash32 string to hash functionality.
     * @Revs(1000)
     */
    public function benchKeccak(): void
    {
        $expect = "0x" . Keccak::hash("Hello World!", 256);
    }

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
