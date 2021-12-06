<?php

namespace App\Service;

use App\ValueObject\SlotsCollection;

interface SlotsFilter
{
    public const TYPE_LATER_THAN = 'later_than';
    public const TYPE_EARLIER_THAN = 'earlier_than';

    public function filter(SlotsCollection $slotsCollection): SlotsCollection;

    public function supports(string $filterType): bool;
}
