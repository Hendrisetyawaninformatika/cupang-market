<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected string $databaseUrl;

    public function __construct()
    {
        $this->databaseUrl = rtrim(env('FIREBASE_DATABASE_URL'), '/');
    }

    public function getAllProducts(): array
    {
        try {
            $response = Http::timeout(10)->get("{$this->databaseUrl}/products.json");
            
            if ($response->failed()) {
                Log::error('Firebase getAllProducts failed: ' . $response->body());
                return [];
            }
            
            return $response->json() ?? [];
        } catch (\Exception $e) {
            Log::error('Firebase getAllProducts error: ' . $e->getMessage());
            return [];
        }
    }

    public function getProduct(string $id): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->databaseUrl}/products/{$id}.json");
            
            if ($response->failed()) {
                Log::error('Firebase getProduct failed: ' . $response->body());
                return null;
            }
            
            $data = $response->json();
            if ($data) $data['id'] = $id;
            return $data;
        } catch (\Exception $e) {
            Log::error('Firebase getProduct error: ' . $e->getMessage());
            return null;
        }
    }

    public function createProduct(array $data): string
    {
        try {
            $response = Http::timeout(10)->post("{$this->databaseUrl}/products.json", $data);
            
            if ($response->failed()) {
                Log::error('Firebase createProduct failed: ' . $response->body());
                throw new \Exception('Failed to create product: ' . $response->body());
            }
            
            $result = $response->json();
            Log::info('Firebase createProduct success', ['result' => $result]);
            
            return $result['name'] ?? '';
        } catch (\Exception $e) {
            Log::error('Firebase createProduct error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateProduct(string $id, array $data): void
    {
        try {
            $response = Http::timeout(10)->patch("{$this->databaseUrl}/products/{$id}.json", $data);
            
            if ($response->failed()) {
                Log::error('Firebase updateProduct failed: ' . $response->body());
                throw new \Exception('Failed to update product: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Firebase updateProduct error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProduct(string $id): void
    {
        try {
            $response = Http::timeout(10)->delete("{$this->databaseUrl}/products/{$id}.json");
            
            if ($response->failed()) {
                Log::error('Firebase deleteProduct failed: ' . $response->body());
                throw new \Exception('Failed to delete product: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Firebase deleteProduct error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function saveUser(string $uid, array $data): void
    {
        try {
            Http::timeout(10)->put("{$this->databaseUrl}/users/{$uid}.json", $data);
        } catch (\Exception $e) {
            Log::error('Firebase saveUser error: ' . $e->getMessage());
        }
    }

    public function getUser(string $uid): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->databaseUrl}/users/{$uid}.json");
            return $response->json();
        } catch (\Exception $e) {
            Log::error('Firebase getUser error: ' . $e->getMessage());
            return null;
        }
    }

    public function getStats(): array
    {
        $products = $this->getAllProducts();
        $total = count($products);
        $totalPrice = 0;
        $sellers = [];

        foreach ($products as $product) {
            $totalPrice += $product['price'] ?? 0;
            if (isset($product['sellerId'])) {
                $sellers[$product['sellerId']] = true;
            }
        }

        return [
            'totalProducts' => $total,
            'avgPrice' => $total > 0 ? round($totalPrice / $total) : 0,
            'totalSellers' => count($sellers),
        ];
    }
}