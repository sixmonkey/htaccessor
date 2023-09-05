<?php

namespace App\Builders;

use App\Builders\Base\Builder;

class WpBase extends Builder
{
    public static int $position = -1;

    public static bool $requiresModRewrite = true;
    static function getTitle(): string
    {
        return "WordPress basic .htaccess";
    }
}
