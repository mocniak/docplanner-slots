<?php

namespace App\Tests\Behat;

use App\Query\DoctorFromApi;
use App\Query\SlotFromApi;
use App\Query\SupplierAPI;

class StubSupplierAPI implements SupplierAPI
{
    /**
     * @var DoctorFromApi[]
     */
    private array $doctors;
    /**
     * @var SlotFromApi[]
     */
    private array $slots;

    public function __construct()
    {
        $this->doctors = [];
        $this->slots = [];
    }

    public function addDoctor(DoctorFromApi $doctor)
    {
        $this->doctors[$doctor->name()] = $doctor;
    }

    public function addSlot(SlotFromApi $slot, int $doctorId)
    {
        $this->slots[$doctorId][] = $slot;
    }

    /**
     * @inheritDoc
     */
    public function findAllDoctors(): array
    {
        return array_values($this->doctors);
    }

    public function getDoctorByName(string $name): DoctorFromApi
    {
        return $this->doctors[$name];
    }

    /**
     * @inheritDoc
     */
    public function findSlotsForADoctor(int $doctorId): array
    {
        return $this->slots[$doctorId];
    }
}
