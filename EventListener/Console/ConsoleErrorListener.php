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

namespace StfalconStudio\ApiBundle\EventListener\Console;

use StfalconStudio\ApiBundle\Exception\Console\CustomConsoleExceptionInterface;
use Symfony\Component\Console\Event\ConsoleErrorEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * ConsoleErrorListener.
 */
final class ConsoleErrorListener implements EventSubscriberInterface
{
    /**
     * @return iterable
     */
    public static function getSubscribedEvents(): iterable
    {
        yield ConsoleErrorEvent::class => 'onConsoleError';
    }

    /**
     * @param ConsoleErrorEvent $event
     *
     * @return void
     */
    public function onConsoleError(ConsoleErrorEvent $event): void
    {
        if ($event->getError() instanceof CustomConsoleExceptionInterface) {
            $event->setExitCode(0);
            $event->stopPropagation();
        }
    }
}
