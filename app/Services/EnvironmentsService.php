<?php

namespace App\Services;

use App\Contracts\EnvironmentsServiceContract;
use App\Contracts\JsonConfigServiceContract;
use Illuminate\Support\Collection;

class EnvironmentsService implements EnvironmentsServiceContract
{
    /**
     * @var bool
     */
    protected bool $created = false;

    /**
     * @param JsonConfigServiceContract $jsonConfig
     */
    public function __construct(protected JsonConfigServiceContract $jsonConfig)
    {
    }

    /**
     * @return Collection
     */
    protected function getEnvironments(): Collection
    {
        return collect($this->jsonConfig->get('environments'))->sortKeys();
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->getEnvironments();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->getEnvironments()->has($key);
    }

    /**
     * @param string $key
     * @param Collection $value
     * @return Collection
     */
    public function put(string $key, Collection $value): Collection
    {
        $this->created = !$this->exists($key);

        return $this->getEnvironments()->put($key, $value);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return Collection
     */
    public function get(string $key, mixed $default = null): Collection
    {
        return collect($this->getEnvironments()->get($key, $default));
    }

    /**
     * @return Collection
     */
    public function allKeys(): Collection
    {
        return $this->getEnvironments()->keys();
    }

    /**
     * @return bool
     */
    public function write(): bool
    {
        $this->jsonConfig->put('environments', $this->getEnvironments()->sortKeys()->toArray());
        return $this->jsonConfig->write();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->getEnvironments()->count();
    }

    /**
     * @return bool
     */
    public function wasCreated(): bool
    {
        return $this->created;
    }
}
