<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    /**
     * Listar todos los roles
     */
    public function index(): JsonResponse
    {
        $roles = Rol::all();
        return response()->json([
            'success' => true,
            'data' => $roles
        ], 200);
    }

    /**
     * Crear un nuevo rol
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:roles',
            'descripcion' => 'nullable|string'
        ]);

        $rol = Rol::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rol creado exitosamente',
            'data' => $rol
        ], 201);
    }

    /**
     * Mostrar un rol específico
     */
    public function show(string $id): JsonResponse
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $rol
        ], 200);
    }

    /**
     * Actualizar un rol
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255|unique:roles,nombre,' . $id,
            'descripcion' => 'nullable|string'
        ]);

        $rol->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Rol actualizado exitosamente',
            'data' => $rol
        ], 200);
    }

    /**
     * Eliminar un rol
     */
    public function destroy(string $id): JsonResponse
    {
        $rol = Rol::find($id);

        if (!$rol) {
            return response()->json([
                'success' => false,
                'message' => 'Rol no encontrado'
            ], 404);
        }

        $rol->delete();

        return response()->json([
            'success' => true,
            'message' => 'Rol eliminado exitosamente'
        ], 200);
    }
}