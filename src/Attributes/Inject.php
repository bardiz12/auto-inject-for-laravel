<?php

namespace Bardiz12\AutoInjectForLaravel\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Inject
{
    /**
     * @codeCoverageIgnore
     * @param mixed $abstract
     * @param bool $lazy
     */
    public function __construct(protected ?string $abstract = null, protected bool $lazy = false) {}
}
