<?php

/**
 * Ethereum Network Model.
 *
 * @package PHPBlock
 * @category Ethereum
 * @author Tanveer Wahid <tan@wahid.email>
 * @license MIT
 * @access public
 */

namespace PHPBlock\Network\Ethereum\Model;

use DateTime;
use PHPBlock\Model\BaseModel;
use PHPBlock\Network\Ethereum\Type\Address;
use PHPBlock\Network\Ethereum\Type\BlockIdentifier;
use PHPBlock\Network\Ethereum\Type\BlockNumber;
use PHPBlock\Network\Ethereum\Type\ChecksumAddress;
use PHPBlock\Network\Ethereum\Type\Hash32;
use PHPBlock\Network\Ethereum\Type\HexAddress;
use PHPBlock\Network\Ethereum\Type\HexString;

use function PHPBlock\Helper\hexToInt;
use function PHPBlock\Helper\hexToStr;

abstract class EthModel extends BaseModel
{
    public static $dataMap;

    public function __construct($data)
    {
        if (!isset(static::$dataMap)) {
            static::$dataMap = [
                int::class => fn ($v) => hexToInt($v),
                string::class => fn ($v) => hexToStr($v),
                Address::class => fn ($v) => new Address($v),
                HexAddress::class => fn ($v) => new HexAddress($v),
                ChecksumAddress::class => fn ($v) => new ChecksumAddress($v),
                DateTime::class => fn ($v) => (new DateTime())->setTimestamp($v),
                BlockIdentifier::class => fn ($v) => new BlockIdentifier($v),
                BlockNumber::class => fn ($v) => new BlockNumber($v),
                SyncStatus::class => fn ($v) => new SyncStatus($v),
                HexString::class => fn ($v) => new HexString($v),
                Hash32::class => fn ($v) => new Hash32($v),
                Block::class => fn ($v) => new Block($v)
            ];
        }

        parent::__construct($data);
    }
}
