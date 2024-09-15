<?php

use Rappasoft\LaravelPatches\Patch;

return new class extends Patch {
    /**
     * List of environments the patch should run on
     *
     * @var array
     */
    public array $envs = ['testing', 'other_environment'];

    /**
     * Run the patch.
     *
     * @return void
     */
    public function up(): void
    {
        $this->log('Hello Third!');
    }

    /**
     * Reverse the patch.
     *
     * @return void
     */
    public function down(): void
    {
        \Log::info('Goodbye Third');
    }
};
