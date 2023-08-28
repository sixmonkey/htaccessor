<?php

namespace App\Services;

use App\Contracts\JsonConfigContract;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class JsonConfigService implements JsonConfigContract
{
    /**
     * @var bool
     */
    protected bool $exists = false;

    /**
     * @var bool $creatable
     */
    protected bool $creatable = false;

    /**
     * @var Collection $config
     */
    protected Collection $config;

    /**
     * JsonConfigService constructor.
     * @throws Exception
     */
    public function __construct()
    {
        $this->exists = Storage::exists('htaccessor.json');

        $this->config = collect($this->exists ? json_decode(Storage::get('htaccessor.json'), true) : []);
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
     * @return void
     * @throws Exception
     */
    public function set(string $key, $value): void
    {
        if (!$this->exists && !$this->creatable) {
            throw new Exception('htaccessor.json does not exist. Please run "htaccessor setup" first.');
        }
        $this->config->put($key, $value);
    }

    /**
     * @return bool
     */
    public function write(): bool
    {
        try {
            return Storage::put('htaccessor.json', json_encode($this->config, JSON_PRETTY_PRINT));
        } catch (Exception $exception) {
            echo $exception->getMessage();
            return false;
        }
    }
}
