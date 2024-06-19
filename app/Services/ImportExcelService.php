<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use App\Models\Vehicle;

class ImportExcelService
{
    public function importUsersAndVehicles($file)
    {
        $reader = IOFactory::createReaderForFile($file->getPathname());
        $spreadsheet = $reader->load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator() as $row) {
            $data = [];
            foreach ($row->getCellIterator() as $cell) {
                $data[] = $cell->getValue();
            }

            // Verificar si la fila está vacía
            if ($this->isEmptyRow($data)) {
                break; // Salir del bucle si la fila está vacía
            }

            $userData = $this->importUserData($data);
            $vehicleData = $this->importVehicleData($data, $userData['user']);

            if (isset($userData['error'])) {
                $errors[] = "Error al importar usuario en fila {$row->getRowIndex()}: {$userData['error']}";
                continue;
            }

            if (isset($vehicleData['error'])) {
                $errors[] = "Error al importar vehículo en fila {$row->getRowIndex()}: {$vehicleData['error']}";
                continue;
            }

            $importedData[] = [
                'user' => $userData['user'],
                'vehicle' => $vehicleData['vehicle'],
            ];
        }

        if (empty($importedData)) {
            throw new \Exception('Error: No se encontraron datos válidos para importar.');
        }

        return $importedData;
    }

    private function importUserData($data)
    {
        $validator = Validator::make($data, [
            0 => 'required|string', // Nombre
            1 => 'required|string', // Apellidos
            2 => 'required|email', // Correo
        ], [
            'required' => 'El campo :attribute es requerido.',
            'email' => 'El campo :attribute debe ser una dirección de correo electrónico válida.',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()->first()];
        }

        $user = User::where('email', $data[2])->first();
        if ($user) {
            return ['user' => $user];
        }

        $user = new User();
        $user->nombre = $data[0];
        $user->apellidos = $data[1];
        $user->email = $data[2];
        $user->save();

        return ['user' => $user];
    }

    private function importVehicleData($data, $user)
    {
        $validator = Validator::make($data, [
            3 => 'required|string', // Marca
            4 => 'required|string', // Modelo
            5 => 'required|string', // Patente
            6 => 'required', // Año
            7 => 'required', // Precio
        ], [
            'required' => 'El campo :attribute es requerido.',
            'string' => 'El campo :attribute debe ser una cadena de texto.',
            'integer' => 'El campo :attribute debe ser un número entero.',
        ]);

        if ($validator->fails()) {
            return ['error' => $validator->errors()->first()];
        }

        $existingVehicle = Vehicle::where('patente', $data[5])->first();
        if ($existingVehicle) {
            return ['error' => 'El vehículo con patente ' . $data[5] . ' ya está registrado.'];
        }

        $vehicle = new Vehicle();
        $vehicle->marca = $data[3];
        $vehicle->modelo = $data[4];
        $vehicle->patente = $data[5];
        $vehicle->año = $data[6];
        $vehicle->user_id = $user->id;
        $vehicle->precio = $data[7];
        $vehicle->save();

        return ['vehicle' => $vehicle];
    }

    private function isEmptyRow($data)
    {
        foreach ($data as $value) {
            if (!is_null($value) && $value !== '') {
                return false;
            }
        }
        return true;
    }
}
