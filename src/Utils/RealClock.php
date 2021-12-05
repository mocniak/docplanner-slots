<?php

namespace App\Utils;

class RealClock implements Clock
{
    public function getCurrentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
