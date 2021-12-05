<?php

namespace App\Utils;

interface Clock
{
    public function getCurrentTime(): \DateTimeImmutable;
}
