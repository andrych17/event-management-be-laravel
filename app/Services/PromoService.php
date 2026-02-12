<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PromoService
{
    private const PROMO_API_URL = 'https://api-ticketing.gms.church/servolution/test-promos';
    private const CACHE_KEY = 'external_promos';
    private const CACHE_DURATION = 300; // 5 minutes

    /**
     * Get promos from external API with caching (Task 8: External Promo API)
     */
    public function getPromos()
    {
        try {
            // Cache promos for 5 minutes to reduce external API calls
            return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
                $response = Http::timeout(10)->get(self::PROMO_API_URL);

                if ($response->successful()) {
                    return $response->json();
                }

                Log::warning('Failed to fetch promos: ' . $response->status());
                return [];
            });
        } catch (\Exception $e) {
            Log::error('Failed to fetch promos: ' . $e->getMessage());
            return [];
        }
    }
}
