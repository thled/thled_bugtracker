<?php

declare(strict_types=1);

namespace App\DataTransferObject;

final class UserShowDto implements DataTransferObjectInterface
{
    public ?string $username = null;
    public array $roles = [];
}
