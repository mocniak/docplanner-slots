<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Slot;
use App\Kernel;
use App\Query\DoctorFromApi;
use App\Query\SlotFromApi;
use App\Repository\SlotRepository;
use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

// TODO: split to smaller contexts
// TODO: remove code duplications
final class FeatureContext implements Context
{
    private int $doctorCounter;
    private array $doctors;
    private SlotRepository $slotRepository;
    private Application $application;
    private Kernel $kernel;
    private ?Response $response;
    private FakeClock $fakeClock;
    private StubSupplierAPI $supplierAPI;


    public function __construct(
        SlotRepository $slotRepository,
        Kernel $kernel,
        FakeClock $fakeClock,
        StubSupplierAPI $supplierAPI
    )
    {
        $this->application = new Application($kernel);
        $this->slotRepository = $slotRepository;
        $this->doctorCounter = 0;
        $this->doctors = [];
        $this->kernel = $kernel;
        $this->response = null;
        $this->fakeClock = $fakeClock;
        $this->supplierAPI = $supplierAPI;
    }

    /**
     * @Given today is :day
     */
    public function todayIs(string $day)
    {
        $this->fakeClock->setCurrentTime(new \DateTimeImmutable($day));
    }

    /**
     * @Given in the supplier API there is a doctor :doctorName
     */
    public function inTheSupplierApiThereIsADoctor(string $doctorName)
    {
        $this->supplierAPI->addDoctor(new DoctorFromApi($this->doctorCounter, $doctorName));
        $this->doctorCounter++;
    }

    /**
     * @Given the API says that doctor :doctorName has a slot on :day from :startTime to :endTime
     */
    public function theApiSaysThatDoctorHasASlotOnFromTo(string $doctorName, string $day, string $startTime, string $endTime)
    {
        $this->supplierAPI->addSlot(
            new SlotFromApi(
                new \DateTimeImmutable($day . 'T' . $startTime . ':00'),
                new \DateTimeImmutable($day . 'T' . $endTime . ':00'),
            ),
            $this->supplierAPI->getDoctorByName($doctorName)->id()
        );
    }

    /**
     * @When I import slots from the supplier
     */
    public function iImportSlotsFromTheSupplier()
    {
        $command = 'app:import-slots';
        $input = new ArgvInput(['behat-test', $command, '--env=test']);
        $this->application->doRun($input, new BufferedOutput());
    }

    /**
     * @When I see that doctor :doctorName has a slot on :day from :startTime to :endTime
     */
    public function iSeeThatDoctorHasASlotOnFromTo(string $doctorName, string $day, string $startTime, string $endTime)
    {
        $doctorId = $this->supplierAPI->getDoctorByName($doctorName)->id();
        $expectedStart = new \DateTimeImmutable($day . 'T' . $startTime . ':00');
        $expectedEnd = new \DateTimeImmutable($day . 'T' . $endTime . ':00');
        Assert::eq(
            count(array_filter(
                $this->slotRepository->findAll()->getSlots(),
                function (Slot $slot) use ($expectedStart, $expectedEnd, $doctorId) {
                    return $slot->startTime() == $expectedStart
                        && $slot->endTime() == $expectedEnd
                        && $slot->doctorId() === $doctorId;
                }
            )),
            1
        );
    }

    /**
     * @Given doctor :doctorName has a slot on :day
     */
    public function doctorHasASlotOnFrom(string $doctorName, string $day)
    {
        if (!key_exists($doctorName, $this->doctors)) {
            $this->doctors[$doctorName] = ['id' => $this->doctorCounter, 'name' => $doctorName];
            $this->doctorCounter++;
        }
        $doctorId = $this->doctors[$doctorName]['id'];

        $this->slotRepository->add(new Slot(
            $doctorName,
            new \DateTimeImmutable($day . 'T08:00:00'),
            new \DateTimeImmutable($day . 'T09:00:00'),
            $doctorId
        ));
    }

    /**
     * @Given doctor :doctorName has a :slotDuration minute slot on :day
     */
    public function doctorHasANMinuteSlot(string $doctorName, string $slotDuration, string $day)
    {
        if (!key_exists($doctorName, $this->doctors)) {
            $this->doctors[$doctorName] = ['id' => $this->doctorCounter, 'name' => $doctorName];
            $this->doctorCounter++;
        }
        $doctorId = $this->doctors[$doctorName]['id'];

        $this->slotRepository->add(new Slot(
            $doctorName,
            new \DateTimeImmutable($day . 'T08:00:00'),
            new \DateTimeImmutable($day . 'T08:' . $slotDuration . ':00'),
            $doctorId
        ));
    }

    /**
     * @When I display slot list
     */
    public function iDisplaySlotList()
    {
        $this->response = $this->kernel->handle(Request::create('/slots', 'GET'));
    }

    /**
     * @When I display slot list with slots after :day
     */
    public function iDisplaySlotListWithSlotsAfter(string $day)
    {
        $this->response = $this->kernel->handle(Request::create('/slots?date_from=' . $day, 'GET'));
    }

    /**
     * @When I display slot list with slots before :day
     */
    public function iDisplaySlotListWithSlotsBefore(string $day)
    {
        $this->response = $this->kernel->handle(Request::create('/slots?date_to=' . $day, 'GET'));
    }

    /**
     * @When I display slot list with slots after :dayFrom and before :dayTo
     */
    public function iDisplaySlotListWithSlotsAfterAndBefore(string $dayFrom, string $dayTo)
    {
        $this->response = $this->kernel->handle(Request::create('/slots?date_from=' . $dayFrom . '&date_to=' . $dayTo, 'GET'));
    }

    /**
     * @When I display slot list ordered by :sortType
     */
    public function iDisplaySlotListOrderedBy(string $sortType)
    {
        $this->response = $this->kernel->handle(Request::create('/slots?sort_type=' . $sortType, 'GET'));
    }

    /**
     * @Then I see the page
     */
    public function iSeeThePage()
    {
        Assert::eq($this->response->getStatusCode(), 200);
    }

    /**
     * @Then I see :numberOfSlots slots/slot
     */
    public function iSeeSlots(string $numberOfSlots)
    {
        Assert::eq(count(json_decode($this->response->getContent(), true)), (int)$numberOfSlots);;
    }

    /**
     * @Then I see on the page that doctor :doctorName has a slot on :day
     */
    public function iSeeOnThePageThatDoctorHasASlotOn(string $doctorName, string $day)
    {
        $slots = json_decode($this->response->getContent(), true);
        $doctorId = $this->doctors[$doctorName]['id'];
        Assert::notEmpty(array_filter($slots, function (array $row) use ($day, $doctorId) {
            return $row['doctorId'] === $doctorId
                && $row['startTime'] === $day . 'T08:00:00+0000';
        }));
    }

    /**
     * @Then the :nth result is doctor :doctorName's slot on :day
     */
    public function theNthResultIsDoctorSSlotOn(int $nth, string $doctorName, string $day)
    {
        $slots = json_decode($this->response->getContent(), true);
        $doctorId = $this->doctors[$doctorName]['id'];
        Assert::eq($slots[$nth - 1]['doctorId'], $doctorId);
        Assert::eq($slots[$nth - 1]['startTime'], $day . 'T08:00:00+0000');
    }

    /**
     * @Transform /^(\d+)(st|nd|rd|th)$/
     */
    public function castCardinalToNumber($cardinal, $remainder)
    {
        return intval($cardinal);
    }

}
