<?php
declare(strict_types=1);

namespace App\Lib;

use Assert\Assertion;

/**
 * Class WalletTypes
 * @package App\Lib
 */
class WalletTypes
{
    const __default = self::CASH;

    const CASH = 1;
    const GIFT = 2;
    const DEBIT = 3;
    const PREPAID = 4;

    /**
     * @var int
     */
    private int $key;

    /**
     * WalletTypes constructor.
     * @param int $key
     */
    public function __construct($key = self::CASH)
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
            self::CASH,
            self::DEBIT,
            self::GIFT,
            self::PREPAID
        ];
    }

    /**
     * @return array
     */
    public static function allTags()
    {
        return [
            'cash' => self::CASH,
            'debit' => self::DEBIT,
            'gift' => self::GIFT,
            'prepaid' => self::PREPAID
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
