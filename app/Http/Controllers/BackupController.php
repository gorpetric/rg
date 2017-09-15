<?php

namespace App\Http\Controllers;

use Artisan;
use Storage;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        // https://laracasts.com/discuss/channels/laravel/how-to-backup-mysql-database
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
        return redirect()->route('admin.backup.index');
    }

    public function download($backup_name)
    {
        dd($backup_name);
    }

    public function delete($backup_name)
    {
        $disk = Storage::disk('local');

        if(!$disk->exists(config('backup.backup.name') . '/' . $backup_name)) {
            abort(404);
        }

        $disk->delete(config('backup.backup.name') . '/' . $backup_name);
        return redirect()->back();
    }
}
