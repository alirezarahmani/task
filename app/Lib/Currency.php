<?php
declare(strict_types=1);

namespace App\Lib;

use Assert\Assertion;

/**
 * Class Currency
 * @package App\Lib
 */
class Currency
{
    const __default = self::DOLLAR;
    const DOLLAR = 1;
    const EURO = 2;

    /**
     * @var int
     */
    private int $key;

    /**
     * Currency constructor.
     * @param $key
     */
    public function __construct($key = self::DOLLAR)
    {
        Assertion::inArray($key, self::all());
        $this->key = $key;
    }

    /**
     * @return array
     */
    public static function all()
    {
        return [
            self::DOLLAR,
            self::EURO
        ];
    }

    /**
     * @return array
     */
    public static function allTags()
    {
        return [
            'dollar' => self::DOLLAR,
            'euro' => self::EURO
        ];
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

}
