<?php

declare(strict_types=1);

namespace App\Enum;

use MyCLabs\Enum\Enum;

final class RoleEnum extends Enum
{
    private const USER = 'ROLE_USER';
    private const ADMIN = 'ROLE_ADMIN';
    private const PO = 'ROLE_PO';
    private const DEV = 'ROLE_DEV';
}
