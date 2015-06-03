<?php namespace AppBundle\Model\Api\Enumeration;

/** Wordlist. */
class Words
{
    /** @var string[] */
    private static $words = [
        'antidisestablishmentarianism',
        'bikes',
        'cheeseburgers',
        'crackerjack',
        'fusion',
        'mammalian',
    ];

    /** @return string */
    public static function random()
    {
        return self::$words[array_rand(self::$words)];
    }
}
