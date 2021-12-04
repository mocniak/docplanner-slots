<?php

namespace App\Query;

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
