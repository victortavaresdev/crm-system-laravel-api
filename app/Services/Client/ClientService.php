<?php

namespace App\Services\Client;

use App\DTO\Client\ClientDTO;
use App\Exceptions\Custom\ConflictException;
use App\Models\Client;

class ClientService
{
    public function store(ClientDTO $dto): Client
    {
        $clientEmail = Client::where(['contact_email' => $dto->contact_email])->first();
        if ($clientEmail)
            throw new ConflictException('Email already registered');

        $client = auth()->user()->clients()->create((array) $dto);

        return $client;
    }

    public function index()
    {
        $clients = auth()->user()->clients()->paginate(10);

        return $clients;
    }

    public function update(Client $client, ClientDTO $dto): Client|null
    {
        $client->update((array) $dto);

        return $client;
    }

    public function destroy(Client $client): void
    {
        $client->delete();
    }
}
