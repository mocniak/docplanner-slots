<?php

namespace App\Service;

use App\Entity\Slot;
use App\ValueObject\SlotsCollection;

class ByDurationSlotSorter implements SlotsSorter
{
    public function sort(SlotsCollection $slotsCollection): SlotsCollection
    {
        $clonedSlots = clone($slotsCollection); //we don't want to change the collection that is passed
        $slotsToSort = $clonedSlots->getSlots();
        usort($slotsToSort, function (Slot $slot1, Slot $slot2) {
            // apparently DateInterval is not comparable in PHP so let's assume that unix timestamp is good enough
            $durationOfSlot1InSeconds = $slot1->endTime()->getTimestamp() - $slot1->startTime()->getTimestamp();
            $durationOfSlot2InSeconds = $slot2->endTime()->getTimestamp() - $slot2->startTime()->getTimestamp();
            return -1 * ($durationOfSlot1InSeconds <=> $durationOfSlot2InSeconds);
        });

        return SlotsCollection::fromArray($slotsToSort);
    }

    public function supports(string $sortType): bool
    {
        return $sortType === SlotsSorter::TYPE_DURATION;
    }
}
