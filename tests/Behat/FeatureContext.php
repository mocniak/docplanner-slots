<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Slot;
use App\Kernel;
use App\Repository\SlotRepository;
use Behat\Behat\Context\Context;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Webmozart\Assert\Assert;

final class FeatureContext implements Context
{
    private int $doctorCounter;
    private array $doctors;
    private SlotRepository $slotRepository;
    private Application $application;


    public function __construct(SlotRepository $slotRepository, Kernel $kernel)
    {
        $this->application = new Application($kernel);
        $this->slotRepository = $slotRepository;
        $this->doctorCounter = 0;
    }

    /**
     * @Given in the supplier API there is a doctor :arg1
     */
    public function inTheSupplierApiThereIsADoctor(string $doctorName)
    {
        $this->doctors[] = ['id' => $this->doctorCounter, 'name' => $doctorName];
        $this->doctorCounter++;
    }

    /**
     * @Given the API says that doctor :arg1 has a slot on :arg2 from :arg3 to :arg4
     */
    public function theApiSaysThatDoctorHasASlotOnFromTo($arg1, $arg2, $arg3, $arg4)
    {
        // do nothing, we're using live API for now
        // TODO: replace with stub API
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
    public function iSeeThatDoctorHasASlotOnFromTo(string $doctorName, string $day, string $startHour, string $endHour)
    {
        $doctorId = array_values(array_filter($this->doctors, fn($doctor) => $doctor['name'] === $doctorName))[0]['id'];
        $start = new \DateTimeImmutable($day . 'T' . $startHour . ':00');
        $end = new \DateTimeImmutable($day . 'T' . $endHour . ':00');
        Assert::eq(
            count(array_filter(
                $this->slotRepository->findForDoctor($doctorId),
                function (Slot $slot) use ($start, $end) {
                    return $slot->startTime() == $start && $slot->endTime() == $end;
                }
            )),
            1
        );
    }
}
