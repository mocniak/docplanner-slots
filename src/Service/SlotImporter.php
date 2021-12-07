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
        // TODO: handle errors
        $doctors = $this->doctorsFromAPI->findAllDoctors();
        foreach ($doctors as $doctor) {
            try {
                $slots = $this->doctorsFromAPI->findSlotsForADoctor($doctor->id());
                foreach ($slots as $slot) {
                    $this->slotRepository->add(new Slot(uniqid(),$slot->start(), $slot->end(),$doctor->id()));
                }
            } catch (\Exception $exception) {
                // TODO: use more specific exception
                // TODO: log error
                // TODO: decide whether to stop the whole import or continue
            }
        }
    }
}
