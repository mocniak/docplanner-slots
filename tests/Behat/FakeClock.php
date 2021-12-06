<?php

namespace App\Tests\Behat;

use App\Utils\Clock;

class FakeClock implements Clock
{
    private ?\DateTimeImmutable $currentTime;

    public function __construct()
    {
        $this->currentTime = null;
    }

    public function getCurrentTime(): \DateTimeImmutable
    {
        return $this->currentTime;
    }

    public function setCurrentTime(\DateTimeImmutable $currentTime): void
    {
        $this->currentTime = $currentTime;
    }
}
