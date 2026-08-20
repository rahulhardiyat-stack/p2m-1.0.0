<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class BackupController extends Controller
{
    protected string $disk = 'local';
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = config('backup.backup.name', 'Laravel');
    }

    public function index()
    {
        $backups = $this->getBackupFiles();
        $diskTotal = $this->formatBytes(disk_total_space(storage_path()));
        $diskFree = $this->formatBytes(disk_free_space(storage_path()));
        return view('admin.backups.index', compact('backups', 'diskTotal', 'diskFree'));
    }

    public function create()
    {
        try {
            // Step 1: Dump database using PHP-native method
            $dumpPath = $this->dumpDatabase();

            // Step 2: Run backup only for files (which includes the dump)
            Artisan::call('backup:run', [
                '--disable-notifications' => true,
                '--only-files' => true
            ]);

            $output = Artisan::output();

            // Step 3: Clean up the temporary dump file
            if (file_exists($dumpPath)) {
                unlink($dumpPath);
            }

            $success = !str_contains(strtolower($output), 'error') && !str_contains(strtolower($output), 'failed');
            if ($success) {
                return redirect()->route('admin.backups.index')
                    ->with('success', 'Backup berhasil dibuat!');
            }
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup gagal: ' . $output);
        }
        catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function updateSchedule(Request $request)
    {
        $request->validate([
            'backup_enabled' => 'nullable|string',
            'backup_frequency' => 'required|in:daily,weekly,monthly',
        ]);

        Setting::updateOrCreate(['key' => 'backup_enabled'], ['value' => $request->has('backup_enabled') ? '1' : '0', 'group' => 'backup', 'type' => 'boolean']);
        Setting::updateOrCreate(['key' => 'backup_frequency'], ['value' => $request->backup_frequency, 'group' => 'backup', 'type' => 'select']);

        return redirect()->back()->with('success', 'Pengaturan jadwal backup berhasil diperbarui.');
    }

    private function dumpDatabase(): string
    {
        $database = config('database.connections.mysql.database');
        $tables = DB::select('SHOW TABLES');
        $tableNameKey = 'Tables_in_' . $database;

        $sql = "-- Database Dump: " . $database . "\n";
        $sql .= "-- Generated at: " . date('Y-m-d H:i:s') . "\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableNameKey;

            // Table structure
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`")[0];
            $createTableKey = 'Create Table';
            $sql .= "DROP TABLE IF EXISTS `$tableName`;\n";
            $sql .= $createTable->$createTableKey . ";\n\n";

            // Table data
            $rows = DB::table($tableName)->get();
            foreach ($rows as $row) {
                $rowArray = (array)$row;
                $columns = implode("`, `", array_keys($rowArray));
                $values = array_map(function ($value) {
                    if ($value === null)
                        return 'NULL';
                    return DB::getPdo()->quote($value);
                }, array_values($rowArray));
                $valuesStr = implode(", ", $values);

                $sql .= "INSERT INTO `$tableName` (`$columns`) VALUES ($valuesStr);\n";
            }
            $sql .= "\n";
        }

        $path = storage_path('app/db-dump.sql');
        file_put_contents($path, $sql);

        return $path;
    }

    public function download(string $filename)
    {
        $path = $this->backupDir . '/' . $filename;

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        $fullPath = Storage::disk($this->disk)->path($path);
        return response()->download($fullPath, $filename);
    }

    public function destroy(string $filename)
    {
        $path = $this->backupDir . '/' . $filename;

        if (!Storage::disk($this->disk)->exists($path)) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'File tidak ditemukan.');
        }

        Storage::disk($this->disk)->delete($path);
        return redirect()->route('admin.backups.index')
            ->with('success', 'Backup berhasil dihapus.');
    }

    public function destroyOld()
    {
        try {
            Artisan::call('backup:clean', ['--disable-notifications' => true]);
            return redirect()->route('admin.backups.index')
                ->with('success', 'Backup lama berhasil dibersihkan.');
        }
        catch (\Exception $e) {
            return redirect()->route('admin.backups.index')
                ->with('error', 'Gagal membersihkan backup: ' . $e->getMessage());
        }
    }

    protected function getBackupFiles(): array
    {
        $files = [];
        try {
            $paths = Storage::disk($this->disk)->files($this->backupDir);
            foreach ($paths as $path) {
                $name = basename($path);
                if (!str_ends_with($name, '.zip'))
                    continue;

                $size = Storage::disk($this->disk)->size($path);
                $lastModified = Storage::disk($this->disk)->lastModified($path);

                $files[] = [
                    'name' => $name,
                    'size' => $this->formatBytes($size),
                    'size_raw' => $size,
                    'date' => date('d M Y H:i:s', $lastModified),
                    'date_raw' => $lastModified,
                ];
            }
            usort($files, fn($a, $b) => $b['date_raw'] - $a['date_raw']);
        }
        catch (\Exception $e) {
        // Directory may not exist yet
        }
        return $files;
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}
