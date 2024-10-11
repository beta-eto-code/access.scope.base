<?php

namespace Access\Scope\Tests;

use Access\Scope\AccessFlag;
use Access\Scope\AccessScope;
use Access\Scope\AccessScopeBuildEntity;
use Access\Scope\AccessScopeResult;
use Access\Scope\Tests\Stub\CrudAccessLevelInterface;
use Access\Scope\Tests\Stub\SimpleRecipient;
use Access\Scope\Tests\Stub\TestAdminAccessRecipient;
use Access\Scope\Tests\Stub\UserModel;
use PHPUnit\Framework\TestCase;
use ReflectionException;

class AccessScopeTest extends TestCase
{
    public function testConstructor()
    {
        $scopeName = 'TestScope';
        $accessScope = new AccessScope($scopeName);

        $this->assertEquals($scopeName, $accessScope->getScopeName());
    }

    public function testAddAccessFlag()
    {
        $accessScope = new AccessScope('TestScope');
        $accessFlag = new AccessFlag('some description');
        $accessFlag->name = 'TestFlag';

        $accessScope->addAccessFlag($accessFlag);
        $this->assertArrayHasKey('TestFlag', $accessScope->getAccessFlagList());
    }

    public function testGetAccessFlag()
    {
        $accessScope = new AccessScope('TestScope');
        $accessFlag = new AccessFlag('some description');
        $accessFlag->name = 'TestFlag';
        $accessScope->addAccessFlag($accessFlag);

        $this->assertInstanceOf(AccessFlag::class, $accessScope->getAccessFlag('TestFlag'));
        $this->assertEquals('TestFlag', $accessScope->getAccessFlag('TestFlag')->name);
    }

    /**
     * @throws ReflectionException
     */
    public function testCreateScopeResultByAccessor()
    {
        $accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
        $adminRecipient = new SimpleRecipient(
            'admin',
            UserModel::initFromArray(['id' => 'admin', 'username' => 'admin'])
        );
        $accessScopeResult = $accessScope->createScopeResultByAccessor(
            new TestAdminAccessRecipient(),
            $adminRecipient
        );
        $this->assertInstanceOf(AccessScopeResult::class, $accessScopeResult);
        $this->assertEquals(
            ($accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_READ)->accessValue |
            $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_WRITE)->accessValue |
            $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_UPDATE)->accessValue |
            $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_DELETE)->accessValue),
            $accessScopeResult->getAccessValue()
        );
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_READ));
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_WRITE));
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_UPDATE));
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_DELETE));

        $adminRecipient = new SimpleRecipient(
            'user',
            UserModel::initFromArray(['id' => 'user', 'username' => 'user'])
        );
        $accessScopeResult = $accessScope->createScopeResultByAccessor(
            new TestAdminAccessRecipient(),
            $adminRecipient
        );
        $this->assertInstanceOf(AccessScopeResult::class, $accessScopeResult);
        $this->assertEquals(0, $accessScopeResult->getAccessValue());
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_READ));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_WRITE));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_UPDATE));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_DELETE));
    }

    /**
     * @throws ReflectionException
     */
    public function testCreateScopeResultWithFlags()
    {
        $accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
        $accessScopeResult = $accessScope->createScopeResultWithFlags(
            CrudAccessLevelInterface::CAN_READ,
            CrudAccessLevelInterface::CAN_WRITE
        );
        $this->assertInstanceOf(AccessScopeResult::class, $accessScopeResult);
        $this->assertEquals(
            ($accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_READ)->accessValue |
                $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_WRITE)->accessValue),
            $accessScopeResult->getAccessValue()
        );
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_READ));
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_WRITE));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_UPDATE));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_DELETE));
    }

    /**
     * @throws ReflectionException
     */
    public function testGetValueWithFlags()
    {
        $accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
        $this->assertEquals(
            (
                $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_READ)->accessValue |
                $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_WRITE)->accessValue
            ),
            $accessScope->getValueWithFlags(
                CrudAccessLevelInterface::CAN_READ,
                CrudAccessLevelInterface::CAN_WRITE
            )
        );
    }

    public function testCreateScopeResultByAccessValue()
    {
        $accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
        $accessValue = $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_READ)->accessValue |
            $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_WRITE)->accessValue;

        $accessScopeResult = $accessScope->createScopeResultByAccessValue($accessValue);
        $this->assertInstanceOf(AccessScopeResult::class, $accessScopeResult);
        $this->assertEquals(
            ($accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_READ)->accessValue |
                $accessScope->getAccessFlag(CrudAccessLevelInterface::CAN_WRITE)->accessValue),
            $accessScopeResult->getAccessValue()
        );
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_READ));
        $this->assertTrue($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_WRITE));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_UPDATE));
        $this->assertFalse($accessScopeResult->hasAccessByFlagName(CrudAccessLevelInterface::CAN_DELETE));
    }
}
