<?php

namespace Access\Scope\Interfaces;

interface AccessRecipientContextInterface
{
    public function getRecipient(): AccessRecipientInterface;
    public function hasAccess(string $scope, int $flag): bool;
    public function hasAccessByFlagName(string $scope, string $flagName): bool;
}
