<?php

namespace App\Supplier;

interface SupplierAPI
{
    /**
     * @return DoctorFromApi[]
     */
    public function findAllDoctors(): array;

    /**
     * @return SlotFromApi[]
     */
    public function findSlotsForADoctor(int $doctorId): array;
}
