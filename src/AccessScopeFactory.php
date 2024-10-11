<?php

namespace Access\Scope;

use Access\Scope\Interfaces\AccessScopeFactoryInterface;
use EmptyIterator;
use Exception;
use Iterator;
use ReflectionException;

class AccessScopeFactory implements AccessScopeFactoryInterface
{
    /**
     * @var array<string,AccessScopeBuildEntity>
     */
    private array $buildEntityList = [];
    /**
     * @var array<string,AccessScope>
     */
    private array $scopeList = [];

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function registerScope(string $interfaceName): void
    {
        $buildEntity = new AccessScopeBuildEntity($interfaceName);
        $scopeName = $buildEntity->getName();
        unset($this->scopeList[$scopeName]);
        $this->buildEntityList[$scopeName] = $buildEntity;
    }

    /**
     * @return Iterator<AccessScope>
     * @throws Exception
     */
    public function createScopes(): Iterator
    {
        foreach (array_keys($this->buildEntityList) as $scopeName) {
            yield $this->createScope($scopeName);
        }
        return new EmptyIterator();
    }

    /**
     * @throws Exception
     */
    public function createScope(string $scopeName): AccessScope
    {
        $scope = $this->scopeList[$scopeName] ?? null;
        if ($scope instanceof AccessScope) {
            return $scope;
        }

        $buildEntity = $this->buildEntityList[$scopeName] ?? null;
        if (empty($buildEntity)) {
            throw new Exception('Отсуствуют данные для вычисления доступов: ' . $scopeName);
        }

        return $this->scopeList[$scopeName] = $buildEntity->build();
    }
}
