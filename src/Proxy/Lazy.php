<?php

namespace Bardiz12\AutoInjectForLaravel\Proxy;

/**
 * @template T
 * @extends  T
 * @implements T
 * @method T resolve()
 */
class Lazy
{

    public function __construct(protected string $abstract) {}

    /**
     * resolve
     *
     * @return T
     */
    public function resolve(): object
    {
        return app($this->abstract);
    }

    public function __call(string $method, array $args)
    {
        return $this->resolve()->$method(...$args);
    }

    public function __get(string $name)
    {
        return $this->resolve()->$name;
    }

    public function __set(string $name, $value)
    {
        $this->resolve()->$name = $value;
    }
}
