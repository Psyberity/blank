<?php
namespace Modules;

class ModuleBase
{
    protected $namespaces;
    protected $moduleDir;
    protected $config;

    public function __construct()
    {
        $this->config = include __DIR__ . '/' . $this->moduleDir . '/config/config.php';
        $this->setNamespaces($this->config);
    }

    protected function setNamespaces($config)
    {
        foreach ($config->namespaces as $namespace => $dir) {
            $this->namespaces[$namespace] = $config->application->$dir;
        }
    }
}