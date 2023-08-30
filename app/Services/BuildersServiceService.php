<?php

namespace App\Services;

use App\Contracts\BuildersServiceContract;
use Illuminate\Support\Collection;

class BuildersServiceService implements BuildersServiceContract
{

    /**
     * @param Collection $builders
     */
    public function __construct(protected Collection $builders)
    {
        collect(config('builders', []))->each(fn($builder) => $this->builders->put($builder, $builder::getTitle()));
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->builders;
    }
}
