<?php

namespace Access\Scope\Interfaces;


use Model\Base\Interfaces\ModelInterface;

interface AccessRecipientInterface
{
    public function getRecipientId(): string;
    public function getRecipientModel(): ModelInterface;
}
