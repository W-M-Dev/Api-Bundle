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

namespace StfalconStudio\ApiBundle\EventListener\JWT;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use StfalconStudio\ApiBundle\Error\BaseErrorNames;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * JwtSubscriber.
 */
final class JwtSubscriber implements EventSubscriberInterface
{
    private const ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION = 'onAuthenticationFailureResponse';

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): iterable
    {
        yield AuthenticationFailureEvent::class => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield JWTInvalidEvent::class => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield JWTNotFoundEvent::class => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield JWTExpiredEvent::class => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield Events::AUTHENTICATION_FAILURE => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield Events::JWT_INVALID => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield Events::JWT_NOT_FOUND => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
        yield Events::JWT_EXPIRED => self::ON_AUTHENTICATION_FAILURE_RESPONSE_FUNCTION;
    }

    /**
     * @param AuthenticationFailureEvent $event
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        switch (true) {
            case $event instanceof JWTInvalidEvent:
                $message = 'invalid_jwt_token_message';
                break;
            case $event instanceof JWTNotFoundEvent:
                $message = 'not_found_jwt_token_message';
                break;
            case $event instanceof JWTExpiredEvent:
                $message = 'expired_jwt_token_message';
                break;
            default:
                $message = 'unauthorised_user_message';
        }

        $data = [
            'error' => BaseErrorNames::UNAUTHORISED_USER,
            'errorDescription' => $this->translator->trans($message),
        ];

        $event->setResponse(new JsonResponse($data, JsonResponse::HTTP_UNAUTHORIZED));
    }
}
