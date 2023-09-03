<?php

namespace App\Builders;

class WpBaseBuilder extends Builder
{
    static function getTitle(): string
    {
        return "WordPress basic .htaccess";
    }
}
