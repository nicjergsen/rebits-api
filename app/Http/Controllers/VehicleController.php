<?php
namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\User;
use App\Models\VehicleOwnerHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VehicleController extends Controller
{
    public function index()
    {
        try {
            $vehicles = Vehicle::whereNull('deleted_at')->with('user:id,nombre,apellidos')->get();
            return response()->json($vehicles);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener vehículos: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'marca' => 'required|string|max:255',
                'modelo' => 'required|string|max:255',
                'patente' => 'required|string|size:6|unique:vehicles',
                'año' => 'required|integer',
                'email' => 'required|email',
                'precio' => 'required|integer',
            ]);
    
            $user = User::where('email', $validatedData['email'])->first();
    
            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
            }
    
            $vehicle = new Vehicle();
            $vehicle->marca = $validatedData['marca'];
            $vehicle->modelo = $validatedData['modelo'];
            $vehicle->patente = $validatedData['patente'];
            $vehicle->año = $validatedData['año'];
            $vehicle->precio = $validatedData['precio'];
            $vehicle->user_id = $user->id; // Asignar el ID del usuario encontrado
            $vehicle->save();
    
            return response()->json($vehicle, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear vehículo: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $vehicle = Vehicle::whereNull('deleted_at')->with('user:id,email')->findOrFail($id);

            if ($vehicle->user && $vehicle->user->trashed()) {
                $vehicle->user = null;
            }
            return response()->json($vehicle);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Vehículo no encontrado'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener vehículo: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $vehicle = Vehicle::whereNull('deleted_at')->findOrFail($id);
    
            $validatedData = $request->validate([
                'marca' => 'sometimes|required|string|max:255',
                'modelo' => 'sometimes|required|string|max:255',
                'patente' => 'sometimes|required|string|max:6|unique:vehicles,patente,' . $vehicle->id,
                'año' => 'sometimes|required|integer',
                'email' => 'sometimes|required|email',
                'precio' => 'sometimes|required|integer',
            ]);
    
            $previousOwnerId = $vehicle->user_id;
    
            if (isset($validatedData['email'])) {
                $user = User::where('email', $validatedData['email'])->first();
                if (!$user) {
                    return response()->json(['error' => 'Usuario no encontrado'], Response::HTTP_NOT_FOUND);
                }
                $vehicle->user_id = $user->id;
                unset($validatedData['email']);
            }
    
            $vehicle->update($validatedData);
    
            if ($previousOwnerId != $vehicle->user_id) {
                $history = new VehicleOwnerHistory();
                $history->vehicle_id = $vehicle->id;
                $history->user_id = $previousOwnerId;
                $history->save();
            }
    
            return response()->json($vehicle);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar vehículo: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function destroy($id)
    {
        try {
            $vehicle = Vehicle::whereNull('deleted_at')->findOrFail($id);
            $vehicle->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar vehículo: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function restore($id)
    {
        try {
            $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
            $vehicle->restore();

            return response()->json($vehicle);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al restaurar vehículo: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
