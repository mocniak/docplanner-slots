<?php

namespace App\Tests\Service;

use App\Entity\Slot;
use App\Service\ClosestAvailableSlotSorter;
use App\Utils\Clock;
use App\ValueObject\SlotsCollection;
use PHPUnit\Framework\TestCase;

class ClosestAvailableSlotSorterTest extends TestCase
{
    /**
     * @dataProvider slotsProvider
     */
    public function testSorterSortsSlotsByTheirDurationDescending(array $slots, array $expectedOrder)
    {
        $sorter = new ClosestAvailableSlotSorter(new class implements Clock {
            public function getCurrentTime(): \DateTimeImmutable
            {
                return new \DateTimeImmutable('2020-01-01 08:00');
            }
        });

        $slotsToSort = SlotsCollection::fromArray(
            array_map(function (array $row) {
                $startTime = new \DateTimeImmutable($row['start']);
                return new Slot($row['id'], $startTime, $startTime->add(new \DateInterval('PT1H')), 1);
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
            'latest first' => [
                [
                    ['id' => 'tomorrow', 'start' => '2020-01-02 08:00'],
                    ['id' => 'in two days', 'start' => '2020-01-03 08:00'],
                    ['id' => 'in one hour', 'start' => '2020-01-01 09:00'],
                ],
                ['in one hour', 'tomorrow', 'in two days']
            ],
            'earliest first' => [
                [
                    ['id' => 'in one hour', 'start' => '2020-01-01 09:00'],
                    ['id' => 'in two days', 'start' => '2020-01-03 08:00'],
                    ['id' => 'tomorrow', 'start' => '2020-01-02 08:00'],
                ],
                ['in one hour', 'tomorrow', 'in two days']
            ],
            'random order' => [
                [
                    ['id' => 'in one hour', 'start' => '2020-01-01 09:00'],
                    ['id' => 'in one minute', 'start' => '2020-01-01 08:01'],
                    ['id' => 'in one week', 'start' => '2020-01-08 08:00'],
                    ['id' => 'in two days', 'start' => '2020-01-03 08:00'],
                    ['id' => 'tomorrow', 'start' => '2020-01-02 08:00'],
                ],
                ['in one minute', 'in one hour', 'tomorrow', 'in two days', 'in one week']
            ],
            'one element' => [
                [
                    ['id' => 'today', 'start' => '2020-01-01 12:00'],
                ],
                ['today']
            ],
            'empty set' => [
                [],
                []
            ]
        ];
    }
}
