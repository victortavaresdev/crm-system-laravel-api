<?php

namespace App\Repositories\Client;

use App\Models\Client;
use App\DTO\Client\{CreateClientDTO, UpdateClientDTO};
use App\Repositories\Interfaces\ClientRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ClientEloquentORM implements ClientRepositoryInterface
{
    public function create(CreateClientDTO $dto): Client
    {
        $createdClient = Client::create(
            [
                'contact_name' => $dto->contact_name,
                'contact_email' => $dto->contact_email,
                'contact_phone_number' => $dto->contact_phone_number,
                'company_name' => $dto->company_name,
                'company_address' => $dto->company_address,
                'company_city' => $dto->company_city,
                'company_zip' => $dto->company_zip,
                'user_id' => auth()->user()->id
            ]
        );

        return $createdClient;
    }

    public function findAll()
    {
        $userClients = Auth::user()->clients()->paginate(10);

        return $userClients;
    }

    public function findById(string $id): Client|null
    {
        $clientData = Client::find($id);

        return $clientData;
    }

    public function findByEmail(string $email): Client|null
    {
        $clientData = Client::where(['contact_email' => $email])->first();

        return $clientData;
    }

    public function findByContactName(string $contactName): Client|null
    {
        $clientData = Client::where(['contact_name' => $contactName])->first();

        return $clientData;
    }

    public function update(string $id, UpdateClientDTO $dto): Client|null
    {
        $updatedClient = Client::find($id);
        $updatedClient->update(
            array_filter(
                [
                    'contact_name' => $dto->contact_name,
                    'contact_email' => $dto->contact_email,
                    'contact_phone_number' => $dto->contact_phone_number,
                    'company_name' => $dto->company_name,
                    'company_address' => $dto->company_address,
                    'company_city' => $dto->company_city,
                    'company_zip' => $dto->company_zip
                ]
            )
        );

        return $updatedClient;
    }

    public function delete(string $id): void
    {
        $client = Client::find($id);
        $client->delete();
    }
}
