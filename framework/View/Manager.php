<?php

namespace Framework\View;

use Exception;
use Framework\View\Engine\Engine;
use Framework\View\Engine\View;

class Manager
{
    /**
     * @var array
     */
    protected array $paths = [];

    /**
     * @var array
     */
    protected array $engines = [];

    /**
     * @var array 
     */
    protected array $macros = [];

    /**
     * @param string $extension
     * @param Engine $engine
     * @return $this
     */
    public function addEngine(string $extension, Engine $engine): static
    {
        $this->engines[$extension] = $engine;
        $this->engines[$extension]->setManager($this);
        return $this;
    }

    /**
     * @param string $resourcesPath
     * @return $this
     */
    public function addPath(string $resourcesPath): static
    {
        $this->paths[] = $resourcesPath;
        return $this;
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function resolve(string $template, array $data = []): string
    {
        $template = str_replace('.', '/', $template);

        foreach ($this->engines() as $extension => $engine) {
            foreach ($this->paths() as $path) {
                $file = "{$path}/{$template}.{$extension}";
                if (is_file($file)) {
                    return new View($engine, realpath($file), $data);
                }
            }
        }

        throw new Exception("Could not render '{$template}'");
    }

    /**
     * @param string $name
     * @param \Closure $closure
     * @return $this
     */
    public function addMacro(string $name, \Closure $closure): static
    {
        $name = trim($name);
        $this->macros[$name] = $closure;
        return $this;
    }

    /**
     * @param string $name
     * @param ...$values
     * @return mixed
     * @throws Exception
     */
    public function useMacro(string $name, ...$values): mixed
    {
        $name = trim($name);
        if (isset($this->macros[$name])) {
            $bound = $this->macros[$name]->bindTo($this);
            return $bound(...$values);
        }

        throw new Exception("Macro isn't defined: '{$name}'");
    }

    /**
     * @return array
     */
    public function paths(): array
    {
        return $this->paths;
    }

    /**
     * @return array
     */
    public function engines(): array
    {
        return $this->engines;
    }



}