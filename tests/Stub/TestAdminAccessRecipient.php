<?php

namespace Access\Scope\Tests\Stub;

use Access\Scope\Interfaces\AccessRecipientInterface;
use Access\Scope\Interfaces\FlagAccessorInterface;

class TestAdminAccessRecipient implements FlagAccessorInterface
{
    public function hasAccess(string $scope, string $flag, ?AccessRecipientInterface $recipient = null): bool
    {
        return $recipient && $recipient->getRecipientId() === 'admin';
    }
}
