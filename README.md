# Auto Inject For Laravel
[![codecov](https://codecov.io/github/bardiz12/auto-inject-for-laravel/graph/badge.svg?token=ZXRV9QDREA)](https://codecov.io/github/bardiz12/auto-inject-for-laravel)

Inspired by php-di's `#[Inject()]` attribute, i created a simple package to automatically inject dependency to php class's properties. This package is also support lazy load the dependency by proxying the access to class via Laravel's app() helper.

## Installation
`composer require bardiz12/auto-inject-for-laravel`

## Usage

1. Use Auto Inject trait and add autoInject method in your constructor
    ```php
    <?php

    namespace ...;

    use Bardiz12\AutoInjectForLaravel\AutoInjectable;

    class MyClass
    {
        use AutoInjectable;
        ...
        public function __construct()
        {
            $this->autoInject();
        }
    }
    ```

2. Add Attribute to your class Property
   - Reading abstract from property's type
        ```php
        <?php

        namespace ...;

        use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
        use Bardiz12\AutoInjectForLaravel\AutoInjectable;

        class MyClass
        {
            use AutoInjectable;

            #[Inject()]
            protected MyDependency $myDependency;

            ...
            public function __construct()
            {
                $this->autoInject();
            }
        }
        ```
    - or, you can use custom string abstract by passing parameter to the Inject attribute
        ```php
        <?php

        namespace ...;

        use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
        use Bardiz12\AutoInjectForLaravel\AutoInjectable;

        class MyClass
        {
            use AutoInjectable;

            #[Inject(abstract: MyDependency::class)]
            /**
            * My Dependency
            *
            * @var MyDependency
            */
            protected $myDependency;

            ...
            public function __construct()
            {
                $this->autoInject();
            }
        }
        ```
3. To Use Lazy load, you can add lazy parameter to the Inject attribute. If the lazy parameter is not provided but the property has **Lazy** type using Union Type, it will be loaded as lazy Dependency.
    ```php
    <?php

    namespace ...;

    use Bardiz12\AutoInjectForLaravel\Attributes\Inject;
    use Bardiz12\AutoInjectForLaravel\AutoInjectable;
    use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;

    class MyClass
    {
        use AutoInjectable;

        #[Inject(lazy: true)]
        /**
         * 
         *
         * @var MyDependency|Lazy<MyDependency>
         */
        protected MyDependency|Lazy $myDependency;

        ...
        public function __construct()
        {
            $this->autoInject();
        }
    }
    ```
    or 


