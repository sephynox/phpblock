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

namespace PHPBlock\Network\Ethereum\Model;

use PHPBlock\Network\Ethereum\Model\Gwei;
use PHPUnit\Framework\TestCase;

final class GweiTest extends TestCase
{
    /**
     * Test Gwei Conversions.
     *
     * @return void
     */
    public function testGweiConversions(): void
    {
        if (!function_exists('bcdiv')) {
            # Optional extension is not available.
            $this->assertTrue(true);
            return;
        }

        $gwei = new Gwei('5464653257');

        $this->assertEquals('5.464653257', $gwei->toEth());
        $this->assertEquals('5464653257000000000', $gwei->toWei());
        $this->assertEquals('0.005464567', Gwei::gweiToEth('5464567'));
        $this->assertEquals('45670', Gwei::ethToGwei('0.00004567'));
    }
}
