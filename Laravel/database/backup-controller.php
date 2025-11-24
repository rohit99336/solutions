<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DBBackupController extends Controller
{
    public function backup()
    {
        $fileName = "backup-" . date('Y-m-d-H-i-s') . ".sql";
        $filePath = storage_path("app/backups/{$fileName}");

        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $escapedPath = escapeshellarg($filePath);
        $command = "mysqldump -u$username -p$password $database > $escapedPath";

        shell_exec($command);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Backup failed! File not created.');
        }

        return Response::download($filePath)->deleteFileAfterSend(true);
    }

    public function structure()
    {
        $fileName = "structure-" . date('Y-m-d-H-i-s') . ".sql";
        $filePath = storage_path("app/backups/{$fileName}");

        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $escapedPath = escapeshellarg($filePath);
        $command = "mysqldump -u$username -p$password --no-data $database > $escapedPath";

        shell_exec($command);

        if (!file_exists($filePath)) {
            return back()->with('error', 'Structure backup failed!');
        }

        return Response::download($filePath)->deleteFileAfterSend(true);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'sql_file' => 'required|mimes:sql'
        ]);

        $file = $request->file('sql_file');
        $path = $file->store('backups');

        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');

        $filePath = storage_path("app/" . $path);

        $command = "mysql -u$username -p$password $database < " . escapeshellarg($filePath);
        shell_exec($command);

        return back()->with('success', 'Database restored successfully!');
    }
}
