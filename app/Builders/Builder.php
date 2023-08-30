<?php

namespace App\Builders;

abstract class Builder
{
    abstract static function getTitle(): string;
    abstract function build();
}
