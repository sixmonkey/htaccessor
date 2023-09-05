<?php

namespace App\Builders\Base;

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

    public static bool $requiresModRewrite = false;

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
     * @return true
     */
    protected function beforeWrite(): bool
    {
        return true;
    }

    /**
     * @return Application|string|Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View
     * @throws Throwable
     */
    final public function write(): Application|string|Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View
    {
        if(!$this->beforeWrite()) {
            return '';
        }

        $result = view('builders.' . Str::kebab(class_basename(preg_replace('/Builder$/', '', static::class))), $this->options);

        return $this->afterWrite($result instanceof View ? $result->render() : '');
    }

    /**
     * @param string $result
     * @return string
     */
    protected function afterWrite(string $result): string
    {
        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return static::getTitle();
    }
}
