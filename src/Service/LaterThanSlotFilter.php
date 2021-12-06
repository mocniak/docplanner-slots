<?php

namespace App\Service;

use App\Entity\Slot;
use App\ValueObject\SlotsCollection;

class LaterThanSlotFilter implements SlotsFilter
{
    private \DateTimeImmutable $dateFrom;

    public function __construct(\DateTimeImmutable $dateFrom)
    {
        $this->dateFrom = $dateFrom;
    }

    public function filter(SlotsCollection $slotsCollection): SlotsCollection
    {
        $dateFrom = $this->dateFrom;
        $filteredSlots = array_filter($slotsCollection->getSlots(), function (Slot $slot) use ($dateFrom) {
            return $slot->startTime() >= $dateFrom;
        });
        return SlotsCollection::fromArray($filteredSlots);
    }

    public function supports(string $filterType): bool
    {
        return true;
    }
}
