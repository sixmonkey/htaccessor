<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface BuildersMenuHelperContract
{
    public function selectBuilders(string $environmentKey): Collection;
}
