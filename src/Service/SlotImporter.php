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
        foreach ($doctors as $doctor) {
            $slots = $this->doctorsFromAPI->findSlotsForADoctor($doctor->id());
            foreach ($slots as $slot) {
                $this->slotRepository->add(new Slot(uniqid(),$slot->start(), $slot->end(),$doctor->id()));
            }
        }
    }
}
