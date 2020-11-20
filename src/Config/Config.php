<?php

namespace CommonUtils\Config;

use RuntimeException;

class Config implements \ArrayAccess
{
    private array $configArray;

    public function __construct(array $configArray)
    {
        $this->configArray = $configArray;
    }

    /**
     * @param string $name
     *
     * @return Config|string
     */
    public function __get(string $name)
    {
        $value = $this->configArray[$name];
        if (is_array($value)) {
            return new Config($value);
        }

        return (string)$value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->configArray[$name]);
    }

    public function __set(string $name, string $value): void
    {
        throw new RuntimeException('Not allowed to modify a config ini value dynamically');
    }

    public function __unset($name)
    {
        throw new RuntimeException('Not allowed to modify a config ini value dynamically');
    }

    public function offsetExists($offset): bool
    {
        return $this->__isset($offset);
    }

    /**
     * @param string $offset
     *
     * @return Config|string
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset($offset): void
    {
        $this->__unset($offset);
    }
}
