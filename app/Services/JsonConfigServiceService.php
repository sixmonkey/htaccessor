<?php

namespace App\Services;

use App\Contracts\JsonConfigServiceContract;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class JsonConfigServiceService implements JsonConfigServiceContract
{
    /**
     * @var bool
     */
    protected bool $exists = false;

    /**
     * @var bool
     */
    protected bool $created = false;

    /**
     * @var bool $creatable
     */
    protected bool $creatable = false;

    /**
     * @var Collection $config
     */
    protected Collection $config;

    protected static string $configFilename = '.htaccessor.json';

    /**
     * JsonConfigServiceService constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->exists = Storage::exists(self::$configFilename);

        $this->config = collect($this->exists ? json_decode(Storage::get(self::$configFilename), true) : []);
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->exists;
    }

    /**
     * @param bool $creatable
     * @return void
     */
    public function setCreatable(bool $creatable): void
    {
        $this->creatable = $creatable;
    }

    /**
     * @param string $key
     * @param $default
     * @return mixed|null
     * @throws Exception
     */
    public function get(string $key, $default = null): mixed
    {
        if (!$this->exists && !$this->creatable) {
            throw new Exception('htaccessor.json does not exist. Please run "htaccessor setup" first.');
        }

        return $this->config->get($key, $default);
    }

    /**
     * @param string $key
     * @param $value
     * @return Collection
     * @throws Exception
     */
    public function put(string $key, $value): Collection
    {
        if (!$this->exists && !$this->creatable) {
            throw new Exception('htaccessor.json does not exist. Please run "htaccessor setup" first.');
        }
        return $this->config->put($key, $value);
    }

    /**
     * @return bool
     */
    public function write(): bool
    {
        try {
            $this->created = !$this->exists;
            return Storage::put(self::$configFilename, $this->config->toJson(JSON_PRETTY_PRINT));
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    public function wasCreated(): bool
    {
        return $this->created;
    }
}
