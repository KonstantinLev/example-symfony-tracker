<?php

namespace App\Model\User\UseCase\SignUp\Confirm\ByToken;

class Command
{
    /**
     * @var string
     */
    public $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }
}