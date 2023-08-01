<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Task;
use App\Repository\TaskRepository;

class TaskRepositoryTest extends KernelTestCase
{
    private TaskRepository $taskRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->taskRepository = $container->get(TaskRepository::class);
    }

    public function testCreateTask()
    {
        $task = new Task();
        $task->setTitle('Title 1');
        $task->setContent('Description 1');

        $this->taskRepository->create($task);

        // Retrieve the task from the repository to verify it was persisted
        $persistedTask = $this->taskRepository->find($task->getId());

        $this->assertSame($task, $persistedTask);
    }
    public function testEditTask()
    {
        // Create a task
        $task = new Task();
        $task->setTitle('Title 1');
        $task->setContent('Description 1');
        $this->taskRepository->create($task);

        // Modify the task's title
        $task->setTitle('Updated Title 1');
        $this->taskRepository->edit($task);

        // Retrieve the task from the repository to verify the update
        $updatedTask = $this->taskRepository->find($task->getId());

        $this->assertSame('Updated Title 1', $updatedTask->getTitle());
    }
    public function testDeleteTask()
    {
        // Create a task
        $task = new Task();
        $task->setTitle('Title 1');
        $task->setContent('Description 1');
        $this->taskRepository->create($task);

        // Récupérer l'identifiant de la tâche
        $taskId = $task->getId();

        // Delete the task
        $this->taskRepository->delete($task);

        // Attempt to retrieve the task from the repository should return null
        $deletedTask = $this->taskRepository->find($taskId);

        $this->assertNull($deletedTask);
    }
    public function testToggleTask()
    {
        // Create a task
        $task = new Task();
        $task->setTitle('Title 1');
        $task->setContent('Description 1');
        $this->taskRepository->create($task);

        // Initially, the task should not be marked as done
        $this->assertFalse($task->isDone());

        // Toggle the task's status
        $this->taskRepository->toggle($task);

        // Retrieve the task from the repository to verify the toggle
        $toggledTask = $this->taskRepository->find($task->getId());

        // Now the task should be marked as done
        $this->assertTrue($toggledTask->isDone());

        // Toggle again to mark the task as not done
        $this->taskRepository->toggle($toggledTask);

        // Retrieve the task from the repository to verify the toggle
        $untoggledTask = $this->taskRepository->find($toggledTask->getId());

        // Now the task should be marked as not done again
        $this->assertFalse($untoggledTask->isDone());
    }

}
