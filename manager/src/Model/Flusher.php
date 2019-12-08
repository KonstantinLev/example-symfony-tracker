<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;

class Flusher
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function flush(): void
    {
        $this->em->flush();
    }
}