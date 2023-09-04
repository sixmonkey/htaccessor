<?php

namespace App\Contracts;

interface EnvironmentsMenuHelperContract
{
    public function selectEnvironment(bool $addable): ?string;
}
