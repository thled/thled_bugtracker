<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Generator;

/** @coversNothing */
final class ApplicationAvailabilityTest extends FunctionalTestBase
{
    /** @dataProvider provideAnonymousUrls */
    public function testPageIsSuccessfulAnonymous(string $url): void
    {
        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function provideAnonymousUrls(): Generator
    {
        yield ['/login'];
        yield ['/register'];
    }

    /** @dataProvider provideAdminUrls */
    public function testPageIsSuccessfulAdmin(string $url): void
    {
        $this->logInAsAdmin();

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function provideAdminUrls(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/'];
    }

    /** @dataProvider providePoUrls */
    public function testPageIsSuccessfulPo(string $url): void
    {
        $this->logInAsPo();

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function providePoUrls(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/'];
    }

    /** @dataProvider provideDevUrls */
    public function testPageIsSuccessfulDev(string $url): void
    {
        $this->logInAsDev();

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function provideDevUrls(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        yield ['/'];
    }
}
