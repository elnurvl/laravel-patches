<?php

namespace Rappasoft\LaravelPatches;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

/**
 * Class Patch
 *
 * @package Rappasoft\LaravelPatches
 */
abstract class Patch
{

    /**
     * Call an Artisan command
     *
     * @param $command
     * @param  array  $parameters
     *
     * @return int
     */
    public function call($command, array $parameters = []): int
    {
        return Artisan::call($command, $parameters);
    }

    /**
     * Call a seeder class by name
     *
     * @param string $class
     *
     * @return int
     */
    public function seed(string $class): int
    {
        return $this->call('db:seed', ['--class' => $class]);
    }

    /**
     * Truncate a table by name
     *
     * @param string $table
     */
    public function truncate(string $table): void
    {
        DB::table($table)->truncate();
    }
}
