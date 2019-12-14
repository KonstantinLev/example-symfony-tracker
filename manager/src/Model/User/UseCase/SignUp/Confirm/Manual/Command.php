<?php

namespace App\Model\User\UseCase\SignUp\Confirm\Manual;

class Command
{
    /**
     * @var string
     */
    public $id;
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}