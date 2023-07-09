<?php

namespace App\Http\Controllers\Api\V1\Client;

use App\DTO\Client\ClientDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\ClientRequest;
use App\Http\Resources\Client\ClientResource;
use App\Models\Client;
use App\Services\Client\ClientService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @group Client endpoints
 */
class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {
    }

    /**
     * POST Client
     *
     * Create a new client.
     *
     * @authenticated
     *
     * @response 201 {"message":"Created"}
     * @response 400 {"message": "Bad Request"}
     * @response 409 {"message": "Conflict"}
     */
    public function store(ClientRequest $request): JsonResource
    {
        $dto = ClientDTO::fromRequest($request);
        $client = $this->clientService->store($dto);

        return new ClientResource($client);
    }

    /**
     * GET Clients
     *
     * Get clients list.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     */
    public function index(): ResourceCollection
    {
        $clients = $this->clientService->index();

        return ClientResource::collection($clients);
    }

    /**
     * GET Client
     *
     * Get client data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function show(Client $client): JsonResource
    {
        $this->authorize('view', $client);

        return new ClientResource($client);
    }

    /**
     * PUT Client
     *
     * Update client data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 400 {"message": "Bad Request"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function update(Client $client, ClientRequest $request): JsonResource
    {
        $this->authorize('update', $client);

        $dto = ClientDTO::fromRequest($request);
        $client = $this->clientService->update($client, $dto);

        return new ClientResource($client);
    }

    /**
     * DELETE Client
     *
     * Delete client data.
     *
     * @authenticated
     *
     * @response {"message":"OK"}
     * @response 401 {"message": "Unauthenticated"}
     * @response 403 {"message": "Forbidden"}
     * @response 404 {"message": "Not Found"}
     */
    public function destroy(Client $client): void
    {
        $this->authorize('delete', $client);
        $this->clientService->destroy($client);
    }
}
