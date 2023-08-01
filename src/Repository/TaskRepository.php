<?php

namespace App\Repository;

use App\Entity\Task;
use App\Manager\TaskManagerInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository implements TaskManagerInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
    public function create(Task $task): void
    {
        $this->_em->persist($task);
        $this->_em->flush();
    }

    public function edit(Task $task): void
    {
        $this->_em->flush();
    }

    public function delete(Task $task): void
    {
        $this->_em->remove($task);
        $this->_em->flush();
    }

    public function toggle(Task $task): void
    {
        $task->toggle(!$task->isDone());
        $this->_em->flush();
    }
}
