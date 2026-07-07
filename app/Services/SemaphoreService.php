<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SemaphoreService
{
    private string $baseUrl = 'https://api.semaphore.co/api/v4';

    public function getAccount(string $apiKey): array
    {
        $apiKey = trim($apiKey);

        if ($apiKey === '') {
            return [
                'success' => false,
                'message' => 'Semaphore API key is missing.',
                'status' => null,
                'data' => null,
            ];
        }

        $cacheKey = 'semaphore_account_balance_' . md5($apiKey);

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($apiKey) {
            try {
                $response = Http::timeout(15)
                    ->acceptJson()
                    ->get($this->baseUrl . '/account', [
                        'apikey' => $apiKey,
                    ]);

                if (! $response->successful()) {
                    return [
                        'success' => false,
                        'message' => 'Semaphore error. Status: ' . $response->status() . '. Response: ' . $response->body(),
                        'status' => $response->status(),
                        'data' => null,
                    ];
                }

                return [
                    'success' => true,
                    'message' => 'Semaphore account loaded.',
                    'status' => $response->status(),
                    'data' => $response->json(),
                ];
            } catch (\Throwable $e) {
                return [
                    'success' => false,
                    'message' => 'Unable to connect to Semaphore. ' . $e->getMessage(),
                    'status' => null,
                    'data' => null,
                ];
            }
        });
    }

    public function getMessages(string $apiKey, ?string $startDate = null, ?string $endDate = null): array
    {
        $params = [
            'apikey' => $apiKey,
            'limit' => 1000,
            'page' => 1,
        ];

        if ($startDate) {
            $params['startDate'] = $startDate;
        }

        if ($endDate) {
            $params['endDate'] = $endDate;
        }

        $response = Http::timeout(15)->get($this->baseUrl . '/messages', $params);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Unable to fetch Semaphore messages.',
                'status' => $response->status(),
                'data' => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Semaphore messages loaded.',
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function getTransactions(string $apiKey): array
    {
        $response = Http::timeout(15)->get($this->baseUrl . '/account/transactions', [
            'apikey' => $apiKey,
            'limit' => 100,
            'page' => 1,
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Unable to fetch Semaphore transactions.',
                'status' => $response->status(),
                'data' => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Semaphore transactions loaded.',
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function getSenderNames(string $apiKey): array
    {
        $response = Http::timeout(15)->get($this->baseUrl . '/account/sendernames', [
            'apikey' => $apiKey,
            'limit' => 100,
            'page' => 1,
        ]);

        if (! $response->successful()) {
            return [
                'success' => false,
                'message' => 'Unable to fetch sender names.',
                'status' => $response->status(),
                'data' => [],
            ];
        }

        return [
            'success' => true,
            'message' => 'Sender names loaded.',
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }
}