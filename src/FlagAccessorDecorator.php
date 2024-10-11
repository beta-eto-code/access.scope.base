<?php

namespace Access\Scope;

use Access\Scope\Interfaces\AccessRecipientInterface;
use Access\Scope\Interfaces\FlagAccessorInterface;

abstract class FlagAccessorDecorator implements FlagAccessorInterface
{
    private ?FlagAccessorInterface $flagAccessor = null;

    abstract protected function hasAccessInternal(
        string $scope,
        string $flag,
        ?AccessRecipientInterface $recipient = null
    ): bool;

    public function __construct(?FlagAccessorInterface $flagAccessor = null)
    {
        $this->flagAccessor = $flagAccessor;
    }

    final public function hasAccess(string $scope, string $flag, ?AccessRecipientInterface $recipient = null): bool
    {
        return $this->hasAccessInternal($scope, $flag, $recipient) ||
            ($this->flagAccessor && $this->flagAccessor->hasAccess($scope, $flag, $recipient));
    }
}
