<?php

namespace Access\Scope\Tests\Stub;

use Access\Scope\Interfaces\AccessRecipientInterface;
use Model\Base\Interfaces\ModelInterface;

class SimpleRecipient implements AccessRecipientInterface
{
    private string $recipientId;
    private ModelInterface $model;

    public function __construct(string $recipientId, ModelInterface $model)
    {
        $this->recipientId = $recipientId;
        $this->model = $model;
    }

    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    public function getRecipientModel(): ModelInterface
    {
        return $this->model;
    }
}
