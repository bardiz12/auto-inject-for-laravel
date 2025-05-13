<?php

use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
use Bardiz12\AutoInjectForLaravel\AutoInjectable;
use Bardiz12\AutoInjectForLaravel\Exception\UndefinedAbstractException;
use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;
use Tests\App\MyLazyDependencyService;
use Tests\App\MyLazyDependencyServiceWithProvidedAbstract;
use Tests\App\MyRepository;
use Tests\App\MyRepositoryContract;
use Tests\App\MyService;
use Tests\App\MyServiceContract;
use Tests\App\MyServiceWithProvidedAbstract;

test('can auto inject based on property type', function (string $concreteClassName) {
    app()->singleton(MyRepositoryContract::class, MyRepository::class);
    app()->singleton(MyServiceContract::class, $concreteClassName);

    $service = app(MyServiceContract::class);

    //since its not lazy, it has been loaded
    expect(app()->resolved(MyRepositoryContract::class))->toBeTrue();

    // expect(app())->toBeTrue();
    expect($service)->toBeInstanceOf($concreteClassName);
    expect($service->method())->toBe("hello");

    expect(spl_object_id($service->getRepository()))->toEqual(spl_object_id(app(MyRepositoryContract::class)));
})->with([
    "using defined PHP Type" => [MyService::class],
    "using defined abstract in Attribute" => [MyServiceWithProvidedAbstract::class],
]);

test('can auto inject based on property type lazyly', function (string $concreteClassName) {
    app()->singleton(MyRepositoryContract::class, MyRepository::class);
    app()->singleton(MyServiceContract::class, $concreteClassName);

    $service = app(MyServiceContract::class);

    // expect(app())->toBeTrue();
    expect($service)->toBeInstanceOf($concreteClassName);

    //since its not lazy, it has not been loaded
    expect(app()->resolved(MyRepositoryContract::class))->toBeFalse();

    //load it via proxy
    expect($service->method())->toBe("hello");
    expect($service)->toBeInstanceOf($concreteClassName);
    expect(app()->resolved(MyRepositoryContract::class))->toBeTrue();

    expect(spl_object_id($service->getRepository()->resolve()))->toEqual(spl_object_id(app(MyRepositoryContract::class)));
})->with([
    "using defined PHP Type" => [MyLazyDependencyService::class],
    "using defined abstract in Attribute" => [MyLazyDependencyServiceWithProvidedAbstract::class],
]);

test("lazy proxy only created once per abstract", function () {
    app()->singleton(MyRepositoryContract::class, MyRepository::class);
    app()->singleton(MyLazyDependencyService::class, MyLazyDependencyService::class);
    app()->singleton(MyLazyDependencyServiceWithProvidedAbstract::class, MyLazyDependencyServiceWithProvidedAbstract::class);

    $service1 = app(MyLazyDependencyService::class);
    $service2 = app(MyLazyDependencyServiceWithProvidedAbstract::class);

    expect(spl_object_id($service1->getRepository()))->toEqual(spl_object_id($service2->getRepository()));
});

test("undefined abstract return error", function () {

    $class = new class() {
        use AutoInjectable;

        #[Inject()]
        protected $myprop;

        public function __construct()
        {
            $this->autoInject();
        }
    };
})->throws(UndefinedAbstractException::class);

test("Lazy can be used as proxy", function () {
    app()->singleton(MyRepository::class, MyRepository::class);
    $lazy = new Lazy(MyRepository::class);

    $lazy->property = "hello";
    expect(app(MyRepository::class)->property)->toEqual("hello");

    app(MyRepository::class)->property = "world";
    expect(value: $lazy->property)->toEqual("world");
});
