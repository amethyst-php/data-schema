<?php

namespace Amethyst\DataSchema;

use Doctrine\Common\Inflector\Inflector;

class Helper
{
    const PREFIX = 'ac_';

    public static function toTable(string $name)
    {
        return static::PREFIX.Inflector::tableize(str_replace('-', '_', $name));
    }
}
