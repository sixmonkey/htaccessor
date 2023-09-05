<?php

namespace App\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeBuilder extends GeneratorCommand
{

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $name = 'make:builder';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Create a new builder';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Builder';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws FileNotFoundException
     */
    public function handle(): ?bool
    {
        if (parent::handle() === false && !$this->option('force')) {
            return false;
        }

        $this->makeView();

        $this->addToConfig();

        return true;
    }

    /**
     * Make the view for this builder.
     *
     * @return void
     */
    protected function makeView(): void
    {
        $name = Str::kebab($this->getNameInput());
        Storage::put("resources/views/builders/$name.blade.php", '');
    }

    public function addToConfig(): void
    {
    }

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
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Builders';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the builder already exists'],
        ];
    }
}
