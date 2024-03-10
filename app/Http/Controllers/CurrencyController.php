<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function getAll(): JsonResponse
    {
        $currencies = Currency::all();

        Log::info('Successful request of getting currencies', $currencies->toArray());
        return response()->json([
            'status' => 200,
            'data' => $currencies
        ]);
    }

    public function getById(string $id): JsonResponse
    {
        $currency = new Currency();
        $tableName = $currency->getTable();

        $validator = Validator::make(['id' => $id], [
            'id' => "required|exists:$tableName,id",
        ]);

        if ($validator->fails()) {
            Log::error('Id validation not passed', $validator->errors()->messages());
            return response()->json([
                'status' => 400,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 400);
        }

        $currency = $currency->where('id', $id)->get();

        Log::info('Successful request of getting currency by id', $currency->toArray());
        return response()->json([
            'status' => 200,
            'data' => $currency
        ]);
    }
}
