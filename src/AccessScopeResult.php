<?php

namespace Access\Scope;

class AccessScopeResult
{
    private string $scopeName;
    /**
     * @var array<string,AccessFlagResult>
     */
    private array $accessFlagResultList;
    private int $accessValue = 0;

    public function __construct(string $scopeName, AccessFlagResult ...$accessFlagResultList)
    {
        $this->scopeName = $scopeName;
        foreach ($accessFlagResultList as $accessFlagResult) {
            $accessFlag = $accessFlagResult->getAccessFlag();
            $name = $accessFlag->name;
            $this->accessFlagResultList[$name] = $accessFlagResult;
            if ($accessFlagResult->hasAccess()) {
                $this->accessValue |= $accessFlag->accessValue;
            }
        }
    }

    public function getScopeName(): string
    {
        return $this->scopeName;
    }

    public function getAccessValue(): int
    {
        return $this->accessValue;
    }

    /**
     * @return AccessFlagResult[]
     */
    public function getAccessFlagResultList(): array
    {
        return $this->accessFlagResultList;
    }

    /**
     * @return array<string,bool>
     */
    public function getAccessMap(): array
    {
        $accessMap = [];
        foreach ($this->accessFlagResultList as $name => $accessFlagResult) {
            $accessMap[$name] = $accessFlagResult->hasAccess();
        }
        return $accessMap;
    }

    public function hasAccessByFlagName(string $flagName): bool
    {
        $accessFlag = $this->accessFlagResultList[$flagName] ?? null;
        return $accessFlag && $accessFlag->hasAccess();
    }

    public function hasAccessByValue(int $accessValue): bool
    {
        return ($this->accessValue & $accessValue) === $accessValue;
    }
}
