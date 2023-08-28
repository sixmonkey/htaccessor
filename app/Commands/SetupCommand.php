<?php

namespace App\Commands;

use App\Contracts\JsonConfigContract;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Spatie\Emoji\Emoji;
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
     * @throws Exception
     */
    public function handle(JsonConfigContract $jsonConfigContract): void
    {
        $jsonConfigContract->setCreatable(true);

        $htaccessLocation = $this->ask(Emoji::thinkingFace() . ' Where is your .htaccess file located?', $jsonConfigContract->get('htaccess_location', './public/.htaccess'));

        $jsonConfigContract->set('htaccess_location', $htaccessLocation);

        $jsonConfigContract->write();
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
