<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class BackupArchiveService
{
    private const RESTORE_DIRECTORIES = [
        'uploads',
        'announcement_pic',
        'generated_documents',
    ];

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

    public function restoreBackup(string $archivePath): void
    {
        if (!class_exists(ZipArchive::class)) {
            throw new \RuntimeException('Zip extension is not enabled on this server.');
        }

        if (!file_exists($archivePath)) {
            throw new \RuntimeException('Backup file does not exist.');
        }

        set_time_limit(0);

        $tempDirectory = storage_path('app/restore_temp/' . uniqid('restore_', true));
        File::ensureDirectoryExists($tempDirectory);

        try {
            $databaseSqlPath = $this->extractBackupArchive($archivePath, $tempDirectory);
            $this->restoreDatabaseFromSql($databaseSqlPath);

            $this->syncDirectory(
                $tempDirectory . DIRECTORY_SEPARATOR . 'uploads',
                public_path('uploads')
            );
            $this->syncDirectory(
                $tempDirectory . DIRECTORY_SEPARATOR . 'announcement_pic',
                public_path('announcement_pic')
            );
            $this->syncDirectory(
                $tempDirectory . DIRECTORY_SEPARATOR . 'generated_documents',
                storage_path('app/generated')
            );
        } finally {
            File::deleteDirectory($tempDirectory);
        }
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

        $sql .= "-- Drop existing tables (children first)\n";
        foreach (array_reverse($tables) as $table) {
            $quotedTable = $this->quoteTableIdentifier($table, $driver);
            $sql .= "DROP TABLE IF EXISTS {$quotedTable};\n";
        }

        $sql .= "\n-- Create tables (parents first)\n";
        foreach ($tables as $table) {
            $createStatement = $this->getCreateTableStatement($connection, $driver, $table);
            $sql .= $createStatement . ";\n\n";
        }

        $sql .= "-- Insert data (parents first)\n";
        foreach ($tables as $table) {
            $insertSql = $this->buildTableInsertSql($connection, $pdo, $driver, $table);
            if ($insertSql !== '') {
                $sql .= $insertSql;
            }
        }

        if ($driver === 'pgsql') {
            $sql .= "-- Reset PostgreSQL sequences\n";
            $sql .= $this->buildPostgresSequenceResetSql($connection, $tables);
            $sql .= "\n";
        }

        return $sql;
    }

    private function buildTableInsertSql($connection, \PDO $pdo, string $driver, string $table): string
    {
        $rows = $connection->table($table)->get();

        if ($rows->isEmpty()) {
            return '';
        }

        $quotedTable = $this->quoteTableIdentifier($table, $driver);
        $columns = array_keys((array) $rows->first());
        $columnList = $driver === 'pgsql'
            ? '"' . implode('","', $columns) . '"'
            : '`' . implode('`,`', $columns) . '`';

        $sql = '';
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

        return $sql;
    }

    private function quoteTableIdentifier(string $table, string $driver): string
    {
        return $driver === 'pgsql' ? '"' . $table . '"' : '`' . $table . '`';
    }

    private function buildPostgresSequenceResetSql($connection, array $tables): string
    {
        $rows = $connection->select(
            "SELECT
                c.table_name,
                c.column_name,
                pg_get_serial_sequence(format('%I.%I', c.table_schema, c.table_name), c.column_name) AS sequence_name
             FROM information_schema.columns c
             WHERE c.table_schema = 'public'
             ORDER BY c.table_name, c.ordinal_position"
        );

        $sql = '';
        foreach ($rows as $row) {
            if (!in_array((string) $row->table_name, $tables, true)) {
                continue;
            }

            if (empty($row->sequence_name)) {
                continue;
            }

            $sequenceName = (string) $row->sequence_name;
            $tableName = (string) $row->table_name;
            $columnName = (string) $row->column_name;

            $sql .= "SELECT setval(\n";
            $sql .= "    '" . str_replace("'", "''", $sequenceName) . "',\n";
            $sql .= "    COALESCE((SELECT MAX(\"" . str_replace('"', '""', $columnName) . "\") FROM \"public\".\"" . str_replace('"', '""', $tableName) . "\"), 1),\n";
            $sql .= "    true\n";
            $sql .= ");\n\n";
        }

        return $sql;
    }

    private function getTableNames($connection, string $driver): array
    {
        $tables = [];

        if ($driver === 'mysql') {
            $tableRows = $connection->select('SHOW TABLES');
            $tables = array_map(function ($tableData) {
                return (string) array_values((array) $tableData)[0];
            }, $tableRows);
        } elseif ($driver === 'pgsql') {
            $tableRows = $connection->select("SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname = 'public' ORDER BY tablename");
            $tables = array_map(function ($tableData) {
                return (string) $tableData->tablename;
            }, $tableRows);
        } elseif ($driver === 'sqlite') {
            $tableRows = $connection->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name");
            $tables = array_map(function ($tableData) {
                return (string) $tableData->name;
            }, $tableRows);
        } else {
            throw new \RuntimeException('Unsupported database driver for backup: ' . $driver);
        }

        return $this->sortTablesByDependencies($connection, $driver, $tables);
    }

    private function sortTablesByDependencies($connection, string $driver, array $tables): array
    {
        if (count($tables) <= 1) {
            return $tables;
        }

        $dependencies = $this->getTableDependencies($connection, $driver, $tables);

        $inDegree = [];
        $adjacency = [];

        foreach ($tables as $table) {
            $inDegree[$table] = 0;
            $adjacency[$table] = [];
        }

        foreach ($dependencies as $table => $parents) {
            foreach ($parents as $parent) {
                if (!isset($inDegree[$parent]) || $parent === $table) {
                    continue;
                }

                $adjacency[$parent][$table] = true;
                $inDegree[$table]++;
            }
        }

        $queue = [];
        foreach ($tables as $table) {
            if ($inDegree[$table] === 0) {
                $queue[] = $table;
            }
        }

        sort($queue);
        $ordered = [];

        while (!empty($queue)) {
            $current = array_shift($queue);
            $ordered[] = $current;

            $children = array_keys($adjacency[$current]);
            sort($children);

            foreach ($children as $child) {
                $inDegree[$child]--;
                if ($inDegree[$child] === 0) {
                    $queue[] = $child;
                }
            }

            sort($queue);
        }

        if (count($ordered) !== count($tables)) {
            $remaining = array_values(array_diff($tables, $ordered));
            sort($remaining);
            $ordered = array_merge($ordered, $remaining);
        }

        return $ordered;
    }

    private function getTableDependencies($connection, string $driver, array $tables): array
    {
        $dependencies = [];
        foreach ($tables as $table) {
            $dependencies[$table] = [];
        }

        if ($driver === 'mysql') {
            $database = $connection->getDatabaseName();
            $rows = $connection->select(
                'SELECT table_name, referenced_table_name
                 FROM information_schema.key_column_usage
                 WHERE table_schema = ?
                   AND referenced_table_name IS NOT NULL',
                [$database]
            );

            foreach ($rows as $row) {
                $table = (string) $row->table_name;
                $parent = (string) $row->referenced_table_name;

                if (isset($dependencies[$table]) && in_array($parent, $tables, true)) {
                    $dependencies[$table][$parent] = $parent;
                }
            }
        } elseif ($driver === 'pgsql') {
            $rows = $connection->select(
                "SELECT tc.table_name, ccu.table_name AS referenced_table_name
                 FROM information_schema.table_constraints tc
                 JOIN information_schema.constraint_column_usage ccu
                   ON tc.constraint_name = ccu.constraint_name
                  AND tc.constraint_schema = ccu.constraint_schema
                 WHERE tc.constraint_type = 'FOREIGN KEY'
                   AND tc.table_schema = 'public'"
            );

            foreach ($rows as $row) {
                $table = (string) $row->table_name;
                $parent = (string) $row->referenced_table_name;

                if (isset($dependencies[$table]) && in_array($parent, $tables, true)) {
                    $dependencies[$table][$parent] = $parent;
                }
            }
        } elseif ($driver === 'sqlite') {
            foreach ($tables as $table) {
                $rows = $connection->select('PRAGMA foreign_key_list(' . $table . ')');

                foreach ($rows as $row) {
                    $parent = (string) $row->table;
                    if (in_array($parent, $tables, true)) {
                        $dependencies[$table][$parent] = $parent;
                    }
                }
            }
        }

        foreach ($dependencies as $table => $parents) {
            $dependencies[$table] = array_values($parents);
        }

        return $dependencies;
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

    private function extractBackupArchive(string $archivePath, string $tempDirectory): string
    {
        $zip = new ZipArchive();
        $openResult = $zip->open($archivePath);

        if ($openResult !== true) {
            throw new \RuntimeException('Unable to open backup archive.');
        }

        try {
            $databaseSqlPath = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);
                if ($entryName === false) {
                    continue;
                }

                $normalizedEntry = str_replace('\\', '/', $entryName);
                $normalizedEntry = ltrim($normalizedEntry, '/');

                if ($normalizedEntry === '' || str_contains($normalizedEntry, '../')) {
                    continue;
                }

                $isAllowed = $normalizedEntry === 'database.sql'
                    || $normalizedEntry === 'manifest.json'
                    || $this->isAllowedDirectoryEntry($normalizedEntry);

                if (!$isAllowed) {
                    continue;
                }

                $destinationPath = $tempDirectory . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalizedEntry);

                if (str_ends_with($normalizedEntry, '/')) {
                    File::ensureDirectoryExists($destinationPath);
                    continue;
                }

                File::ensureDirectoryExists(dirname($destinationPath));

                $entryContents = $zip->getFromIndex($i);
                if ($entryContents === false) {
                    throw new \RuntimeException('Unable to read backup archive entry: ' . $normalizedEntry);
                }

                File::put($destinationPath, $entryContents);

                if ($normalizedEntry === 'database.sql') {
                    $databaseSqlPath = $destinationPath;
                }
            }

            if ($databaseSqlPath === null || !file_exists($databaseSqlPath)) {
                throw new \RuntimeException('The backup archive does not contain database.sql.');
            }

            return $databaseSqlPath;
        } finally {
            $zip->close();
        }
    }

    private function isAllowedDirectoryEntry(string $entry): bool
    {
        foreach (self::RESTORE_DIRECTORIES as $directory) {
            if ($entry === $directory || str_starts_with($entry, $directory . '/')) {
                return true;
            }
        }

        return false;
    }

    private function restoreDatabaseFromSql(string $databaseSqlPath): void
    {
        $sql = File::get($databaseSqlPath);
        if (trim($sql) === '') {
            throw new \RuntimeException('Database SQL backup is empty.');
        }

        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'pgsql') {
            $this->restorePostgresSql($connection, $sql);
            $this->syncPostgresSequences($connection);
            return;
        }

        if ($driver === 'mysql') {
            $connection->unprepared('SET FOREIGN_KEY_CHECKS=0;');

            try {
                $connection->unprepared($sql);
            } finally {
                $connection->unprepared('SET FOREIGN_KEY_CHECKS=1;');
            }

            return;
        }

        $connection->unprepared($sql);
    }

    private function restorePostgresSql($connection, string $sql): void
    {
        $sequenceNames = $this->extractPostgresSequenceNames($sql);
        $normalizedSql = $this->qualifyPostgresRegclassReferences($sql);

        $connection->unprepared('SET search_path TO public;');

        $createMarker = '-- Create tables (parents first)';
        if (str_contains($normalizedSql, $createMarker)) {
            $parts = explode($createMarker, $normalizedSql, 2);
            $dropPhaseSql = $parts[0] ?? '';
            $createInsertSql = $parts[1] ?? '';

            if (trim($dropPhaseSql) !== '') {
                $connection->unprepared($dropPhaseSql);
            }

            $this->createPostgresSequences($connection, $sequenceNames);

            if (trim($createInsertSql) !== '') {
                $connection->unprepared($createMarker . $createInsertSql);
            }

            return;
        }

        $this->createPostgresSequences($connection, $sequenceNames);
        $connection->unprepared($normalizedSql);
    }

    private function extractPostgresSequenceNames(string $sql): array
    {
        preg_match_all("/nextval\('([^']+)'::regclass\)/", $sql, $matches);
        return array_values(array_unique($matches[1] ?? []));
    }

    private function createPostgresSequences($connection, array $sequenceNames): void
    {
        foreach ($sequenceNames as $sequenceName) {
            $connection->unprepared('CREATE SEQUENCE IF NOT EXISTS ' . $this->qualifyPostgresSequence($sequenceName) . ';');
        }
    }

    private function qualifyPostgresRegclassReferences(string $sql): string
    {
        return preg_replace_callback(
            "/nextval\('([^']+)'::regclass\)/",
            function (array $match): string {
                $qualified = $this->qualifyPostgresRegclassLiteral($match[1]);
                return "nextval('{$qualified}'::regclass)";
            },
            $sql
        ) ?? $sql;
    }

    private function qualifyPostgresSequence(string $sequenceName): string
    {
        $normalized = str_replace('"', '', trim($sequenceName));
        $parts = explode('.', $normalized, 2);

        if (count($parts) === 2) {
            return $this->quotePostgresIdentifier($parts[0]) . '.' . $this->quotePostgresIdentifier($parts[1]);
        }

        return $this->quotePostgresIdentifier('public') . '.' . $this->quotePostgresIdentifier($normalized);
    }

    private function quotePostgresIdentifier(string $identifier): string
    {
        return '"' . str_replace('"', '""', $identifier) . '"';
    }

    private function qualifyPostgresRegclassLiteral(string $sequenceName): string
    {
        $normalized = str_replace('"', '', trim($sequenceName));
        if (str_contains($normalized, '.')) {
            return $normalized;
        }

        return 'public.' . $normalized;
    }

    private function syncPostgresSequences($connection): void
    {
        $connection->unprepared(<<<'SQL'
DO $$
DECLARE r RECORD;
BEGIN
    FOR r IN
        SELECT
            n.nspname AS schema_name,
            c.relname AS table_name,
            a.attname AS column_name,
            pg_get_serial_sequence(format('%I.%I', n.nspname, c.relname), a.attname) AS sequence_name
        FROM pg_class c
        JOIN pg_namespace n ON n.oid = c.relnamespace
        JOIN pg_attribute a ON a.attrelid = c.oid
        WHERE c.relkind = 'r'
          AND n.nspname = 'public'
          AND a.attnum > 0
          AND NOT a.attisdropped
    LOOP
        IF r.sequence_name IS NOT NULL THEN
            EXECUTE format(
                'SELECT setval(%L, COALESCE((SELECT MAX(%I) FROM %I.%I), 0) + 1, false)',
                r.sequence_name,
                r.column_name,
                r.schema_name,
                r.table_name
            );
        END IF;
    END LOOP;
END $$;
SQL);
    }

    private function syncDirectory(string $sourceDirectory, string $destinationDirectory): void
    {
        if (!is_dir($sourceDirectory)) {
            return;
        }

        if (is_dir($destinationDirectory)) {
            $deleted = File::deleteDirectory($destinationDirectory);
            if ($deleted === false && is_dir($destinationDirectory)) {
                throw new \RuntimeException('Unable to clear destination directory: ' . $destinationDirectory);
            }
        }

        File::ensureDirectoryExists(dirname($destinationDirectory));

        $copied = File::copyDirectory($sourceDirectory, $destinationDirectory);
        if ($copied === false) {
            throw new \RuntimeException('Unable to restore directory: ' . basename($destinationDirectory));
        }
    }
}
