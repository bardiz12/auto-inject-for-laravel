<?php

namespace Tests\App;

use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
use Bardiz12\AutoInjectForLaravel\AutoInjectable;

class MyService implements MyServiceContract
{
    use AutoInjectable;

    #[Inject()]
    protected MyRepositoryContract $myRepository;

    public function __construct()
    {
        $this->autoInject();
    }

    public function method()
    {
        return $this->myRepository->getData();
    }

    public function getRepository(): MyRepositoryContract
    {
        return $this->myRepository;
    }
}
