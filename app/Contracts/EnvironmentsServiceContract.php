<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface EnvironmentsServiceContract
{
    public function all(): Collection;

    public function allKeys(): Collection;

    public function exists(string $key): bool;

    public function put(string $key, Collection $value): Collection;

    public function get(string $key, mixed $default = null): mixed;
}
