<?php

namespace Access\Scope;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class AccessFlag
{
    private string $description;

    public string $name = '';
    public int $accessValue = 0;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
