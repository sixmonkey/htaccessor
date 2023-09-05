<?php

namespace App\Services;

use App\Contracts\BuildersServiceContract;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use Illuminate\Support\Collection;

class BuildersServiceService implements BuildersServiceContract
{

    /**
     * @param Collection $builders
     * @throws Exception
     */
    public function __construct(protected Collection $builders)
    {
        ClassFinder::disablePSR4Vendors();
        collect(ClassFinder::getClassesInNamespace('App\Builders') ?? [])
            ->each(fn($builder) => $this->builders->put($builder, $builder::getTitle()));
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->builders;
    }
}
