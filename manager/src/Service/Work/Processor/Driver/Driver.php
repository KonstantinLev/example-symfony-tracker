<?php

namespace App\Service\Work\Processor\Driver;

interface Driver
{
    public function process(string $text): string;
}