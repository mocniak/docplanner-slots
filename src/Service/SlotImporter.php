<?php

namespace App\Service;

use App\Entity\Slot;
use App\Query\SupplierAPI;
use App\Repository\SlotRepository;

class SlotImporter
{
    private SupplierAPI $doctorsFromAPI;
    private SlotRepository $slotRepository;

    public function __construct(SupplierAPI $doctorsFromAPI, SlotRepository $slotRepository)
    {
        $this->doctorsFromAPI = $doctorsFromAPI;
        $this->slotRepository = $slotRepository;
    }

    public function importAll(): void
    {
        $doctors = $this->doctorsFromAPI->findAllDoctors();
        $doctors = array_slice($doctors,0, 2);
        foreach ($doctors as $doctor) {
            $slots = $this->doctorsFromAPI->findSlotsForADoctor($doctor->id());
            $slots = array_slice($slots, 0, 2);
            foreach ($slots as $slot) {
                $this->slotRepository->add(new Slot(uniqid(),$slot->start(), $slot->end(),$doctor->id()));
            }
        }
    }
}
