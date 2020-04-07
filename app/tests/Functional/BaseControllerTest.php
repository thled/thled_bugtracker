<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Controller\BaseController;
use App\Entity\User;
use App\Tests\InterfaceForAbstractClass\BaseControllerInterface;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

/** @covers \App\Controller\BaseController */
final class BaseControllerTest extends FunctionalTestBase
{
    private BaseControllerInterface $baseControllerExt;
    private ObjectProphecy $containerMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->baseControllerExt = $this->createClassWhichExtendsBaseController();
    }

    private function createClassWhichExtendsBaseController(): BaseControllerInterface
    {
        $this->baseControllerExt = new class extends BaseController implements
            BaseControllerInterface
        {
            public function getUser(): User
            {
                return parent::getUser();
            }
        };

        return $this->baseControllerExt;
    }

    public function testGetUser(): void
    {
        /** @var User $user */
        $user = $this->fixtures->getReference('user-admin');
        $this->mockContainerOfBaseControllerExtToLogInAsUser($user);

        $user = $this->baseControllerExt->getUser();

        self::assertSame('admin@example.com', $user->getUsername());
    }

    private function mockContainerOfBaseControllerExtToLogInAsUser(?User $user): void
    {
        $this->containerMock = $this->prophesize(ContainerInterface::class);
        $this->baseControllerExt->setContainer($this->containerMock->reveal());

        $this->containerMock->has('security.token_storage')->willReturn(true);

        $tokenStorage = $this->prophesize(TokenStorageInterface::class);
        $this->containerMock->get('security.token_storage')->willReturn($tokenStorage);

        $token = $this->prophesize(TokenInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn($user);
    }

    public function testGetUserThrowsUnsupportedUserException(): void
    {
        $this->mockContainerOfBaseControllerExtToLogInAsUser(null);

        $this->expectException(UnsupportedUserException::class);

        $this->baseControllerExt->getUser();
    }
}
