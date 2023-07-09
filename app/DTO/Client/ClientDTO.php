<?php

namespace App\DTO\Client;

use App\Http\Requests\Client\ClientRequest;

class ClientDTO
{
    public function __construct(
        public readonly string $contact_name,
        public readonly string $contact_email,
        public readonly string $contact_phone_number,
        public readonly string $company_name,
        public readonly string $company_address,
        public readonly string $company_city,
        public readonly string $company_zip
    ) {
    }

    public static function fromRequest(ClientRequest $request): self
    {
        return new self(
            contact_name: $request->validated('contact_name'),
            contact_email: $request->validated('contact_email'),
            contact_phone_number: $request->validated('contact_phone_number'),
            company_name: $request->validated('company_name'),
            company_address: $request->validated('company_address'),
            company_city: $request->validated('company_city'),
            company_zip: $request->validated('company_zip'),
        );
    }
}
