<?php

namespace Rappasoft\LaravelPatches\Tests\Commands;

use Illuminate\Support\Facades\Log;
use Rappasoft\LaravelPatches\Tests\TestCase;

class RollbackTest extends TestCase
{
    /** @test */
    public function it_rollsback_a_patch()
    {
        Log::shouldReceive('info')->with('Goodbye First');

        file_put_contents(
            database_path('patches/2021_01_01_000000_my_first_patch.php'),
            file_get_contents(__DIR__.'/patches/2021_01_01_000000_my_first_patch.php')
        );

        $this->assertDatabaseCount(config('laravel-patches.table_name'), 0);

        $this->artisan('patch')->run();

        $this->assertDatabaseCount(config('laravel-patches.table_name'), 1);

        $this->assertDatabaseHas(config('laravel-patches.table_name'), [
            'patch' => '2021_01_01_000000_my_first_patch',
            'batch' => 1,
        ]);

        $this->artisan('patch:rollback')->run();

        $this->assertDatabaseCount(config('laravel-patches.table_name'), 0);

        $this->assertDatabaseMissing(config('laravel-patches.table_name'), [
            'patch' => '2021_01_01_000000_my_first_patch',
            'batch' => 1,
        ]);
    }

    /** @test */
    public function it_rollsback_all_patches_of_the_previous_batch()
    {
        Log::shouldReceive('info')->with('Goodbye First');
        Log::shouldReceive('info')->with('Goodbye Second');

        file_put_contents(
            database_path('patches/2021_01_01_000000_my_first_patch.php'),
            file_get_contents(__DIR__.'/patches/2021_01_01_000000_my_first_patch.php')
        );

        file_put_contents(
            database_path('patches/2021_01_02_000000_my_second_patch.php'),
            file_get_contents(__DIR__.'/patches/2021_01_02_000000_my_second_patch.php')
        );

        file_put_contents(
            database_path('patches/2021_01_04_000000_my_fourth_patch.php'),
            file_get_contents(__DIR__.'/patches/2021_01_04_000000_my_fourth_patch.php')
        );

        $this->artisan('patch')->run();

        $this->assertDatabaseCount(config('laravel-patches.table_name'), 2);

        $this->artisan('patch:rollback')->run();

        $this->assertDatabaseCount(config('laravel-patches.table_name'), 0);
    }

    /** @test */
    public function it_rollsback_the_correct_patches_with_step()
    {
        Log::shouldReceive('info')->once();

        file_put_contents(
            database_path('patches/2021_01_01_000000_my_first_patch.php'),
            file_get_contents(__DIR__.'/patches/2021_01_01_000000_my_first_patch.php')
        );

        file_put_contents(
            database_path('patches/2021_01_02_000000_my_second_patch.php'),
            file_get_contents(__DIR__.'/patches/2021_01_02_000000_my_second_patch.php')
        );

        $this->artisan('patch')->run();

        $this->assertDatabaseHas(config('laravel-patches.table_name'), [
            'patch' => '2021_01_01_000000_my_first_patch',
            'batch' => 1,
        ]);

        $this->assertDatabaseHas(config('laravel-patches.table_name'), [
            'patch' => '2021_01_02_000000_my_second_patch',
            'batch' => 1,
        ]);

        $this->artisan('patch:rollback', ['--step' => 1])->run();

        $this->assertDatabaseHas(config('laravel-patches.table_name'), [
            'patch' => '2021_01_01_000000_my_first_patch',
            'batch' => 1,
        ]);

        $this->assertDatabaseMissing(config('laravel-patches.table_name'), [
            'patch' => '2021_01_02_000000_my_second_patch',
            'batch' => 1,
        ]);
    }
}
