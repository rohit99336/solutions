<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


/**
 * Extracts the video ID from a YouTube URL.
 */
if (! function_exists('extractVideoId')) {
    function extractVideoId($url)
    {
        $parsedUrl = parse_url($url);

        // Handle URLs with query parameters (?v=video_id)
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (!empty($queryParams['v'])) {
                return $queryParams['v'];
            }
        }

        // Check for YouTube Shorts, youtu.be, and embed URLs
        if (
            preg_match('/youtu\.be\/([a-zA-Z0-9_-]+)/', $url, $matches) ||
            preg_match('/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/', $url, $matches) ||
            preg_match('/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $url, $matches)
        ) {
            return $matches[1];
        }

        return null; // Return null if no match is found
    }
}

/**
 * Upload file
 */
if (! function_exists('uploadFile')) {
    function uploadFile($file, $path)
    {
        // Generate a unique name for each file
        $imageName = $path . '/' . time() . '_' . $file->getClientOriginalName();

        // Move the file to the desired folder
        $file->move(public_path($path), $imageName);

        return $imageName;
    }
}


/**
 * Delete file
 */
if (! function_exists('deleteFile')) {
    function deleteFile($path)
    {
        if (File::exists(public_path($path))) {
            File::delete(public_path($path));
        }

        // unlink(public_path($template->media_file));
    }
}


/**
 * filter array data
 */
if (!function_exists('cleanArray')) {
    function cleanArray($data)
    {
        return collect($data)->map(function ($value) {
            if (is_array($value)) {
                return array_filter($value, function ($item) {
                    return is_array($item) ? array_filter($item, fn($v) => !is_null($v) && $v !== '') : !is_null($item) && $item !== '';
                });
            }
            return !is_null($value) && $value !== '' ? $value : null;
        })->toArray();
    }
}

/**
 * Set new env value
 */
if (!function_exists('setEnvValue')) {
    function setEnvValue($key, $value)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return false;
        }

        $escaped = preg_quote("{$key}=", '/');

        $envContents = File::get($envPath);

        // If key already exists, replace it
        if (preg_match("/^{$escaped}.*/m", $envContents)) {
            $envContents = preg_replace("/^{$escaped}.*/m", "{$key}=\"{$value}\"", $envContents);
        } else {
            // Append new key-value pair
            $envContents .= "\n{$key}=\"{$value}\"";
        }

        File::put($envPath, $envContents);

        return true;
    }
}

/**
 * Delete existing env values
 */
if (!function_exists('deleteEnvValue')) {
    function deleteEnvValue($key)
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return false;
        }

        $escaped = preg_quote("{$key}=", '/');

        $envContents = File::get($envPath);

        // Remove the line containing the key
        $envContents = preg_replace("/^{$escaped}.*/m", '', $envContents);

        File::put($envPath, $envContents);

        return true;
    }
}

/**
 * Download databse
 */
if (!function_exists('downloadDB')) {
    function downloadDB()
    {
        $fileName = "backup-" . date('Y-m-d-H-i-s') . ".sql";
        $filePath = storage_path("app/backups/{$fileName}");

        // Ensure directory exists
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Run mysqldump
        $command = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $filePath
        );

        system($command);

        return Response::download($filePath)->deleteFileAfterSend(true);
    }
}

/**
 * Download databse strucutre
 */
if (!function_exists('downloadDBStructure')) {
    function downloadDBStructure()
    {
        $fileName = "structure-" . date('Y-m-d-H-i-s') . ".sql";
        $filePath = storage_path("app/backups/{$fileName}");

        // Ensure directory exists
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Run mysqldump for structure only
        $command = sprintf(
            'mysqldump -u%s -p%s --no-data %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_DATABASE'),
            $filePath
        );

        system($command);

        return Response::download($filePath)->deleteFileAfterSend(true);
    }
}


if (!function_exists('exportPdf')) {
    function exportPdf($export, $filename = 'export.pdf')
    {
        // Data le lo
        if (method_exists($export, 'collection')) {
            $collection = $export->collection();
        } else {
            throw new \Exception('Export class must have collection() method');
        }

        $dataArray = $collection->toArray();

        // HTML create karo
        $html = '<h2 style="text-align:center;">Export Data</h2>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" width="100%">';

        if (!empty($dataArray)) {
            // Table header
            $html .= '<thead><tr>';
            foreach (array_keys($dataArray[0]) as $header) {
                $html .= '<th>' . htmlspecialchars(ucfirst($header)) . '</th>';
            }
            $html .= '</tr></thead><tbody>';

            // Table body
            foreach ($dataArray as $row) {
                $html .= '<tr>';
                foreach ($row as $cell) {
                    $html .= '<td>' . htmlspecialchars((string) $cell) . '</td>';
                }
                $html .= '</tr>';
            }
            $html .= '</tbody>';
        } else {
            $html .= '<tr><td colspan="10" align="center">No Data Found</td></tr>';
        }

        $html .= '</table>';

        // PDF generate karo
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
