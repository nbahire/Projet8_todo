<?php

namespace App\Tests\UnitTests;

use App\Entity\Task;
use App\Entity\User;
use App\Tests\Traits\ConstraintTrait;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    use ConstraintTrait;
    private Task $task;

    public function setUp(): void
    {
        $this->task = new Task();
    }
    public function testId(): void
    {
        $this->assertNull($this->task->getId());
    }

    public function testTitle(): void
    {
        $this->task->setTitle('test');
        $this->assertSame('test', $this->task->getTitle());

    }
    public function testContent(): void
    {
        $this->task->setContent('lorem');
        $this->assertSame('lorem', $this->task->getContent());

    }

    public function testisDone(): void
    {
        $this->task->setIsDone(true);
        $this->assertSame(true, $this->task->isDone());
    }

    public function testCreatedAt()
    {
        $date = new \DateTimeImmutable();
        $this->task->setCreatedAt($date);
        $this->assertSame($date, $this->task->getCreatedAt());
    }

    public function testUser()
    {
        $this->task->setUser(new User());
        $this->assertInstanceOf(User::class, $this->task->getUser());
    }

}
