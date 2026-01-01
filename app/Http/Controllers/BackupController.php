<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    /**
     * Mostrar lista de backups
     */
    public function index()
    {
        $backupPath = storage_path('app/backups');
        
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
            $backups = collect([]);
        } else {
            $files = File::files($backupPath);
            $backups = collect($files)->map(function ($file) {
                return [
                    'name' => basename($file),
                    'path' => $file->getPathname(),
                    'size' => round(File::size($file) / 1024, 2), // KB
                    'date' => File::lastModified($file),
                    'formatted_date' => date('d/m/Y H:i:s', File::lastModified($file)),
                ];
            })->sortByDesc('date');
        }

        return view('backups.index', compact('backups'));
    }

    /**
     * Crear nuevo backup manualmente
     */
    public function create()
    {
        try {
            Artisan::call('backup:database');
            return back()->with('success', '✅ Backup creado correctamente');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error al crear backup: ' . $e->getMessage());
        }
    }

    /**
     * Descargar un backup
     */
    public function download($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (!File::exists($filePath)) {
            return back()->with('error', '❌ Archivo no encontrado');
        }

        return Response::download($filePath);
    }

    /**
     * Eliminar un backup
     */
    public function destroy($filename)
    {
        $filePath = storage_path('app/backups/' . $filename);
        
        if (File::exists($filePath)) {
            File::delete($filePath);
            return back()->with('success', '🗑️ Backup eliminado correctamente');
        }

        return back()->with('error', '❌ Archivo no encontrado');
    }
}