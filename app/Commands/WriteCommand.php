<?php

namespace App\Commands;

use App\Contracts\BuildersMenuHelperContract;
use App\Contracts\BuildersServiceContract;
use App\Contracts\EnvironmentsMenuHelperContract;
use App\Contracts\EnvironmentsServiceContract;
use App\Contracts\JsonConfigServiceContract;
use Exception;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use Spatie\Emoji\Emoji;

class WriteCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'write {environment?}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Writes the .htaccess file for the given environment.';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(
        EnvironmentsServiceContract    $environmentsService,
        EnvironmentsMenuHelperContract $environmentsMenuHelper,
        JsonConfigServiceContract      $jsonConfig,
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

        $environment = $environmentsService->get($environmentName);

        $this->info(Emoji::writingHand() . " Writing .htaccess file for environment $environmentName...");

        $output = [
            'mod_rewrite_directives' => [],
            'directives' => [],
        ];


        $environment
            ->sortBy(fn($options, $builder) => class_exists($builder) ? ($builder)::$position : 999)
            ->each(function ($options, $builder) use (&$output) {
                if(!class_exists($builder)) {
                    $this->warn(Emoji::pileOfPoo() . " Builder $builder does not exist.");
                    return;
                }
                $this->info(Emoji::magicWand() . " Executing builder $builder...");

                $builder = app($builder, ['options' => $options]);

                $output[$builder::$requiresModRewrite ? 'mod_rewrite_directives' : 'directives'][] = $builder->write();
            });

        Storage::put(
            $jsonConfig->get('public_path') . DIRECTORY_SEPARATOR . '.htaccess',
            view('htaccess-base', $output)->render()
        );

        $this->info(Emoji::writingHand() . " Done!");

        return self::SUCCESS;
    }
}
