<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use function Termwind\{render};

class SetupCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'setup';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Setup all basics for htaccessor.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        render(<<<'HTML'
            <div class="py-1 ml-2">
                <div class="px-1 bg-blue-300 text-black">Laravel Zero</div>
                <em class="ml-1">
                  Simplicity is the ultimate sophistication.
                </em>
            </div>
        HTML);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
