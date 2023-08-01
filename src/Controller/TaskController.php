<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Manager\TaskManagerInterface;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function taskListAction(TaskRepository $taskRepository): Response
    {
        return $this->render('task/list.html.twig', ['tasks' => $taskRepository->findBy(['user' => $this->getUser()]),
            'anonymousTasks' => $taskRepository->findBy(['user' => null])]);
    }

    #[Route('/tasks/create', name: 'task_create', methods: ['GET','POST'])]
    public function taskCreateAction(Request $request, TaskManagerInterface $taskManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskManager->create($task);

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form]);
    }

    #[Route('/tasks/{id}/edit', name: 'task_edit', methods: ['GET','POST','PUT'])]
    #[IsGranted('TASK_EDIT', subject: 'task')]
    public function taskEditAction(Task $task, Request $request, TaskManagerInterface $taskManager): RedirectResponse|Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $taskManager->edit($task);
            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    #[Route('/tasks/done', name: 'task_list_done', methods: ['GET','POST'])]
    public function listDone(TaskRepository $taskRepository): Response
    {
        return $this->render('task/is_done.html.twig', ['tasks' => $taskRepository->findBy(['isDone' => true])]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: ['GET','POST'])]
    public function toggleTaskAction(Task $task, TaskManagerInterface $taskManager): RedirectResponse
    {
        $taskManager->toggle($task);

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['GET','POST'])]
    #[IsGranted('TASK_DELETE', subject: 'task')]
    public function deleteTaskAction(Task $task, TaskManagerInterface $taskManager): RedirectResponse
    {
        $taskManager->delete($task);

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        return $this->redirectToRoute('task_list');
    }
}
