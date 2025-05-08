<?php

namespace Bardiz12\AutoInjectForLaravel;

use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
use Bardiz12\AutoInjectForLaravel\Exception\UndefinedAbstractException;
use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;
use ReflectionClass;
use ReflectionUnionType;

trait AutoInjectable
{
    public function autoInject(): void
    {
        $reflection = new ReflectionClass($this);

        foreach ($reflection->getProperties() as $property) {
            $attributes = $property->getAttributes(Inject::class);

            if (count($attributes) > 0) {
                $args = $attributes[0]->getArguments();
                $abstract = $args['abstract'] ?? null;

                if ($abstract === null) {
                    $type = $property->getType();
                    if ($type instanceof ReflectionUnionType) {
                        foreach ($type->getTypes() as $type) {
                            if ($type->getName() === Lazy::class) {
                                continue;
                            } else {
                                $abstract = $type->getName();
                                break;
                            }
                        }
                    } else if ($type && (interface_exists($type->getName()) || class_exists($type->getName()))) {
                        $abstract = $type->getName();
                    };
                }

                $lazy = $args['lazy'] ?? false;

                if ($abstract === null) {
                    throw new UndefinedAbstractException($this::class . "::\$" . $property->getName() . " must have defined type or must have defined abstract. use : #[Inject(abstract: YOUR_ABSTRACT)]");
                }

                $property->setAccessible(true);
                if ($lazy) {
                    if (!app()->has("lazy." . $abstract)) {
                        app()->singleton("lazy." . $abstract, fn() => new Lazy($abstract));
                    }
                    $property->setValue($this, app("lazy." . $abstract));
                    continue;
                }

                $instance = app()->make($abstract);
                $property->setAccessible(true);
                $property->setValue($this, $instance);
            }
        }
    }
}
