<?php
declare(strict_types=1);

namespace App\Service;

use App\ValueObject\SlotsCollection;

interface SlotsSorter
{
    public const TYPE_DURATION = 'duration';
    public const TYPE_CLOSEST_AVAILABLE = 'closest_available';
    public const TYPE_LAZY = 'lazy';
    public function sort(SlotsCollection $slotsCollection): SlotsCollection;
    public function supports(string $sortType): bool;
}
