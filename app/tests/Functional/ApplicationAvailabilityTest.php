<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Generator;

class ApplicationAvailabilityTest extends FunctionalTestBase
{
    /** @dataProvider urlProvider */
    public function testPageIsSuccessful(string $url): void
    {
        $this->client->request('GET', $url);

        self::assertTrue($this->client->getResponse()->isSuccessful());
    }

    /** @return Generator<array<string>> */
    public function urlProvider(): Generator
    {
        yield ['/'];
        yield ['/login'];
        yield ['/register'];
    }
}
