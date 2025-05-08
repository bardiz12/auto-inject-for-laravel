<?php

namespace Tests\App;

use Bardiz12\AutoInjectForLaravel\Proxy\Lazy;

interface MyServiceContract
{
    public function method();
    public function getRepository(): MyRepositoryContract|Lazy;
}
