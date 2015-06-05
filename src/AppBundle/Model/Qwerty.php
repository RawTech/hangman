<?php namespace AppBundle\Model;

/** Contains Qwerty keys */
class Qwerty
{
    /** @var array */
    private static $keys = [
        ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
        ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l'],
        ['z', 'x', 'c', 'v', 'b', 'n', 'm'],
    ];

    /** @return array */
    public static function getKeys()
    {
        return self::$keys;
    }
}
