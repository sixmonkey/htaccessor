<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface BuildersServiceContract
{
    public function all(): Collection;
}
