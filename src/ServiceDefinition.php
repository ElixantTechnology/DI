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

/**
 * Class ServiceDefinition
 *
 * The abstract class ServiceDefinition is the base class for defining service definitions.
 * It provides basic functionality for managing bindings, instances, listeners, and middlewares.
 *
 * @package Your\Namespace
 */
abstract class ServiceDefinition
{
    /**
     * Array containing all bindings for the service, that are to be resolved
     * within the container.
     *
     * @var array $_bindings
     */
    protected static array $_bindings    = [];
    
    /**
     * Array containing all resolved instances of the services, that are to be
     * bound within the container.
     *
     * @var array $_instances
     */
    protected static array $_instances   = [];
    
    /**
     * Array containing all listeners for the service, that are to be resolved
     * within the event dispatcher.
     *
     * @var array $_listeners
     */
    protected static array $_listeners   = [];
    
    /**
     * Array containing all middlewares for the service, that are to be resolved
     * within the bootloader.
     *
     * @var array $_middlewares
     */
    protected static array $_middlewares = [];
    
    /**
     * Class constructor.
     *
     * @param ServiceContainer $container The service container instance.
     */
    public function __construct(
        protected ServiceContainer $container
    ) {}
    
    abstract public function build(): void;
    
    /**
     * Binds a key to a value or a callback function in the service container.
     *
     * @param string|array               $key   The key or an array of key-value pairs to bind.
     * @param string|array|\Closure|null $value The value or callback function to bind to the key.
     *
     * @return void
     */
    protected function bind(string|array $key, string|array|\Closure|null $value = null): void
    {
        if (is_array($key) && is_null($value)) {
            foreach ($key as $k => $v) {
                self::$_bindings[$k] = $v;
            }
        } else {
            self::$_bindings[$key] = $value;
        }
    }
    
    protected function instance(string $abstract, object $instance): void
    {
        self::$_instances[$abstract] = $instance;
    }
    
    public function getInstances(): array
    {
        return self::$_instances;
    }
    
    public function getBindings(): array
    {
        return self::$_bindings;
    }
    
    public function getName(): string
    {
        return get_called_class();
    }
    
    protected function getContainer(): ServiceContainer
    {
        return $this->container;
    }
}
