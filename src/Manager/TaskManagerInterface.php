<?php

namespace App\Manager;

use App\Entity\Task;

interface TaskManagerInterface
{
    public function create(Task $task): void;
    public function edit(Task $task): void;
    public function toggle(Task $task): void;
    public function delete(Task $task): void;

}
