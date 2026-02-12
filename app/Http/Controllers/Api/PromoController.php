<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PromoService;

class PromoController extends Controller
{
    private $promoService;

    public function __construct(PromoService $promoService)
    {
        $this->promoService = $promoService;
    }

    /**
     * Get promos from external API (Task 8: External Promo API)
     */
    public function index()
    {
        $promos = $this->promoService->getPromos();

        return response()->json([
            'promos' => $promos
        ]);
    }
}
