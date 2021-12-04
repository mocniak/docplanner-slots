<?php

namespace App\Repository;

use App\Entity\Slot;

interface SlotRepository
{
    public function add(Slot $slot): void;

    public function findForDoctor(int $doctorId);
}
