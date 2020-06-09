<?php

declare(strict_types=1);

namespace App\Model\Work\UseCase\Projects\Task\Create;

use App\Model\Flusher;
use App\Model\Work\Entity\Members\Member\Id as MemberId;
use App\Model\Work\Entity\Members\Member\MemberRepository;
use App\Model\Work\Entity\Projects\Project\Id as ProjectId;
use App\Model\Work\Entity\Projects\Project\ProjectRepository;
use App\Model\Work\Entity\Projects\Task\Task;
use App\Model\Work\Entity\Projects\Task\Id;
use App\Model\Work\Entity\Projects\Task\TaskRepository;
use App\Model\Work\Entity\Projects\Task\Type;

class Handler
{
    private $members;
    private $projects;
    private $tasks;
    private $flusher;

    public function __construct(MemberRepository $members, ProjectRepository $projects, TaskRepository $tasks, Flusher $flusher)
    {
        $this->members = $members;
        $this->projects = $projects;
        $this->tasks = $tasks;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $member = $this->members->get(new MemberId($command->member));
        $project = $this->projects->get(new ProjectId($command->project));

//        $task = new Task(
//            $this->tasks->nextId(),
//            $project,
//            $member,
//            new \DateTimeImmutable(),
//            new Type($command->type),
//            $command->priority,
//            $command->name,
//            $command->content
//        );
//
//        if ($command->parent) {
//            $parent = $this->tasks->get(new Id($command->parent));
//            $task->setChildOf($parent);
//        }

        $parent = $command->parent ? $this->tasks->get(new Id($command->parent)) : null;

//        if ($command->plan) {
//            $task->plan($command->plan);
//        }

        $date = new \DateTimeImmutable();

        $tasks = [];

        foreach ($command->names as $name) {
            $task = new Task(
                $this->tasks->nextId(),
                $project,
                $member,
                $date,
                new Type($command->type),
                $command->priority,
                $name->name,
                $command->content
            );

            if ($parent) {
                //$task->setChildOf($parent);
                $task->setChildOf($member, $date, $parent);
            }
            if ($command->plan) {
                //$task->plan($command->plan);
                $task->plan($member, $date, $command->plan);
            }

            $date = $date->modify('+1 sec');

            $this->tasks->add($task);

            $tasks[] = $task;
        }

        //$this->tasks->add($task);

        $this->flusher->flush(...$tasks);
    }
}