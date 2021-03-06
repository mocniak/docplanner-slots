<?php

namespace App\Supplier;

use GuzzleHttp\Client;

class HttpSupplierApi implements SupplierAPI
{
    private Client $client;

    public function __construct(string $supplierApiUser, string $supplierApiPassword)
    {
        // TODO: url from injected parameter
        $this->client = new Client([
            'base_uri' => 'http://cryptic-cove-05648.herokuapp.com/',
            'timeout' => 10.0,
            'auth' => [$supplierApiUser, $supplierApiPassword]
        ]);
    }

    /**
     * @return DoctorFromApi[]
     */
    public function findAllDoctors(): array
    {
        //TODO: error handling
        $result = $this->client->get('/api/doctors');
        return array_map(
            fn(array $row) => new DoctorFromApi((int)$row['id'], $row['name']),
            json_decode($result->getBody(), true)
        );
    }

    public function findSlotsForADoctor(int $doctorId): array
    {
        //TODO: error handling
        $result = $this->client->get('/api/doctors/' . (string)$doctorId . '/slots');
        return array_map(
            fn(array $row) => new SlotFromApi(new \DateTimeImmutable($row['start']), new \DateTimeImmutable($row['end'])),
            json_decode($result->getBody(), true)
        );
    }
}
