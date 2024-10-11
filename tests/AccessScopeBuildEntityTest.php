<?php

namespace Access\Scope\Tests;

use Access\Scope\Tests\Stub\CrudAccessLevelInterface;
use PHPUnit\Framework\TestCase;
use Access\Scope\AccessScopeBuildEntity;
use Access\Scope\AccessScope;
use Access\Scope\AccessFlag;
use ReflectionException;
use Exception;

class AccessScopeBuildEntityTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function testCreateAccessScope()
    {
        $accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
        $this->assertInstanceOf(AccessScope::class, $accessScope);
        $this->assertEquals('crud', $accessScope->getScopeName());

        $readFlag = $accessScope->getAccessFlag('read');
        $this->assertInstanceOf(AccessFlag::class, $readFlag);
        $this->assertEquals('read', $readFlag->name);
        $this->assertEquals('Доступ на чтение', $readFlag->getDescription());
        $this->assertEquals(1 << 0, $readFlag->accessValue);

        $writeFlag = $accessScope->getAccessFlag('write');
        $this->assertInstanceOf(AccessFlag::class, $writeFlag);
        $this->assertEquals('write', $writeFlag->name);
        $this->assertEquals('Доступ на запись', $writeFlag->getDescription());
        $this->assertEquals(1 << 1, $writeFlag->accessValue);

        $updateFlag = $accessScope->getAccessFlag('update');
        $this->assertInstanceOf(AccessFlag::class, $updateFlag);
        $this->assertEquals('update', $updateFlag->name);
        $this->assertEquals('Доступ для обновления', $updateFlag->getDescription());
        $this->assertEquals(1 << 2, $updateFlag->accessValue);

        $deleteFlag = $accessScope->getAccessFlag('delete');
        $this->assertInstanceOf(AccessFlag::class, $deleteFlag);
        $this->assertEquals('delete', $deleteFlag->name);
        $this->assertEquals('Доступ для удаления', $deleteFlag->getDescription());
        $this->assertEquals(1 << 3, $deleteFlag->accessValue);

    }
}