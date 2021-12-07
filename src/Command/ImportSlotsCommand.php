<?php

namespace App\Command;

use App\Service\SlotImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportSlotsCommand extends Command
{
    protected static $defaultName = 'app:import-slots';
    private SlotImporter $slotImporter;

    public function __construct(SlotImporter $slotImporter, string $name = null)
    {
        parent::__construct($name);
        $this->slotImporter = $slotImporter;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //TODO: log and inform about errors
        $this->slotImporter->importAll();

        return Command::SUCCESS;
    }
}
