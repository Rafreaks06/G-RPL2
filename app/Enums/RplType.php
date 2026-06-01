<?php

namespace App\Enums;

class RplType
{
    public const A1 = 'a1';

    public const A2 = 'a2';

    public const HYBRID = 'hybrid';

    public const ALL = [
        self::A1,
        self::A2,
        self::HYBRID,
    ];
}