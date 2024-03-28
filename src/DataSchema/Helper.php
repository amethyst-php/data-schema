<?php

namespace Amethyst\DataSchema;

use Doctrine\Inflector\InflectorFactory;

class Helper
{
    const PREFIX = '';

    public static function toTable(string $name)
    {
        return static::PREFIX.(InflectorFactory::create()->build())->tableize(str_replace('-', '_', $name));
    }
}
