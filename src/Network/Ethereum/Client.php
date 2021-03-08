<?php

/**
 * Ethereum Network Client.
 *
 * @package PHPBlock
 * @category Client
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Networks\Ethereum;

use PHPBlock\Network\Base;

class Client extends Base
{
    public function __construct(string $uri)
    {
        parent::__construct($uri);
    }
}
