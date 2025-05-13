<?php

namespace Tests\App;

use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
use Bardiz12\AutoInjectForLaravel\AutoInjectable;
use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;

class ClassB
{
    use AutoInjectable;

    #[Inject(lazy: true)]
    /**
     * class A Loop dependency
     * @var ClassA|Lazy
     */
    protected ClassA|Lazy $classA;

    public function __construct()
    {
        $this->autoInject();
    }

    public function number()
    {
        return 5;
    }

    public function calculate()
    {
        return $this->classA->number() + $this->number();
    }
}
