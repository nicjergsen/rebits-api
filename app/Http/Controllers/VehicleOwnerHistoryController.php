<?php

namespace App\Http\Controllers;

use App\Models\VehicleOwnerHistory;
use Illuminate\Http\Request;

class VehicleOwnerHistoryController extends Controller
{
    public function getAllOwnersHistory()
    {
        try {
            $vehicleOwnersHistory = VehicleOwnerHistory::with(['vehicle' => function ($query) {
                $query->withTrashed();
            }, 'user' => function ($query) {
                $query->withTrashed();
            }])->get();
    
            return response()->json($vehicleOwnersHistory);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener historial de propietarios: ' . $e->getMessage()], 500);
        }
    }
}