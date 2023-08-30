<?php

namespace App\Commands;

use App\Contracts\JsonConfigServiceContract;
use Exception;
use LaravelZero\Framework\Commands\Command;
use Spatie\Emoji\Emoji;

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
     * @param JsonConfigServiceContract $jsonConfig
     * @return int
     */
    public function handle(JsonConfigServiceContract $jsonConfig): int
    {
        $jsonConfig->setCreatable(true);

        $htaccessLocation = $this->ask(Emoji::thinkingFace() . ' Where is your public folder located?', $jsonConfig->get('public_path', './public'));

        $jsonConfig->put('public_path', $htaccessLocation);

        if ($jsonConfig->write()) {
            $this->info(Emoji::partyPopper() . ' htaccessor is ready to use! Please run `htaccessor edit` to configure your first environment.');
            return self::SUCCESS;
        }

        return self::FAILURE;
    }
}
