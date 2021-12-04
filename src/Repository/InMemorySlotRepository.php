<?php

namespace App\Repository;

use App\Entity\Slot;

class InMemorySlotRepository implements SlotRepository
{
    /**
     * @var Slot[]
     */
    private array $slots;

    public function __construct()
    {
        $this->slots = [];
    }

    public function add(Slot $slot): void
    {
        $this->slots[] = $slot;
    }

    /**
     * @return Slot[]
     */
    public function findForDoctor(int $doctorId):array
    {
        return $this->slots;
    }
}
