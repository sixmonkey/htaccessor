<?php

namespace App\Commands;

use App\Contracts\BuildersMenuHelperContract;
use App\Contracts\EnvironmentsMenuHelperContract;
use App\Contracts\EnvironmentsServiceContract;
use App\Contracts\JsonConfigServiceContract;
use Exception;
use LaravelZero\Framework\Commands\Command;
use Spatie\Emoji\Emoji;

class EditCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'edit {environment?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Edit your htaccess settings for an environment.';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(
        JsonConfigServiceContract      $jsonConfig,
        EnvironmentsServiceContract    $environmentsService,
        EnvironmentsMenuHelperContract $environmentsMenuHelper,
        BuildersMenuHelperContract     $buildersMenuHelper
    ): int
    {
        $environments = $environmentsService->all();

        $environmentName = $this->argument('environment') ?? $environmentsMenuHelper->selectEnvironment();

        if ($environmentName === null) {
            $this->info(Emoji::wavingHand() . ' Have a nice day!');
            return self::INVALID;
        }

        $builders = $buildersMenuHelper->selectBuilders($environmentName);

        $builders->each(function ($options, $builderName) use (&$builders, $buildersMenuHelper) {
            $builder = new $builderName($options, app(EnvironmentsServiceContract::class));
            if (method_exists($builder, 'configure')) {
                $builder->setOutput($this->output);
                $builder->setInput($this->input);
                $this->info(Emoji::writingHand() . " Configuring $builder...");
                $builders->put($builderName, $builder->configure());
            }
        });

        $environments->put($environmentName, $builders);

        $jsonConfig->put('environments', $environments->sortKeys()->toArray());

        if ($jsonConfig->write()) {
            $this->info(sprintf(Emoji::partyPopper() . " %s settings for environment %s!", $environmentsService->wasCreated() ? 'created' : 'updated', $environmentName));

            return self::SUCCESS;
        }

        $this->error(Emoji::pileOfPoo() . " Something went wrong while writing your settings.");
        return self::FAILURE;
    }
}
