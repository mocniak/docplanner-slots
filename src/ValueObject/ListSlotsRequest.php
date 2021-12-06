<?php
declare(strict_types=1);

namespace App\ValueObject;

final class ListSlotsRequest
{
    private string $sortType;
    private ?\DateTimeImmutable $dateFrom;
    private ?\DateTimeImmutable $dateTo;

    public function __construct(string $sortType, ?\DateTimeImmutable $dateFrom, ?\DateTimeImmutable $dateTo)
    {
        $this->sortType = $sortType;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function getSortType(): string
    {
        return $this->sortType;
    }

    public function getDateFrom(): ?\DateTimeImmutable
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ?\DateTimeImmutable
    {
        return $this->dateTo;
    }
}
