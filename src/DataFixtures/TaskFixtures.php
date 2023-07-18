<?php

namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = new Factory();
        $faker = $faker::create('fr_FR');

        for ($i = 0; $i <= 10; $i++) {
            $task = new Task();
            $task->setContent($faker->paragraphs(4, true));
            $task->setTitle($faker->words(3, true));
            $manager->persist($task);
        }

        $manager->flush();
    }
}
