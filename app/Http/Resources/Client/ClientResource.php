<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Client
 */
class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'contactName' => $this->contact_name,
            'contactEmail' => $this->contact_email,
            'contactPhoneNumber' => $this->contact_phone_number,
            'companyName' => $this->company_name,
            'companyAddress' => $this->company_address,
            'companyCity' => $this->company_city,
            'companyZip' => $this->company_zip,
        ];
    }
}
