<?php

namespace App\Builders;

class WpBaseBuilder extends Builder
{
    public static int $position = -1;

    public static bool $needsModRewrite = true;
    static function getTitle(): string
    {
        return "WordPress basic .htaccess";
    }
}
