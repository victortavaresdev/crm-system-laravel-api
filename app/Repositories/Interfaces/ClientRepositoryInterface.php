<?php

namespace App\Repositories\Interfaces;

use App\Models\Client;
use App\DTO\Client\{CreateClientDTO, UpdateClientDTO};

interface ClientRepositoryInterface
{
    public function create(CreateClientDTO $dto): Client;
    public function findAll();
    public function findById(string $id): Client|null;
    public function findByEmail(string $email): Client|null;
    public function findByContactName(string $contactName): Client|null;
    public function update(string $id, UpdateClientDTO $dto): Client|null;
    public function delete(string $id): void;
}
