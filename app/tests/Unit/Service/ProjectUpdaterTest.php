<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\DataTransferObject\ProjectUpdateDto;
use App\Entity\Project;
use App\Service\ProjectUpdater;
use PHPUnit\Framework\TestCase;

/** @covers ProjectUpdater */
final class ProjectUpdaterTest extends TestCase
{
    public function testUpdateProjectEntityFromDto(): void
    {
        $sut = new ProjectUpdater();
        $project = new Project('FOO', 'Foo Project');
        $projectDto = new ProjectUpdateDto();
        $projectDto->name = 'New Project';

        $sut->updateFromDto($project, $projectDto);

        self::assertSame(
            'New Project',
            $project->getName(),
            'Project got wrong name after update.',
        );
    }
}
