<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\ApiController;
use App\Models\ExchangeRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExchangeRateManagementController extends ApiController
{
    /**
     * Display a listing of exchange rates.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ExchangeRate::query();

        if ($request->filled('from_currency')) {
            $query->where('from_currency', $request->from_currency);
        }

        if ($request->filled('to_currency')) {
            $query->where('to_currency', $request->to_currency);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('effective_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('effective_date', '<=', $request->date_to);
        }

        $exchangeRates = $query->latest('effective_date')->paginate($request->get('per_page', 15));

        return $this->success($exchangeRates);
    }

    /**
     * Store a newly created exchange rate.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from_currency' => ['required', 'string', 'size:3'],
            'to_currency' => ['required', 'string', 'size:3'],
            'rate' => ['required', 'numeric', 'min:0'],
            'effective_date' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        // Check if rate already exists for the same date and currencies
        $existing = ExchangeRate::where('from_currency', $request->from_currency)
            ->where('to_currency', $request->to_currency)
            ->where('effective_date', $request->effective_date)
            ->first();

        if ($existing) {
            return $this->error('Exchange rate already exists for this date and currency pair', 409);
        }

        $exchangeRate = ExchangeRate::create($request->all());

        return $this->success($exchangeRate, 'Exchange rate created successfully', 201);
    }

    /**
     * Display the specified exchange rate.
     */
    public function show(int $id): JsonResponse
    {
        $exchangeRate = ExchangeRate::find($id);

        if (!$exchangeRate) {
            return $this->notFound('Exchange rate not found');
        }

        return $this->success($exchangeRate);
    }

    /**
     * Update the specified exchange rate.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $exchangeRate = ExchangeRate::find($id);

        if (!$exchangeRate) {
            return $this->notFound('Exchange rate not found');
        }

        $validator = Validator::make($request->all(), [
            'from_currency' => ['sometimes', 'required', 'string', 'size:3'],
            'to_currency' => ['sometimes', 'required', 'string', 'size:3'],
            'rate' => ['sometimes', 'required', 'numeric', 'min:0'],
            'effective_date' => ['sometimes', 'required', 'date'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $exchangeRate->update($request->all());

        return $this->success($exchangeRate, 'Exchange rate updated successfully');
    }

    /**
     * Remove the specified exchange rate.
     */
    public function destroy(int $id): JsonResponse
    {
        $exchangeRate = ExchangeRate::find($id);

        if (!$exchangeRate) {
            return $this->notFound('Exchange rate not found');
        }

        $exchangeRate->delete();

        return $this->success(null, 'Exchange rate deleted successfully');
    }

    /**
     * Get current exchange rate.
     */
    public function getCurrentRate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from_currency' => ['required', 'string', 'size:3'],
            'to_currency' => ['required', 'string', 'size:3'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $rate = ExchangeRate::where('from_currency', $request->from_currency)
            ->where('to_currency', $request->to_currency)
            ->where('effective_date', '<=', now())
            ->latest('effective_date')
            ->first();

        if (!$rate) {
            return $this->notFound('No exchange rate found for this currency pair');
        }

        return $this->success($rate);
    }

    /**
     * Get exchange rate history.
     */
    public function getHistory(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'from_currency' => ['required', 'string', 'size:3'],
            'to_currency' => ['required', 'string', 'size:3'],
            'days' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $days = $request->get('days', 30);

        $rates = ExchangeRate::where('from_currency', $request->from_currency)
            ->where('to_currency', $request->to_currency)
            ->where('effective_date', '>=', now()->subDays($days))
            ->orderBy('effective_date')
            ->get();

        return $this->success($rates);
    }

    /**
     * Auto-update exchange rates (placeholder for API integration).
     */
    public function autoUpdate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'api_provider' => ['nullable', 'in:fixer,exchangerate_api,currency_layer'],
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        // This would integrate with external API providers
        // For now, return a message
        return $this->success([
            'message' => 'Auto-update feature requires API integration',
            'supported_providers' => ['fixer', 'exchangerate_api', 'currency_layer'],
        ], 'Exchange rate auto-update initiated');
    }

    /**
     * Get exchange rate statistics.
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total_rates' => ExchangeRate::count(),
            'currency_pairs' => ExchangeRate::selectRaw('DISTINCT CONCAT(from_currency, "-", to_currency) as pair')
                ->pluck('pair')
                ->count(),
            'latest_update' => ExchangeRate::latest('effective_date')->value('effective_date'),
        ];

        return $this->success($stats);
    }
}
