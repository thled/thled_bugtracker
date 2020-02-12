<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Generator;

/** @coversNothing */
final class ApplicationAvailabilityTest extends FunctionalTestBase
{
    private const ADMIN = 'admin@example.com';
    private const DEV = 'dev0@example.com';
    private const PO = 'po0@example.com';

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
        $this->logIn(self::ADMIN);

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderAdmin(): Generator
    {
        yield ['/login'];
        yield ['/register'];
        
//        yield ['/'];
    }

    /** @dataProvider urlProviderPo */
    public function testPageIsSuccessfulPo(string $url): void
    {
        $this->logIn(self::PO);

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderPo(): Generator
    {
        yield ['/login'];
        yield ['/register'];

//        yield ['/'];
    }

    /** @dataProvider urlProviderDev */
    public function testPageIsSuccessfulDev(string $url): void
    {
        $this->logIn(self::DEV);

        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProviderDev(): Generator
    {
        yield ['/login'];
        yield ['/register'];

//        yield ['/'];
    }
}
