<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface JsonConfigServiceContract
{
    public function exists(): bool;

    public function put(string $key, mixed $value): Collection;

    public function get(string $key, mixed $default = null): mixed;

    public function write(): bool;
}
