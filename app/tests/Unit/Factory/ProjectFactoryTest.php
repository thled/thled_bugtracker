<?php

declare(strict_types=1);

namespace App\Tests\Unit\Factory;

use App\DataTransferObject\ProjectCreateDto;
use App\Factory\ProjectFactory;
use PHPUnit\Framework\TestCase;

/** @covers ProjectFactory */
final class ProjectFactoryTest extends TestCase
{
    public function testTransformProjectDtoToEntity(): void
    {
        $sut = new ProjectFactory();
        $projectDto = new ProjectCreateDto();
        $projectDto->projectId = 'FOO';
        $projectDto->name = 'Foo Project';

        $project = $sut->createFromDto($projectDto);

        self::assertSame(
            'FOO',
            $project->getProjectId(),
            'Project got wrong ID',
        );
        self::assertSame(
            'Foo Project',
            $project->getName(),
            'Project got wrong name',
        );
    }
}
