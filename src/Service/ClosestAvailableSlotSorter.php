<?php

namespace App\Service;

use App\Entity\Slot;
use App\Utils\Clock;
use App\ValueObject\SlotsCollection;

// TODO: instead sorting array of objects in PHP use the DB/search engine mechanisms and specification pattern
class ClosestAvailableSlotSorter implements SlotsSorter
{
    private Clock $clock;

    public function __construct(Clock $clock)
    {
        $this->clock = $clock;
    }

    public function sort(SlotsCollection $slotsCollection): SlotsCollection
    {
        $clonedSlots = clone($slotsCollection); //we don't want to change the collection that is passed
        $slotsToSort = $clonedSlots->getSlots();
        $now = $this->clock->getCurrentTime();
        usort($slotsToSort, function (Slot $slot1, Slot $slot2) use ($now) {
            $secondsToSlot1 = $slot1->startTime()->getTimestamp() - $now->getTimestamp();
            $secondsToSlot2 = $slot2->startTime()->getTimestamp() - $now->getTimestamp();
            return $secondsToSlot1 <=> $secondsToSlot2;
        });

        return SlotsCollection::fromArray($slotsToSort);
    }

    public function supports(string $sortType): bool
    {
        return $sortType === SlotsSorter::TYPE_CLOSEST_AVAILABLE;
    }
}
