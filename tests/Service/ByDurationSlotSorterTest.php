<?php

namespace App\Tests\Service;

use App\Entity\Slot;
use App\Service\ByDurationSlotSorter;
use App\ValueObject\SlotsCollection;
use PHPUnit\Framework\TestCase;

class ByDurationSlotSorterTest extends TestCase
{
    /**
     * @dataProvider slotsProvider
     */
    public function testSorterSortsSlotsByTheirDurationDescending(array $slots, array $expectedOrder)
    {
        $sorter = new ByDurationSlotSorter();
        $slotsToSort = SlotsCollection::fromArray(
            array_map(function (array $row) {
                return new Slot($row['id'], new \DateTimeImmutable($row['start']), new \DateTimeImmutable($row['end']), 1);
            }, $slots)
        );
        $this->assertEquals(
            $expectedOrder,
            array_map(
                fn(Slot $slot) => $slot->id(),
                $sorter->sort($slotsToSort)->getSlots()
            )
        );
    }

    public function slotsProvider(): array
    {
        return [
            'ascending duration' => [
                [
                    ['id' => '0min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:10'],
                    ['id' => '30min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:30'],
                    ['id' => '45min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:45'],
                    ['id' => '1hr', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 13:00'],
                ],
                ['1hr', '45min', '30min', '0min',]
            ],
            'descending duration' => [
                [
                    ['id' => '1hr', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 13:00'],
                    ['id' => '45min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:45'],
                    ['id' => '30min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:30'],
                    ['id' => '0min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:10'],
                ],
                ['1hr', '45min', '30min', '0min',]
            ],
            'random order of duration' => [
                [
                    ['id' => '30min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:30'],
                    ['id' => '45min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:45'],
                    ['id' => '0min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:10'],
                    ['id' => '1hr', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 13:00'],
                ],
                ['1hr', '45min', '30min', '0min',]
            ],
            'random order of duration with different start time' => [
                [
                    ['id' => '30min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:30'],
                    ['id' => '45min', 'start' => '2020-01-02 11:00', 'end' => '2020-01-02 11:45'],
                    ['id' => '0min', 'start' => '2020-01-03 10:00', 'end' => '2020-01-03 10:10'],
                    ['id' => '1hr', 'start' => '2020-01-04 09:00', 'end' => '2020-01-04 10:00'],
                ],
                ['1hr', '45min', '30min', '0min',]
            ],
            'one element' => [
                [
                    ['id' => '30min', 'start' => '2020-01-01 12:00', 'end' => '2020-01-01 12:30'],
                ],
                ['30min']
            ],
            'empty set' => [
                [],
                []
            ]
        ];
    }
}
