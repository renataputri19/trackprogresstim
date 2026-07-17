<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Symfony\Component\Process\Process;

/**
 * Seeds the database from the bundled production dump
 * (database/seed/init.sql or init.sql.gz).
 *
 * It shells out to the mysql/mariadb client, which streams the dump
 * statement-by-statement — the reliable way to load a full mysqldump (running
 * a 17 MB file through PDO::exec would blow past max_allowed_packet).
 *
 * The container's entrypoint already does this automatically on a fresh
 * database; running `php artisan db:seed` reproduces it locally.
 */
class SqlDumpSeeder extends Seeder
{
    public function run(): void
    {
        $dump = self::dumpPath();
        if ($dump === null) {
            $this->command?->warn('No dump at database/seed/init.sql[.gz] — skipping SqlDumpSeeder.');
            return;
        }

        $client = self::clientBinary();
        if ($client === null) {
            $this->command?->warn('No mysql/mariadb client on PATH — cannot import the dump.');
            return;
        }

        $cfg = config('database.connections.'.config('database.default'));

        $client = escapeshellarg($client)
            .' --host='.escapeshellarg($cfg['host'] ?? '127.0.0.1')
            .' --port='.escapeshellarg((string) ($cfg['port'] ?? '3306'))
            .' --user='.escapeshellarg($cfg['username'] ?? 'root')
            .' '.escapeshellarg($cfg['database']);

        $command = str_ends_with($dump, '.gz')
            ? 'gzip -dc '.escapeshellarg($dump).' | '.$client
            : $client.' < '.escapeshellarg($dump);

        $this->command?->info("Importing seed dump: {$dump}");

        // Password via MYSQL_PWD so it never appears in the process arguments.
        $process = Process::fromShellCommandline($command, base_path(), [
            'MYSQL_PWD' => (string) ($cfg['password'] ?? ''),
        ]);
        $process->setTimeout(600);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new \RuntimeException('Seed import failed: '.$process->getErrorOutput());
        }

        $this->command?->info('Seed import finished.');
    }

    /** Absolute path to the bundled dump, or null if none is present. */
    public static function dumpPath(): ?string
    {
        foreach (['database/seed/init.sql', 'database/seed/init.sql.gz'] as $rel) {
            $path = base_path($rel);
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function dumpExists(): bool
    {
        return self::dumpPath() !== null;
    }

    private static function clientBinary(): ?string
    {
        $probe = PHP_OS_FAMILY === 'Windows' ? 'where' : 'command -v';
        $null = PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null';

        foreach (['mariadb', 'mysql'] as $bin) {
            if (! empty(trim((string) @shell_exec("{$probe} {$bin} 2>{$null}")))) {
                return $bin;
            }
        }

        return null;
    }
}
