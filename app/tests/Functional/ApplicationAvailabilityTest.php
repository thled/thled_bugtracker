<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Generator;

class ApplicationAvailabilityTest extends FunctionalTestBase
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
        // todo: login as admin

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
        // todo: login as po

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
        // todo: login as dev

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
