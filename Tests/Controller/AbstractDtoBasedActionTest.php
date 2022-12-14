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

namespace StfalconStudio\ApiBundle\Tests\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use StfalconStudio\ApiBundle\Controller\AbstractDtoBasedAction;
use StfalconStudio\ApiBundle\Request\DtoExtractor;
use StfalconStudio\ApiBundle\Serializer\Serializer;
use StfalconStudio\ApiBundle\Validator\EntityValidator;
use StfalconStudio\ApiBundle\Validator\JsonSchemaValidator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class AbstractDtoBasedActionTest extends TestCase
{
    /** @var AuthorizationCheckerInterface|MockObject */
    protected AuthorizationCheckerInterface|MockObject $authorizationChecker;

    /** @var EntityValidator|MockObject */
    protected EntityValidator|MockObject $entityValidator;

    /** @var JsonSchemaValidator|MockObject */
    protected JsonSchemaValidator|MockObject $jsonSchemaValidator;

    /** @var Serializer|MockObject */
    protected Serializer|MockObject $serializer;

    /** @var DtoExtractor|MockObject */
    protected DtoExtractor|MockObject $dtoExtractor;

    /** @var EventDispatcherInterface|MockObject */
    protected EventDispatcherInterface|MockObject $eventDispatcher;

    /** @var Request|MockObject */
    protected Request|MockObject $request;

    /** @var AbstractDtoBasedAction */
    protected $action;

    protected function setUp(): void
    {
        $this->authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);
        $this->dtoExtractor = $this->createMock(DtoExtractor::class);
        $this->entityValidator = $this->createMock(EntityValidator::class);
        $this->jsonSchemaValidator = $this->createMock(JsonSchemaValidator::class);
        $this->serializer = $this->createMock(Serializer::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->action->setDtoExtractor($this->dtoExtractor);
        $this->action->setAuthorizationChecker($this->authorizationChecker);
        $this->action->setEntityValidator($this->entityValidator);
        $this->action->setJsonSchemaValidator($this->jsonSchemaValidator);
        $this->action->setSerializer($this->serializer);
        $this->action->setEventDispatcher($this->eventDispatcher);
    }

    protected function tearDown(): void
    {
        unset(
            $this->authorizationChecker,
            $this->dtoExtractor,
            $this->entityValidator,
            $this->jsonSchemaValidator,
            $this->serializer,
            $this->eventDispatcher,
            $this->request,
            $this->action,
        );
    }
}
