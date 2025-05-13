<?php

use Tests\App\ClassA;
use Tests\App\ClassB;

test("can have dependency loop", function () {
    $classA = app()->make(ClassA::class);
    $classB = app()->make(ClassB::class);

    expect($classA->calculate())->toEqual(15);
    expect($classB->calculate())->toEqual(15);
});
