<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportExcelService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ExcelImportNotification;

class ImportExcelController extends Controller
{
    protected $importService;

    public function __construct(ImportExcelService $importService)
    {
        $this->importService = $importService;
    }

    public function importFromExcel(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'file' => 'required|mimes:xls,xlsx'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $file = $request->file('file');
            $importedData = $this->importService->importUsersAndVehicles($file);

            Mail::to('support@rebits.cl')->send(new ExcelImportNotification('Datos importados correctamente.'));

            return response()->json($importedData, 201);

        } catch (\Exception $e) {
            Mail::to('support@rebits.cl')->send(new ExcelImportNotification('Hubo un error al intentar la importacion del archivo.'));
            return response()->json(['error' => 'Error al importar datos: ' . $e->getMessage()], 500);
        }
    }
}
