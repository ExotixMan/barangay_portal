<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class BackupArchiveService
{
    public function createBackup(): string
    {
        if (!class_exists(ZipArchive::class)) {
            throw new \RuntimeException('Zip extension is not enabled on this server.');
        }

        set_time_limit(0);

        $backupDirectory = $this->backupDirectory();
        if (!is_dir($backupDirectory)) {
            mkdir($backupDirectory, 0755, true);
        }

        $timestamp = now('Asia/Manila')->format('Ymd_His');
        $fileName = "barangay_backup_{$timestamp}.zip";
        $zipPath = $backupDirectory . DIRECTORY_SEPARATOR . $fileName;

        $zip = new ZipArchive();
        $openResult = $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        if ($openResult !== true) {
            throw new \RuntimeException('Unable to create backup file.');
        }

        try {
            $databaseSql = $this->buildDatabaseSqlDump();
            $zip->addFromString('database.sql', $databaseSql);

            $zip->addFromString('manifest.json', json_encode([
                'generated_at' => now('Asia/Manila')->toDateTimeString(),
                'app_name' => config('app.name'),
                'app_url' => config('app.url'),
                'environment' => config('app.env'),
                'php_version' => PHP_VERSION,
                'database_connection' => config('database.default'),
                'backup_type' => 'automatic_or_manual',
            ], JSON_PRETTY_PRINT));

            $this->addDirectoryToZip(public_path('uploads'), $zip, 'uploads');
            $this->addDirectoryToZip(public_path('announcement_pic'), $zip, 'announcement_pic');
            $this->addDirectoryToZip(storage_path('app/generated'), $zip, 'generated_documents');

            $zip->close();
        } catch (\Throwable $e) {
            $zip->close();

            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }

            throw $e;
        }

        return $fileName;
    }

    public function backupDirectory(): string
    {
        return storage_path('app/backups');
    }

    private function buildDatabaseSqlDump(): string
    {
        $connection = DB::connection();
        $pdo = $connection->getPdo();
        $database = $connection->getDatabaseName();
        $driver = $connection->getDriverName();

        $sql = "-- Barangay Portal SQL Backup\n";
        $sql .= '-- Generated at: ' . now('Asia/Manila')->toDateTimeString() . "\n\n";
        if ($driver === 'mysql') {
            $sql .= "CREATE DATABASE IF NOT EXISTS `{$database}`;\nUSE `{$database}`;\n\n";
        } elseif ($driver === 'pgsql') {
            $sql .= "-- PostgreSQL backup for database: {$database}\n";
            $sql .= "SET search_path TO public;\n\n";
        }

        $tables = $this->getTableNames($connection, $driver);

        foreach ($tables as $table) {
            $quotedTable = $driver === 'pgsql' ? '"' . $table . '"' : '`' . $table . '`';
            $createStatement = $this->getCreateTableStatement($connection, $driver, $table);

            $sql .= "DROP TABLE IF EXISTS {$quotedTable};\n";
            $sql .= $createStatement . ";\n\n";

            $rows = $connection->table($table)->get();

            if ($rows->isEmpty()) {
                continue;
            }

            $columns = array_keys((array) $rows->first());
            $columnList = $driver === 'pgsql'
                ? '"' . implode('","', $columns) . '"'
                : '`' . implode('`,`', $columns) . '`';

            if ($driver === 'mysql') {
                $sql .= "LOCK TABLES {$quotedTable} WRITE;\n";
            }

            $insertRows = [];
            foreach ($rows as $row) {
                $values = [];
                foreach ((array) $row as $value) {
                    $values[] = $this->formatSqlValue($value, $pdo, $driver);
                }
                $insertRows[] = '(' . implode(', ', $values) . ')';
            }

            $sql .= "INSERT INTO {$quotedTable} ({$columnList}) VALUES\n";
            $sql .= implode(",\n", $insertRows) . ";\n";

            if ($driver === 'mysql') {
                $sql .= "UNLOCK TABLES;\n\n";
            } else {
                $sql .= "\n";
            }
        }

        return $sql;
    }

    private function getTableNames($connection, string $driver): array
    {
        if ($driver === 'mysql') {
            $tables = $connection->select('SHOW TABLES');
            return array_map(function ($tableData) {
                return (string) array_values((array) $tableData)[0];
            }, $tables);
        }

        if ($driver === 'pgsql') {
            $tables = $connection->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename");
            return array_map(function ($tableData) {
                return (string) $tableData->tablename;
            }, $tables);
        }

        if ($driver === 'sqlite') {
            $tables = $connection->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
            return array_map(function ($tableData) {
                return (string) $tableData->name;
            }, $tables);
        }

        throw new \RuntimeException('Unsupported database driver for backup: ' . $driver);
    }

    private function getCreateTableStatement($connection, string $driver, string $table): string
    {
        if ($driver === 'mysql') {
            $createTableResult = (array) $connection->select("SHOW CREATE TABLE `{$table}`")[0];
            return (string) end($createTableResult);
        }

        if ($driver === 'pgsql') {
            return $this->buildPostgresCreateTableStatement($connection, $table);
        }

        if ($driver === 'sqlite') {
            $create = $connection->selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", [$table]);
            return (string) ($create->sql ?? "CREATE TABLE {$table} ()");
        }

        throw new \RuntimeException('Unsupported database driver for schema export: ' . $driver);
    }

    private function buildPostgresCreateTableStatement($connection, string $table): string
    {
        $columns = $connection->select(
            "SELECT column_name, data_type, is_nullable, column_default, character_maximum_length, numeric_precision, numeric_scale
             FROM information_schema.columns
             WHERE table_schema = 'public' AND table_name = ?
             ORDER BY ordinal_position",
            [$table]
        );

        $pkColumns = $connection->select(
            "SELECT kcu.column_name
             FROM information_schema.table_constraints tc
             JOIN information_schema.key_column_usage kcu
               ON tc.constraint_name = kcu.constraint_name
              AND tc.table_schema = kcu.table_schema
             WHERE tc.table_schema = 'public'
               AND tc.table_name = ?
               AND tc.constraint_type = 'PRIMARY KEY'
             ORDER BY kcu.ordinal_position",
            [$table]
        );

        $definitions = [];

        foreach ($columns as $column) {
            $type = $this->mapPostgresType($column);

            $definition = '"' . $column->column_name . '" ' . $type;

            if ($column->column_default !== null) {
                $definition .= ' DEFAULT ' . $column->column_default;
            }

            if ($column->is_nullable === 'NO') {
                $definition .= ' NOT NULL';
            }

            $definitions[] = $definition;
        }

        if (!empty($pkColumns)) {
            $quotedPk = array_map(function ($pk) {
                return '"' . $pk->column_name . '"';
            }, $pkColumns);
            $definitions[] = 'PRIMARY KEY (' . implode(', ', $quotedPk) . ')';
        }

        return 'CREATE TABLE "' . $table . '" (' . "\n    " . implode(",\n    ", $definitions) . "\n)";
    }

    private function mapPostgresType($column): string
    {
        switch ($column->data_type) {
            case 'character varying':
                return $column->character_maximum_length ? 'VARCHAR(' . $column->character_maximum_length . ')' : 'VARCHAR';
            case 'character':
                return $column->character_maximum_length ? 'CHAR(' . $column->character_maximum_length . ')' : 'CHAR';
            case 'numeric':
                if ($column->numeric_precision && $column->numeric_scale !== null) {
                    return 'NUMERIC(' . $column->numeric_precision . ',' . $column->numeric_scale . ')';
                }
                return 'NUMERIC';
            case 'timestamp without time zone':
                return 'TIMESTAMP';
            case 'timestamp with time zone':
                return 'TIMESTAMPTZ';
            default:
                return strtoupper($column->data_type);
        }
    }

    private function formatSqlValue($value, \PDO $pdo, string $driver): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            if ($driver === 'pgsql') {
                return $value ? 'TRUE' : 'FALSE';
            }
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $pdo->quote((string) $value);
    }

    private function addDirectoryToZip(string $sourceDirectory, ZipArchive $zip, string $zipRoot): void
    {
        if (!is_dir($sourceDirectory)) {
            return;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sourceDirectory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                continue;
            }

            $absolutePath = $file->getRealPath();
            if ($absolutePath === false) {
                continue;
            }

            $relativePath = substr($absolutePath, strlen($sourceDirectory) + 1);
            $relativePath = str_replace('\\', '/', $relativePath);
            $zip->addFile($absolutePath, $zipRoot . '/' . $relativePath);
        }
    }
}
