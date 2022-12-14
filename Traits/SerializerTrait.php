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

namespace StfalconStudio\ApiBundle\Traits;

use StfalconStudio\ApiBundle\Serializer\Serializer;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * SerializerTrait.
 */
trait SerializerTrait
{
    protected Serializer $serializer;

    /**
     * @param Serializer $serializer
     */
    #[Required]
    public function setSerializer(Serializer $serializer): void
    {
        $this->serializer = $serializer;
    }
}
