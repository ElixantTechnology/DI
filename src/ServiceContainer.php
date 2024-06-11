<?php
/**
 * Elixant Platform Framework Component
 *
 * Elixant Platform
 * Copyright (c) 2023 Elixant Corporation.
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit
 * persons to whom the Software is furnished to do so.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 *
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @copyright    2023 (C) Elixant Corporation.
 * @license      MIT License
 * @author       Alexander Schmautz <a.schmautz@outlook.com>
 */
declare(strict_types = 1);

namespace Elixant\DI;

use Elixant\Utility\Arr;
use Illuminate\Container\Container;

/**
 * ServiceContainer Class
 *
 *  This class serves as a container for managing and resolving dependencies
 *  between objects in your application. It allows you to define and register
 *  services, and then retrieve them through the container when needed.
 *
 * @package         Elixant/DI
 * @copyright       2024 (c) Elixant Corporation.
 * @license         MIT License
 * @author          Alexander M. Schmautz <a.schmautz91@gmail.com>
 * @class
 */
class ServiceContainer extends Container
{
    protected array $services = [];
    
    public function register(string|ServiceDefinition $service): void
    {
        if (is_string($service) && class_exists($service)) {
            $service = new $service($this);
            
            if (! $service instanceof ServiceDefinition) {
                throw new \InvalidArgumentException(
                    'Service must be an instance of ServiceDefinition'
                );
            }
        }
        
        if (! Arr::has($this->services, $service->getName())) {
            $this->services[$service->getName()] = &$service;
        }
        
        if (method_exists($service, 'register')) {
            $service->register();
        }
        
        if (! empty($bindings = $service->getBindings())) {
            foreach ($bindings as $key => $value) {
                $this->bind($key, $value);
            }
        }
        
        if (! empty($instances = $service->getInstances())) {
            foreach ($instances as $key => $value) {
                $this->instance($key, $value);
            }
        }
    }
    
    public function buildServices(): void
    {
        foreach ($this->services as $service) {
            $service->build();
        }
    }
}
