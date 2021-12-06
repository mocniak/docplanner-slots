<?php

namespace App\Service;

use App\Repository\SlotRepository;
use App\ValueObject\ListSlotsRequest;
use App\ValueObject\SlotsCollection;

class SlotListQuery
{
    private SlotRepository $repository;

    public function __construct(SlotRepository $repository)
    {
        $this->repository = $repository;
    }

    public function find(ListSlotsRequest $request): SlotsCollection {
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

        return $slots;
    }
}
