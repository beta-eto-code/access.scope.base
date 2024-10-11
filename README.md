## Установка

```
composer require beta/access.scope.base
```

Данный модуль предназначен для организации глобальных (не контекстных) доступов в проекте.
Это означает что единственным контекстом для данных прав может быть только пользовательская сессия.

Для организации прав в модуле предусмотрены след. сущности:

* Интерфейс пространства доступов - представляет из себя программный интерфейс использующий атрибуты AccessScope и AccessFlag.
* Пространство доступов - экземпляр AccessScope построенный через интерфейс.
* FlagAccessor - представляет реализацию интерфейса FlagAccessor интерфейс, принимает решение о доступе к флагам в пространстве доступов.
* Флаг пространства доступов - является константой интерфейса пространства доступов с атрибутом AccessFlag.
* Результат пространства доступов - экземпляр AccessScopeResult, содержащий в себе описание пространства + результаты с вычисленными доступами (либо на основе контекста пользователя или на основе значения битовой маски).

*Пример описания интерфейса пространства доступов:*

```php
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
```

*Пример создания пространства доступов на основе интерфейса:*

```php
use Access\Scope\AccessScopeBuildEntity;

$accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
$accessScope->getScopeName(); // crud
$accessScope->getAccessFlag('read')->getDescription(); // Доступ на чтение
$accessScope->getAccessFlag('update')->accessValue; // 8
$accessScope->getAccessFlag('delete')->accessValue; // 16
$accessScope->getAccessFlag('delete')->name; // delete
```

*Пример реализации FlagAccessor:*

```php
use Access\Scope\Interfaces\FlagAccessorInterface;
use Access\Scope\Interfaces\AccessRecipientInterface;

class FlagAccessorAllowOnlyMainAdmin implements FlagAccessorInterface
{
    public function hasAccess(string $scope, string $flag, ?AccessRecipientInterface $recipient = null): bool
    {
        return $recipient && (int) ($recipient->getRecipientId()) === 1;
    }
}
```

*Пример расчета результата пространства доступов:*

```php
use Access\Scope\AccessScopeBuildEntity;

$accessScope = AccessScopeBuildEntity::createAccessScope(CrudAccessLevelInterface::class);
$accessor = new FlagAccessorAllowOnlyMainAdmin();
$adminAccessScopeResult = $accessScope->createScopeResultByAccessor($accessor, $adminUserContext);
$adminAccessScopeResult->getAccessValue(); // 30
$adminAccessScopeResult->hasAccessByFlagName('read'); // true
$adminAccessScopeResult->hasAccessByFlagName('write'); // true
$adminAccessScopeResult->hasAccessByFlagName('update'); // true
$adminAccessScopeResult->hasAccessByValue(8); // true
$adminAccessScopeResult->hasAccessByFlagName('delete'); // true
$adminAccessScopeResult->hasAccessByValue(16); // true
$userAccessScopeResult = $accessScope->createScopeResultByAccessor($accessor, $simpleUserContext);
$userAccessScopeResult->getAccessValue(); // 0
$userAccessScopeResult->hasAccessByFlagName('read'); // false
$userAccessScopeResult->hasAccessByFlagName('write'); // false
$userAccessScopeResult->hasAccessByFlagName('update'); // false
$userAccessScopeResult->hasAccessByValue(8); // false
$userAccessScopeResult->hasAccessByFlagName('delete'); // false
$userAccessScopeResult->hasAccessByValue(16); // false
$resultValue = $accessScope->getAccessFlag('read')->accessValue | $accessScope->getAccessFlag('write')->accessValue;
$otherAccessScopeResult = $accessScope->createScopeResultByAccessValue($resultValue);
$otherAccessScopeResult->getAccessValue(); // 6
$otherAccessScopeResult->hasAccessByFlagName('read'); // true
$otherAccessScopeResult->hasAccessByFlagName('write'); // true
$otherAccessScopeResult->hasAccessByFlagName('update'); // false
$otherAccessScopeResult->hasAccessByValue(8); // false
$otherAccessScopeResult->hasAccessByFlagName('delete'); // false
$otherAccessScopeResult->hasAccessByValue(16); // false
```
