<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\Entity\Project;
use App\Factory\ProjectDtoFactory;
use PHPUnit\Framework\TestCase;

/** @covers ProjectDtoFactory */
final class ProjectDtoFactoryTest extends TestCase
{
    public function testTransformProjectEntityToDto(): void
    {
        $sut = new ProjectDtoFactory();
        $project = new Project('FOO', 'Foo Project');

        $projectDto = $sut->createUpdate($project);

        self::assertSame(
            'Foo Project',
            $projectDto->name,
            'ProjectDto got wrong name',
        );
    }
}
