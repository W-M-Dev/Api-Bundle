<?php
/*
 * This file is part of the StfalconApiBundle.
 *
 * (c) Stfalcon LLC <stfalcon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace StfalconStudio\ApiBundle\Model\Aggregate;

use StfalconStudio\ApiBundle\Model\Timestampable\TimestampableInterface;
use StfalconStudio\ApiBundle\Model\UUID\UuidInterface;

/**
 * AggregateRootInterface.
 */
interface AggregateRootInterface extends TimestampableInterface, UuidInterface
{
}
