<?php

use Rappasoft\LaravelPatches\Patch;

return new class extends Patch {
    /**
     * List of environments the patch should run on
     *
     * @var array<string>
     */
    public array $envs = ['other-environment'];

    /**
     * Run the patch.
     *
     * @return void
     */
    public function up(): void
    {
        $this->log('Hello Fourth!');
    }

    /**
     * Reverse the patch.
     *
     * @return void
     */
    public function down(): void
    {
        \Log::info('Goodbye Fourth');
    }
};
