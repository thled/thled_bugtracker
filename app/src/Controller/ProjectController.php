<?php

declare(strict_types=1);

namespace App\Controller;

use App\DataTransferObject\ProjectCreateDto;
use App\Entity\Project;
use App\Facade\ProjectFacadeInterface;
use App\Form\ProjectCreateType;
use App\Form\ProjectEditType;
use App\Repository\ProjectRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/** @Route("/project/", name="project_") */
final class ProjectController extends BaseController
{
    /** @Route("list", name="list") */
    public function list(ProjectRepositoryInterface $projectRepo): Response
    {
        $projects = $projectRepo->findAll();

        return $this->render(
            'project/list.html.twig',
            ['projects' => $projects],
        );
    }

    /** @Route("add", name="add") */
    public function add(
        Request $request,
        ProjectFacadeInterface $projectFacade
    ): Response {
        $projectDto = new ProjectCreateDto();
        $form = $this->createForm(ProjectCreateType::class, $projectDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectFacade->saveProjectFromDto($projectDto);

            return $this->redirectToRoute('project_list');
        }

        return $this->render(
            'project/add.html.twig',
            ['form' => $form->createView()],
        );
    }

    /** @Route("edit/{project}", name="edit") */
    public function edit(
        Project $project,
        Request $request,
        ProjectFacadeInterface $projectFacade
    ): Response {
        $projectDto = $projectFacade->mapProjectToUpdateDto($project);
        $form = $this->createForm(ProjectEditType::class, $projectDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $projectFacade->updateProjectFromDto($project, $projectDto);

            return $this->redirectToRoute('project_list');
        }

        return $this->render(
            'project/edit.html.twig',
            ['form' => $form->createView()],
        );
    }
}
