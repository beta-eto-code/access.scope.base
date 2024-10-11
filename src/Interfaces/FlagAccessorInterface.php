<?php

namespace Access\Scope\Interfaces;


interface FlagAccessorInterface
{
    public function hasAccess(string $scope, string $flag, ?AccessRecipientInterface $recipient = null): bool;
}
