<?php

namespace App\Commands;

use App\Contracts\BuildersMenuHelperContract;
use App\Contracts\BuildersServiceContract;
use App\Contracts\EnvironmentsMenuHelperContract;
use App\Contracts\EnvironmentsServiceContract;
use App\Contracts\JsonConfigServiceContract;
use Exception;
use LaravelZero\Framework\Commands\Command;
use Spatie\Emoji\Emoji;

class DeleteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'delete {environment?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Delete an environment completely.';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(
        JsonConfigServiceContract      $jsonConfig,
        EnvironmentsServiceContract    $environmentsService,
        EnvironmentsMenuHelperContract $environmentsMenuHelper
    ): int
    {
        $environments = $environmentsService->all();
        if (!$environments->count()) {
            $this->error(Emoji::pileOfPoo() . " You don't have any environments yet.");
            return self::FAILURE;
        }

        $environmentName = $this->argument('environment') ?? $environmentsMenuHelper->selectEnvironment(false);

        if (!$environments->has($environmentName)) {
            $this->error(Emoji::pileOfPoo() . " Environment $environmentName does not exist.");
            return self::FAILURE;
        }

        if ($environmentName === null || $this->ask("Are you sure you want to delete environment $environmentName? Please wirte down the name of your environment to do this! [$environmentName]") !== $environmentName) {
            $this->info(Emoji::wavingHand() . ' Have a nice day!');
            return self::INVALID;
        }

        $environments->forget($environmentName);

        $jsonConfig->put('environments', $environments->sortKeys()->toArray());

        if ($jsonConfig->write()) {
            $this->info(Emoji::cryingCat() . " deleted environment $environmentName!");

            return self::SUCCESS;
        }

        $this->error(Emoji::pileOfPoo() . " Something went wrong while writing your settings.");
        return self::FAILURE;
    }
}
