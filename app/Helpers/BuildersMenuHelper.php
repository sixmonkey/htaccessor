<?php

namespace App\Helpers;

use App\Contracts\BuildersMenuHelperContract;
use App\Contracts\BuildersServiceContract;
use App\Contracts\EnvironmentsServiceContract;
use PhpSchool\CliMenu\Builder\CliMenuBuilder;
use PhpSchool\CliMenu\CliMenu;
use PhpSchool\CliMenu\Exception\InvalidTerminalException;
use Illuminate\Support\Collection;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;

class BuildersMenuHelper implements BuildersMenuHelperContract
{
    /**
     * @param BuildersServiceContract $buildersService
     * @param EnvironmentsServiceContract $environmentsService
     */
    public function __construct(
        protected BuildersServiceContract     $buildersService,
        protected EnvironmentsServiceContract $environmentsService
    )
    {
    }

    /**
     * @param string $environmentKey
     * @return Collection
     * @throws InvalidTerminalException
     */
    public function selectBuilders(string $environmentKey): Collection
    {
        $builders = $this->buildersService->all();

        $selectedBuilders = $this->environmentsService->get($environmentKey, collect());

        $menu = (new CliMenuBuilder)
            ->setTitle('Select Builders')
            ->setMarginAuto();

        $builders->each(function ($builder, $key) use ($menu, $selectedBuilders) {
            $checkboxItem = new CheckboxItem(
                $builder,
                fn(CliMenu $menu) => $menu->getSelectedItem()->getChecked() ? $selectedBuilders->put($key, $this->environmentsService->get($key, [])) : $selectedBuilders->forget($key),
            );

            if ($selectedBuilders->has($key)) {
                $checkboxItem->setChecked();
            }

            $menu->addMenuItem(
                $checkboxItem
            );
        });

        $menu
            ->setForegroundColour('green')
            ->setBackgroundColour('black')
            ->addLineBreak()
            ->setExitButtonText('✅  Save changes');

        $menu->build()->open();

        return $selectedBuilders;
    }
}
