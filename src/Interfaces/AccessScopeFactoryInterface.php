<?php

namespace Access\Scope\Interfaces;

use Access\Scope\AccessScope;
use Iterator;

interface AccessScopeFactoryInterface
{
    public function registerScope(string $interfaceName): void;
    public function createScope(string $scopeName): AccessScope;
    /**
     * @return Iterator|AccessScope[]
     */
    public function createScopes(): Iterator;
}
