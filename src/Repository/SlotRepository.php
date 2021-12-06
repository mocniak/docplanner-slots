<?php

namespace App\Repository;

use App\Entity\Slot;
use App\ValueObject\SlotsCollection;

interface SlotRepository
{
    public function add(Slot $slot): void;

    public function findForDoctor(int $doctorId);

    public function findAll(): SlotsCollection;
}
