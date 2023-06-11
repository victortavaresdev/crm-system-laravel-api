<?php

namespace App\Services\Client;

use App\Models\Client;
use App\DTO\Client\{CreateClientDTO, UpdateClientDTO};
use App\Exceptions\CustomExceptions\{ConflictException, NotFoundException};
use App\Repositories\Interfaces\ClientRepositoryInterface;

class ClientService
{
    public function __construct(
        protected ClientRepositoryInterface $clientRepository
    ) {
    }

    public function store(CreateClientDTO $dto): Client
    {
        $clientEmail = $this->clientRepository->findByEmail($dto->contact_email);
        if ($clientEmail) throw new ConflictException('Email already registered');

        $client = $this->clientRepository->create($dto);
        return $client;
    }

    public function index()
    {
        $clients = $this->clientRepository->findAll();
        return $clients;
    }

    public function show(string $id): Client|null
    {
        $client = $this->clientRepository->findById($id);
        if (!$client) throw new NotFoundException('Client not found');

        return $client;
    }

    public function update(string $id, UpdateClientDTO $dto): Client|null
    {
        $client = $this->clientRepository->update($id, $dto);
        return $client;
    }

    public function destroy(string $id): void
    {
        $this->clientRepository->delete($id);
    }
}
