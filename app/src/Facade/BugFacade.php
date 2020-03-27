<?php

declare(strict_types=1);

namespace App\Facade;

use App\DataTransferObject\BugCreateDto;
use App\DataTransferObject\BugUpdateDto;
use App\Entity\Bug;
use App\Factory\BugDtoFactoryInterface;
use App\Factory\BugFactoryInterface;
use App\Repository\BugRepositoryInterface;
use App\Service\BugUpdaterInterface;

final class BugFacade implements BugFacadeInterface
{
    private BugRepositoryInterface $bugRepo;
    private BugDtoFactoryInterface $bugDtoFactory;
    private BugFactoryInterface $bugFactory;
    private BugUpdaterInterface $bugUpdater;

    public function __construct(
        BugRepositoryInterface $bugRepo,
        BugDtoFactoryInterface $bugDtoFactory,
        BugFactoryInterface $bugFactory,
        BugUpdaterInterface $bugUpdater
    ) {
        $this->bugRepo = $bugRepo;
        $this->bugDtoFactory = $bugDtoFactory;
        $this->bugFactory = $bugFactory;
        $this->bugUpdater = $bugUpdater;
    }

    public function mapBugToUpdateDto(Bug $bug): BugUpdateDto
    {
        return $this->bugDtoFactory->createUpdate($bug);
    }

    public function saveBugFromDto(BugCreateDto $bugDto): void
    {
        $bug = $this->bugFactory->createFromDto($bugDto);

        $this->bugRepo->save($bug);
    }

    public function updateBugFromDto(Bug $bug, BugUpdateDto $bugDto): void
    {
        $this->bugUpdater->updateFromDto($bug, $bugDto);

        $this->bugRepo->save($bug);
    }
}
