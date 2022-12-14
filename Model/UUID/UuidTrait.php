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

namespace StfalconStudio\ApiBundle\Model\UUID;

/**
 * UuidTrait.
 */
trait UuidTrait
{
    /**
     * @return string
     */
    public function getId(): string
    {
        return (string) $this->id;
    }
}
