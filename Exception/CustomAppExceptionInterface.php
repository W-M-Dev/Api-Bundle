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

namespace StfalconStudio\ApiBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * CustomAppExceptionInterface.
 */
interface CustomAppExceptionInterface extends HttpExceptionInterface
{
    /**
     * @return bool
     */
    public function loggable(): bool;

    /**
     * @return string
     */
    public function getErrorName(): string;
}
