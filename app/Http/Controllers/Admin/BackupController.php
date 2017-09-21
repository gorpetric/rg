<?php

namespace App\Http\Controllers\Admin;

use Artisan;
use Storage;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function index()
    {
        $disk = Storage::disk('local');
        $files = $disk->files(config('backup.backup.name'));
        $backups = [];

        foreach($files as $file) {
            if($disk->exists($file) && substr($file, -4) == '.zip') {
                $backups[] = [
                    'name' => str_replace(config('backup.backup.name') . '/', '', $file),
                    'path' => $file,
                    'size' => formatBytes($disk->size($file)),
                    'last_modified' => $disk->lastModified($file),
                ];
            }
        }

        $backups = array_reverse($backups);

        return view('admin.backup.index', compact('backups'));
    }

    public function create()
    {
        try {
            Artisan::call('backup:run', ['--only-db' => true]);
            // $output = Artisan::output();
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back();
        }
    }

    public function download($backup_name)
    {
        $backup = config('backup.backup.name') . '/' . $backup_name;
        $disk = Storage::disk('local');

        if(!$disk->exists($backup)) {
            abort(404);
        }

        $fs = $disk->getDriver();
        $stream = $fs->readStream($backup);

        return response()->stream(function() use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $fs->getMimetype($backup),
            'Content-Length' => $fs->getSize($backup),
            'Content-disposition' => "attachment; filename=\"" . basename($backup) . "\"",
        ]);
    }

    public function delete($backup_name)
    {
        $backup = config('backup.backup.name') . '/' . $backup_name;
        $disk = Storage::disk('local');

        if(!$disk->exists($backup)) {
            abort(404);
        }

        $disk->delete($backup);
        return redirect()->back();
    }
}
