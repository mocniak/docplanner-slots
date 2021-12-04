<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Slot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\Column(type="string")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeImmutable $startTime;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTimeImmutable $endTime;

    /**
     * @ORM\Column(type="integer")
     */
    private $doctorId;

    public function __construct(string $id, \DateTimeImmutable $startTime, \DateTimeImmutable $endTime, int $doctorId)
    {
        $this->id = $id;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
        $this->doctorId = $doctorId;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function startTime(): \DateTimeImmutable
    {
        return $this->startTime;
    }

    public function endTime(): \DateTimeImmutable
    {
        return $this->endTime;
    }

    public function doctorId(): int
    {
        return $this->doctorId;
    }
}
