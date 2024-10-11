<?php

namespace Access\Scope;

use Access\Scope\Interfaces\AccessRecipientInterface;
use Attribute;
use Access\Scope\Interfaces\FlagAccessorInterface;

#[Attribute(Attribute::TARGET_CLASS)]
class AccessScope
{
    private string $scopeName;

    /**
     * @var array<string,AccessFlag>
     */
    private array $accessFlagList = [];

    public function __construct(string $scopeName)
    {
        $this->scopeName = $scopeName;
    }

    public function getScopeName(): string
    {
        return $this->scopeName;
    }

    public function addAccessFlag(AccessFlag $flag): void
    {
        $this->accessFlagList[$flag->name] = $flag;
    }

    public function getAccessFlag(string $flagName): ?AccessFlag
    {
        $accessFlag = $this->accessFlagList[$flagName] ?? null;
        return $accessFlag instanceof AccessFlag ? $accessFlag : null;
    }

    public function getAccessFlagList(): array
    {
        return $this->accessFlagList;
    }

    public function createScopeResultByAccessor(
        FlagAccessorInterface $accessor,
        ?AccessRecipientInterface $recipient = null
    ): AccessScopeResult {
        $accessFlagResultList = [];
        foreach ($this->accessFlagList as $accessFlag) {
            $accessFlagResultList[] = new AccessFlagResult(
                $accessFlag,
                $accessor->hasAccess($this->scopeName, $accessFlag->name, $recipient)
            );
        }

        return new AccessScopeResult($this->scopeName, ...$accessFlagResultList);
    }

    public function createScopeResultWithFlags(string ...$flagNames): AccessScopeResult
    {
        return $this->createScopeResultByAccessValue($this->getValueWithFlags(...$flagNames));
    }

    public function getValueWithFlags(string ...$flagNames): int
    {
        $accessValue = 0;
        foreach ($flagNames as $flagName) {
            $flag = $this->getAccessFlag($flagName);
            if ($flag !== null) {
                $accessValue |= $flag->accessValue;
            }
        }
        return $accessValue;
    }

    public function createScopeResultByAccessValue(int $accessValue): AccessScopeResult
    {
        $accessFlagResultList = [];
        foreach ($this->accessFlagList as $accessFlag) {
            $accessFlagResultList[] = new AccessFlagResult(
                $accessFlag,
                ($accessValue & $accessFlag->accessValue) === $accessFlag->accessValue
            );
        }

        return new AccessScopeResult($this->scopeName, ...$accessFlagResultList);
    }
}
