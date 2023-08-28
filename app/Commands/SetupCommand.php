<?php

namespace App\Commands;

use App\Contracts\JsonConfigContract;
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
     * @throws Exception
     */
    public function handle(JsonConfigContract $jsonConfigContract): void
    {
        $jsonConfigContract->setCreatable(true);

        $htaccessLocation = $this->ask(Emoji::thinkingFace() . ' Where is your public folder located?', $jsonConfigContract->get('public_path', './public'));

        $jsonConfigContract->set('public_path', $htaccessLocation);

        $jsonConfigContract->write();

        $this->info(Emoji::partyPopper() . ' htaccessor is ready to use! Please run `htaccessor edit` to configure your first environment.');
    }
}
