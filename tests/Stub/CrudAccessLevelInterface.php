<?php

namespace Access\Scope\Tests\Stub;

use Access\Scope\AccessFlag;
use Access\Scope\AccessScope;

#[AccessScope('crud')]
interface CrudAccessLevelInterface
{
    public const SCOPE = 'crud'; // для удобства работы с интерфейсом

    #[AccessFlag('Доступ на чтение')]
    public const CAN_READ = 'read';
    #[AccessFlag('Доступ на запись')]
    public const CAN_WRITE = 'write';
    #[AccessFlag('Доступ для обновления')]
    public const CAN_UPDATE = 'update';
    #[AccessFlag('Доступ для удаления')]
    public const CAN_DELETE = 'delete';
}
