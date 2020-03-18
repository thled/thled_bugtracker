<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\User;
use Generator;

/** @coversNothing */
final class ApplicationAvailabilityTest extends FunctionalTestBase
{
    /** @dataProvider urlProviderAnonymous */
    public function testPageIsSuccessfulAnonymous(string $url): void
    {
        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderAnonymous(): Generator
    {
        yield ['/login'];
        yield ['/register'];
    }

    /** @dataProvider urlProviderAdmin */
    public function testPageIsSuccessfulAdmin(string $url): void
    {
        /** @var User $adminUser */
        $adminUser = $this->fixtures->getReference('user-admin');
        $this->logIn($adminUser);

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderAdmin(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/'];
    }

    /** @dataProvider urlProviderPo */
    public function testPageIsSuccessfulPo(string $url): void
    {
        /** @var User $poUser */
        $poUser = $this->fixtures->getReference('user-po0');
        $this->logIn($poUser);

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderPo(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/'];
    }

    /** @dataProvider urlProviderDev */
    public function testPageIsSuccessfulDev(string $url): void
    {
        /** @var User $devUser */
        $devUser = $this->fixtures->getReference('user-dev0');
        $this->logIn($devUser);

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderDev(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/'];
    }
}
