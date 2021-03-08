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

use PHPBlock\Network\Ethereum\Type\Address;

final class AddressTest extends EthTypeTest
{
    /**
     * {@inheritdoc}
     */
    protected function getType(): string
    {
        return Address::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue()
    {
        return strtolower('0xdbF03B407c01E7cD3CBea99509d93f8DDDC8C6FB');
    }
}