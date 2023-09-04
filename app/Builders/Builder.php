<?php

namespace App\Builders;

use App\Contracts\EnvironmentsServiceContract;
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

abstract class Builder
{

    use InteractsWithIO;

    public static int $position = 99;

    public static bool $needsModRewrite = false;

    public function __construct(
        protected Collection|array            $options,
        protected EnvironmentsServiceContract $environmentsService,
    )
    {
    }

    /**
     * @return string
     */
    abstract static function getTitle(): string;

    /**
     * @return Application|string|Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View
     * @throws Throwable
     */
    public function build(): Application|string|Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View
    {
        $result = view('builders.' . Str::kebab(class_basename(preg_replace('/Builder$/', '', static::class))), $this->options);

        return $result instanceof View ? $result->render() : '';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return static::getTitle();
    }
}
