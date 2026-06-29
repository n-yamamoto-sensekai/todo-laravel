<?php

namespace App\Providers;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DatabaseQueryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (! config('logging.sql.enable')) {
            return;
        }

        DB::listen(function ($query): void {
            $sql = $query->sql;

            if ($this->isQueueSql($sql) && ! config('logging.queue.sql.enable')) {
                return;
            }

            $bindings = $this->formatBindings($query->bindings);
            $realSql = preg_replace_array('/\?/', $bindings, $sql);
            $realSql = preg_replace('/\t/', '', $realSql);

            Log::debug('SQL', [
                'sql' => "{$realSql};\n",
                'time' => "{$query->time} ms",
            ]);
        });

        Event::listen(TransactionBeginning::class, function (TransactionBeginning $event): void {
            // Log::debug('START TRANSACTION');
        });

        Event::listen(TransactionCommitted::class, function (TransactionCommitted $event): void {
            // Log::debug('COMMIT');
        });

        Event::listen(TransactionRolledBack::class, function (TransactionRolledBack $event): void {
            Log::debug('ROLLBACK');
        });
    }

    private function formatBindings(array $bindings): array
    {
        array_walk_recursive($bindings, function (&$value): void {
            if (is_string($value)) {
                $value = "'" . str_replace("'", "''", $value) . "'";
            } elseif (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            } elseif ($value instanceof Carbon) {
                $value = "'{$value->toDateTimeString()}'";
            } elseif ($value instanceof DateTimeInterface) {
                $value = "'{$value->format('Y-m-d H:i:s')}'";
            } elseif ($value === null) {
                $value = 'NULL';
            }
        });

        return $bindings;
    }

    private function isQueueSql(string $sql): bool
    {
        return str_contains($sql, 'queue_jobs')
            || preg_match('/\b(from|into|update|join)\s+[`"\[]?(jobs|job_batches|failed_jobs)[`"\]]?/i', $sql) === 1;
    }
}
