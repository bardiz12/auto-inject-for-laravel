<?php

namespace Tests\App;

use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
use Bardiz12\AutoInjectForLaravel\AutoInjectable;
use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;
use Tests\App\MyRepositoryContract;

class MyLazyDependencyServiceWithProvidedAbstract implements MyServiceContract
{
    use AutoInjectable;

    #[Inject(abstract: MyRepositoryContract::class, lazy: true)]
    /**
     * Undocumented variable
     *
     * @var MyRepositoryContract|Lazy<MyRepositoryContract>
     */
    protected $myRepository;

    public function __construct()
    {
        $this->autoInject();
    }

    public function method()
    {
        return $this->myRepository->getData();
    }

    public function getRepository(): MyRepositoryContract|Lazy
    {
        return $this->myRepository;
    }
}
