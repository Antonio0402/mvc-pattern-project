<?php

namespace App\Framework;

use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $registry = [];

    public function set(string $key, \Closure $value): void
    {
        $this->registry[$key] = $value;
    }
    public function get(string $class): object
    {
        if (array_key_exists($class, $this->registry)) {
            return $this->registry[$class]();
        }
        $reflector = new ReflectionClass($class);
        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return new $class();
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();

            if ($type === null) {
                throw new \InvalidArgumentException("Constructor parameter '{$param->getName()}' in the $class class has no type declaration");
            }

            if (!$type instanceof ReflectionNamedType) {

                throw new \InvalidArgumentException("Constructor parameter '{$param->getName()}' in the $class class is an invalid type: '$type' - only single named types supported");

            }

            if ($type->isBuiltin()) {
                throw new \InvalidArgumentException("Unable to resolve constructor parameter '{$param->getName()}' of type '$type' in the $class class");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return new $class(...$dependencies);
    }
}
