<?php


namespace Tests\App;

class MyRepository implements MyRepositoryContract
{
    public ?string $property = null;
    public function __construct() {}
    public function getData(): string
    {
        return "hello";
    }
}
