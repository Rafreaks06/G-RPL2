<?php

namespace App\Enums;

class CourseRplType
{
    public const A1 = 'a1';

    public const A2 = 'a2';

    public const HYBRID = 'hybrid';

    public const NOT_SUPPORTED = 'not_supported';

    public const ALL = [
        self::A1,
        self::A2,
        self::HYBRID,
        self::NOT_SUPPORTED,
    ];
}