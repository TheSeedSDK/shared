<?php

declare(strict_types=1);

namespace TheSeed\Shared;

use ReflectionClass;
use ReflectionException;
use TheSeed\Shared\Exceptions\NonInstantiableComponent;
use TheSeed\Shared\Exceptions\UndefinedComponentClass;

/**
 * Class ComponentMaker
 *
 * Make the required instances of each component of the system.
 *
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package TheSeed\Shared
 */
final class ComponentMaker
{
    /**
     * List of component definitions.
     *
     * @var array
     */
    private array $components = [];

    /**
     * Container constructor.
     *
     * @param  array  $components
     *
     * @throws NonInstantiableComponent
     * @throws UndefinedComponentClass
     */
    public function __construct(array $components = [])
    {
        try {
            foreach ($components as $component) {
                $reflector = new ReflectionClass($component);
                if (!$reflector->isInstantiable()) {
                    throw new NonInstantiableComponent(
                        "Given component <$component> is not instantiable."
                    );
                }

                $this->components[$component] = fn(...$arguments) => new $component(...$arguments);
            }
        } catch (ReflectionException $e) {
            throw new UndefinedComponentClass($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * True if the given id exists.
     *
     * @param  string  $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->components[$id]);
    }

    /**
     * Make a new component instance.
     *
     * @param  string  $component
     * @param  mixed  ...$parameters
     *
     * @return object|null
     */
    public function make(string $component, mixed ...$parameters): ?object
    {
        return ($this->has($component))
            ? $this->components[$component](...$parameters)
            : null;
    }
}
