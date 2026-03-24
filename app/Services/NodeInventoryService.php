<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class NodeInventoryService
{
    private string $baseUrl;

    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.node_inventory.base_url'), '/');
        $this->apiKey = (string) config('services.node_inventory.api_key');
    }

    /**
     * @throws RequestException
     */
    public function listParts(array $filters = []): array
    {
        $queryParams = [];

        if (!empty($filters['q'])) {
            $queryParams['q'] = $filters['q'];
        }

        if (!empty($filters['page'])) {
            $queryParams['page'] = (int) $filters['page'];
        }

        if (!empty($filters['per_page'])) {
            $queryParams['per_page'] = (int) $filters['per_page'];
        }

        $response = $this->client()->get($this->url('/parts'), $queryParams);
        $response->throw();

        return [
            'items' => $response->json('data.items', []),
            'meta' => $response->json('data.meta', []),
        ];
    }

    /**
     * @throws RequestException
     */
    public function createPart(array $payload): array
    {
        $response = $this->client()->post($this->url('/parts'), $payload);
        $response->throw();

        return $response->json('data', []);
    }

    /**
     * @throws RequestException
     */
    public function findPart(int $id): ?array
    {
        $response = $this->client()->get($this->url('/parts/'.$id));

        if ($response->status() === 404) {
            return null;
        }

        $response->throw();

        return $response->json('data');
    }

    /**
     * @throws RequestException
     */
    public function updatePart(int $id, array $payload): array
    {
        $response = $this->client()->put($this->url('/parts/'.$id), $payload);
        $response->throw();

        return $response->json('data', []);
    }

    /**
     * @throws RequestException
     */
    public function deletePart(int $id): void
    {
        $response = $this->client()->delete($this->url('/parts/'.$id));
        $response->throw();
    }

    private function client()
    {
        return Http::acceptJson()
            ->withHeader('x-api-key', $this->apiKey)
            ->timeout(8)
            ->connectTimeout(4);
    }

    private function url(string $path): string
    {
        return $this->baseUrl.$path;
    }
}
