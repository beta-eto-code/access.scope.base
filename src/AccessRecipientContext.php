<?php

namespace Access\Scope;

use Access\Scope\Interfaces\AccessRecipientContextInterface;
use Access\Scope\Interfaces\AccessRecipientInterface;

class AccessRecipientContext implements AccessRecipientContextInterface
{
    private AccessRecipientInterface $recipient;
    /**
     * @var AccessScopeResult[]
     */
    private array $scopeResultList;

    public function __construct(AccessRecipientInterface $recipient, AccessScopeResult ...$scopeResultList)
    {
        $this->recipient = $recipient;
        $this->scopeResultList = $scopeResultList;
    }

    public function getRecipient(): AccessRecipientInterface
    {
        return $this->recipient;
    }

    public function hasAccess(string $scope, int $flag): bool
    {
        $scopeResult = $this->getScopeByName($scope);
        return $scopeResult && $scopeResult->hasAccessByValue($flag);
    }

    public function hasAccessByFlagName(string $scope, string $flagName): bool
    {
        $scopeResult = $this->getScopeByName($scope);
        return $scopeResult && $scopeResult->hasAccessByFlagName($flagName);
    }

    private function getScopeByName(string $scope): ?AccessScopeResult
    {
        foreach ($this->scopeResultList as $scopeResult) {
            if ($scopeResult->getScopeName() === $scope) {
                return $scopeResult;
            }
        }
        return null;
    }
}
