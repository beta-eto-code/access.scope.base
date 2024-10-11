<?php

namespace Access\Scope\Tests\Stub;

use Model\Base\BaseSerializableModel;
use Model\Base\Interfaces\ModelInterface;
use Model\Base\ModelDataLoader;

class UserModel extends BaseSerializableModel
{
    public string $id;
    public string $username;

    public static function initFromArray(array $data): ModelInterface
    {
        $model = new UserModel();
        ModelDataLoader::loadData($model, $data);
        return $model;
    }
}
