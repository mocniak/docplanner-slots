<?php

namespace App\Repository;

use App\Entity\Slot;
use App\ValueObject\SlotsCollection;

class InMemorySlotRepository implements SlotRepository
{
    private SlotsCollection $slots;

    public function __construct()
    {
        $this->slots = new SlotsCollection();
    }

    public function add(Slot $slot): void
    {
        $this->slots->addSlot($slot);
    }

    public function findAll(): SlotsCollection
    {
        return $this->slots;
    }
}
