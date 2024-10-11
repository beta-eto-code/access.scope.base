<?php

namespace Access\Scope;

use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class AccessScopeBuildEntity
{
    private ReflectionClass $reflectionClass;
    private ReflectionAttribute $reflectionAccessScope;

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public static function createAccessScope(string $interfaceName): AccessScope
    {
        return (new AccessScopeBuildEntity($interfaceName))->build();
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function __construct(string $interfaceName)
    {
        $reflectionClass = new ReflectionClass($interfaceName);
        $accessScopeAttribute = current($reflectionClass->getAttributes(AccessScope::class));
        if (empty($accessScopeAttribute)) {
            throw new Exception('Данный интерфейс не содержит аттрибута AccessScope');
        }

        $this->reflectionClass = $reflectionClass;
        $this->reflectionAccessScope = $accessScopeAttribute;
    }

    public function getName(): string
    {
        return (string) (current($this->reflectionAccessScope->getArguments()) ?: '');
    }

    /**
     * @throws Exception
     */
    public function build(): AccessScope
    {
        $accessScope = $this->internalCreateAccessScope();
        $this->loadAccessFlags($accessScope);
        return $accessScope;
    }

    private function internalCreateAccessScope(): AccessScope
    {
        return $this->reflectionAccessScope->newInstance();
    }

    /**
     * @throws Exception
     */
    private function loadAccessFlags(AccessScope $accessScope): void
    {
        $index = 0;
        foreach ($this->reflectionClass->getReflectionConstants() as $reflectionConstant) {
            $accessFlagAttribute = current($reflectionConstant->getAttributes(AccessFlag::class));
            if (!empty($accessFlagAttribute)) {
                $flagName = (string) ($reflectionConstant->getValue() ?? '');
                $accessScope->addAccessFlag(
                    $this->createAccessFlagFromAttribute($accessFlagAttribute, $flagName, $index++)
                );
            }
        }
    }

    /**
     * @throws Exception
     */
    private function createAccessFlagFromAttribute(
        ReflectionAttribute $reflectionAttribute,
        string $flagName,
        int $index
    ): AccessFlag {
        $accessFlag = $reflectionAttribute->newInstance();
        if (!($accessFlag instanceof AccessFlag)) {
            throw new Exception('Не верный класс флага доступа');
        }

        $accessFlag->name = $flagName;
        $accessFlag->accessValue = 1 << $index;
        return $accessFlag;
    }
}
