<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

final class FeatureContext implements Context
{
    /**
     * @Given in the supplier API there is a doctor :arg1
     */
    public function inTheSupplierApiThereIsADoctor($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given the API says that doctor :arg1 has a slot on :arg2 from :arg3 to :arg4
     */
    public function theApiSaysThatDoctorHasASlotOnFromTo($arg1, $arg2, $arg3, $arg4)
    {
        throw new PendingException();
    }

    /**
     * @When I import slots from the supplier
     */
    public function iImportSlotsFromTheSupplier()
    {
        throw new PendingException();
    }

    /**
     * @When I see that doctor :arg1 has a slot on :arg2 from :arg3 to :arg4
     */
    public function iSeeThatDoctorHasASlotOnFromTo($arg1, $arg2, $arg3, $arg4)
    {
        throw new PendingException();
    }

}
