<?php

namespace App\Service;

use App\Repository\SlotRepository;
use App\Utils\Clock;
use App\ValueObject\ListSlotsRequest;
use App\ValueObject\SlotsCollection;

class SlotListQuery
{
    private SlotRepository $repository;
    private Clock $clock;

    public function __construct(SlotRepository $repository, Clock $clock)
    {
        $this->repository = $repository;
        $this->clock = $clock;
    }

    public function find(ListSlotsRequest $request): SlotsCollection
    {
        $slots = $this->repository->findAll();
        $filters = [];
        if ($request->getDateFrom() !== null) {
            $filters[] = new LaterThanSlotFilter($request->getDateFrom());
        }
        if ($request->getDateTo() !== null) {
            $filters[] = new EarlierThanSlotFilter($request->getDateTo());
        }

        foreach ($filters as $filter) {
            $slots = $filter->filter($slots);
        }

        if ($request->getSortType() === SlotsSorter::TYPE_CLOSEST_AVAILABLE) {
            $sorter = new ClosestAvailableSlotSorter($this->clock);
        } elseif ($request->getSortType() === SlotsSorter::TYPE_DURATION) {
            $sorter = new ByDurationSlotSorter();
        } else {
            return $slots;
        }

        return $sorter->sort($slots);
    }
}
