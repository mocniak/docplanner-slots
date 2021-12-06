<?php

namespace App\Repository;

use App\Entity\Slot;
use App\ValueObject\SlotsCollection;

class FilesystemSlotRepository implements SlotRepository
{
    const SUPER_SECURE_STORAGE = '/secret/slots.txt';
    private array $slots;
    private string $basePath;

    public function __construct(string $projectDir)
    {
        $this->basePath = $projectDir;

        if (!file_exists($this->basePath . self::SUPER_SECURE_STORAGE)) {
            file_put_contents($this->basePath . self::SUPER_SECURE_STORAGE, serialize([]));
        }

        $this->slots = \unserialize(file_get_contents($this->basePath . self::SUPER_SECURE_STORAGE));
    }

    public function __destruct()
    {
        file_put_contents($this->basePath . self::SUPER_SECURE_STORAGE, \serialize($this->slots));
    }

    public function add(Slot $slot): void
    {
        $this->slots[$slot->id()] = $slot;
    }

    public function findAll(): SlotsCollection
    {
        return SlotsCollection::fromArray($this->slots);
    }
}
