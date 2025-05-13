<?php

namespace Tests\App;

use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
use Bardiz12\AutoInjectForLaravel\AutoInjectable;
use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;

class ClassA
{
    use AutoInjectable;

    #[Inject()]
    /**
     * class B Loop dependency
     * @var ClassB|Lazy
     */
    protected ClassB|Lazy $classB;

    public function __construct()
    {
        $this->autoInject();
    }

    public function number()
    {
        return 10;
    }

    public function calculate()
    {
        return $this->classB->number() + $this->number();
    }
}
