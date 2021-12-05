<?php
declare(strict_types=1);

namespace App\ValueObject;

use App\Entity\Slot;

final class SlotsCollection
{
    /** @var Slot[] */
    private array $slots;

    public function addSlot(Slot $slot): void
    {
        $this->slots[] = $slot;
    }

    /**
     * @param Slot[] $slots
     */
    public static function fromArray(array $slots): self {
        $newSlots = new self();
        $newSlots->slots = $slots;
        return $newSlots;
    }

    public function getSlots(): array
    {
        return $this->slots;
    }
}
