<?php

namespace Test\Unit;

use Test\TestCase;
use TheSeed\Shared\ComponentMaker;
use TheSeed\Shared\Exceptions\NonInstantiableComponent;
use TheSeed\Shared\Exceptions\UndefinedComponentClass;
use TheSeed\Shared\Models\Id;

trait SomeTrait
{
}

class ComponentMakerTest extends TestCase
{
    public function testShouldSuccessfullyMakeTheComponent(): void
    {
        $expected = '740177dc-a20b-42ae-8740-8e0e6975a2f3';

        $maker = new ComponentMaker([Id::class]);
        $component = $maker->make(Id::class, $expected);

        $this->assertInstanceOf(Id::class, $component);
        $this->assertEquals($expected, $component->value());
    }

    public function testShouldThrowExceptionDueToNonInstantiableComponent(): void
    {
        $this->expectException(UndefinedComponentClass::class);

        $maker = new ComponentMaker(['SomeNotInstantiableClass']);
    }

    public function testShouldThrowExceptionDueToUndefinedComponentClass(): void
    {
        $this->expectException(NonInstantiableComponent::class);

        $maker = new ComponentMaker([SomeTrait::class]);
    }
}
