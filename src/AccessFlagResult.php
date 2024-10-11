<?php

namespace Access\Scope;

class AccessFlagResult
{
    private AccessFlag $accessFlag;
    private bool $hasAccess;

    public function __construct(AccessFlag $accessFlag, bool $hasAccess)
    {
        $this->accessFlag = $accessFlag;
        $this->hasAccess = $hasAccess;
    }

    public function getAccessFlag(): AccessFlag
    {
        return $this->accessFlag;
    }

    public function hasAccess(): bool
    {
        return $this->hasAccess;
    }
}
