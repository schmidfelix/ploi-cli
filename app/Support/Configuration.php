<?php

namespace App\Support;

use Exception;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;

class Configuration
{
    /** @var array */
    protected $config;

    /** @var Ploi */
    protected $ploi;

    public function __construct(Ploi $ploi)
    {
        $this->ploi = $ploi;

        try {
            $this->config = Yaml::parseFile(getcwd() . '/ploi.yml');
        } catch (Exception $e) {
            $this->config = [];
        }
    }

    public function initialize(string $server, string $site, string $path, string $domain)
    {
        $configFile = $path . '/ploi.yml';

        $this->config = $this->getConfigFormat($server, $site, $domain);

        $this->store($configFile);
    }

    protected function getConfigFormat(string $server, string $site, string $domain)
    {
        return [
            'site' => $site,
            'server' => $server,
            'domain' => $domain,
        ];
    }

    public function store(string $configFile)
    {
        $configContent = Yaml::dump($this->config, 4, 2, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);

        file_put_contents($configFile, $configContent);
    }

    public function get(string $key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

    public function set(string $key, $value)
    {
        Arr::set($this->config, $key, $value);
    }
}
