<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\DTO\Client\{CreateClientDTO, UpdateClientDTO};
use App\Http\Requests\Client\{StoreClientRequest, UpdateClientRequest};
use App\Http\Resources\Client\{ClientCollection, ClientResource};
use App\Models\Client;
use Illuminate\Http\Resources\Json\{JsonResource, ResourceCollection};
use App\Services\Client\ClientService;

class ClientController extends Controller
{
    public function __construct(
        protected ClientService $clientService
    ) {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/clients/create",
     *     tags={"Clients"},
     *     summary="Create client",
     *     operationId="create-client",
     *     security={{"bearerToken":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="contact_name", example="Pedro"),
     *             @OA\Property(property="contact_email", example="pedro@gmail.com"),
     *             @OA\Property(property="contact_phone_number", example="(31) 95050-2020"),
     *             @OA\Property(property="company_name", example="Empresa X",),
     *             @OA\Property(property="company_address", example="Rua afonso mendes"),
     *             @OA\Property(property="company_city", example="Belo Horizonte"),
     *             @OA\Property(property="company_zip", example="50030-700"),
     *         ),
     *     ),
     *     @OA\Response(response="201", description="Created"),
     *     @OA\Response(response="400",description="Bad Request"),
     *     @OA\Response(response="409",description="Conflict"),
     * )
     */
    public function store(StoreClientRequest $request): JsonResource
    {
        $dto = CreateClientDTO::fromRequest($request);
        $client = $this->clientService->store($dto);

        return new ClientResource($client);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/clients",
     *     tags={"Clients"},
     *     summary="Get clients list",
     *     operationId="get-clients",
     *     security={{"bearerToken":{}}},
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     * )
     */
    public function index(): ResourceCollection
    {
        $clients = $this->clientService->index();

        return new ClientCollection($clients);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/clients/{id}",
     *     tags={"Clients"},
     *     summary="Get client data",
     *     operationId="get-client",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function show(string $id): JsonResource
    {
        $client = $this->isUserAuthorized($id, 'view');

        return new ClientResource($client);
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/clients/{id}/update",
     *     tags={"Clients"},
     *     summary="Update client data",
     *     operationId="update-client",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="contact_name", example="Pedro updated"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="400", description="Bad Request"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function update(string $id, UpdateClientRequest $request): JsonResource
    {
        $this->isUserAuthorized($id, 'update');

        $dto = UpdateClientDTO::fromRequest($request);
        $client = $this->clientService->update($id, $dto);

        return new ClientResource($client);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/clients/{id}/delete",
     *     tags={"Clients"},
     *     summary="Delete client data",
     *     operationId="delete-client",
     *     security={{"bearerToken":{}}},
     *     @OA\Parameter(name="id", in="path", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="OK"),
     *     @OA\Response(response="401", description="Unauthenticated"),
     *     @OA\Response(response="403", description="Forbidden"),
     *     @OA\Response(response="404", description="Not Found"),
     * )
     */
    public function destroy(string $id): void
    {
        $this->isUserAuthorized($id, 'delete');
        $this->clientService->destroy($id);
    }

    private function isUserAuthorized(string $id, string $action): Client|null
    {
        $client = $this->clientService->show($id);
        $this->authorize($action, $client);

        return $client;
    }
}
