<?php

namespace App\Helpers;

use App\Contracts\EnvironmentsMenuHelperContract;
use App\Contracts\EnvironmentsServiceContract;
use Illuminate\Support\Facades\Validator;
use NunoMaduro\LaravelConsoleMenu\Menu;
use PhpSchool\CliMenu\CliMenu;
use Spatie\Emoji\Emoji;

class EnvironmentsMenuHelper implements EnvironmentsMenuHelperContract
{

    /**
     * @param EnvironmentsServiceContract $environmentsService
     */
    public function __construct(protected EnvironmentsServiceContract $environmentsService)
    {
    }

    /**
     * @param boolean|true $addable
     * @return string|null
     */
    public function selectEnvironment(bool $addable = true): ?string
    {
        $environments = $this->environmentsService->allKeys();
        $menu = new Menu('Please choose an environment', array_combine($environments->toArray(), $environments->toArray())); // TODO: Awhy do we need array_combine here? Seems like a total hack.

        $menu->setForegroundColour('green')
            ->setBackgroundColour('black');

        if (count($environments)) {
            $menu->addLineBreak();
        }

        if ($addable) {
            $itemCallable = function (CliMenu $cliMenu) use ($menu) {
                $newEnv = $cliMenu
                    ->askText()
                    ->setValidator(function ($environmentName) {
                        $validator = Validator::make(['environmentName' => $environmentName], ['environmentName' => 'required|lowercase|alpha_dash']);
                        return $validator->passes();
                    })
                    ->setPromptText('What is your new environment\'s name?')
                    ->setValidationFailedText(Emoji::pileOfPoo() . " Must be lowercase and only contain letters, numbers, dashes and underscores.")
                    ->ask();

                $menu->setResult($newEnv->fetch());

                $cliMenu->close();
            };
            $menu->addItem('🪄  Add a new environment', $itemCallable);
        }

        $menu->setExitButtonText('👋 Exit');

        return $menu->open();
    }
}
