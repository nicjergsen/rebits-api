<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::whereNull('deleted_at')->get();

            return response()->json($users);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al obtener usuarios', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
            ])->validate();

            $user = User::create($validatedData);

            return response()->json($user, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $user = User::whereNull('deleted_at')->findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Usuario no encontrado', 'error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validatedData = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'apellidos' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            ])->validate();

            $user->update($validatedData);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al actualizar usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar usuario', 'error' => $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = User::onlyTrashed()->findOrFail($id);
            $user->restore();

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al restaurar usuario', 'error' => $e->getMessage()], 500);
        }
    }
}
