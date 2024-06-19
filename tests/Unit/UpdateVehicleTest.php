<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\VehicleOwnerHistory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateVehicleTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateVehicleAndChangeOwner()
    {
        // Crear un usuario y un vehículo
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $vehicle = Vehicle::factory()->create(['user_id' => $user1->id]);

        // Datos de actualización
        $updateData = [
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'patente' => 'ABC123',
            'año' => 2020,
            'email' => $user2->email,
            'precio' => 15000
        ];

        // Llamar a la ruta de actualización
        $response = $this->putJson("/api/vehicles/{$vehicle->id}", $updateData);

        // Verificar la respuesta
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'marca' => 'Toyota',
            'modelo' => 'Corolla',
            'patente' => 'ABC123',
            'año' => 2020,
            'precio' => 15000
        ]);

        // Verificar que el dueño ha cambiado
        $vehicle->refresh();
        $this->assertEquals($user2->id, $vehicle->user_id);

        // Verificar que se ha creado un registro en el historial de dueños
        $this->assertDatabaseHas('vehicle_owners_history', [
            'vehicle_id' => $vehicle->id,
            'user_id' => $user1->id
        ]);
    }
}
