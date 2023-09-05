<?php

namespace App\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeBuilder extends GeneratorCommand
{

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'app:make:builder {name}';

    /**
     * @inheritDoc
     */
    protected function getStub(): string
    {
        return app_path('Stubs/Builder.stub');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Builders';
    }
}
