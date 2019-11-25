<?php

namespace App\Model\User\UseCase\SignUp\Request;

class Command
{
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
}