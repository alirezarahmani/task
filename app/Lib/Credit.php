<?php
declare(strict_types=1);

namespace App\Lib;

use App\Exceptions\CurrencyMismatchException;
use App\Exceptions\NotEnoughCreditException;
use OverflowException;

/**
 * Class Credit
 */
class Credit
{
    /**
     * @var int
     */
    private int $amount = 0;

    /**
     * @var Currency
     */
    private Currency $currency;

    /**
     * Credit constructor.
     * @param int $amount
     * @param Currency $currency
     */
    public function __construct(int $amount, Currency $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @param Credit $other
     * @return Credit
     */
    public function add(Credit $other): Credit
    {
        $this->assertSameCurrency($this, $other);
        $value = $this->amount + $other->amount();
        $this->assertIsInteger($value);
        return $this->newCredit($value);
    }

    /**
     * @return Currency
     */
    public function currency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function amount(): int
    {
        return $this->amount;
    }

    /**
     * @param Credit $other
     * @return Credit
     */
    public function subtract(Credit $other):Credit
    {
        $this->assertSameCurrency($this, $other);
        $value = $this->amount - $other->amount();
        $this->assertIsInteger($value);
        $this->assertNotEnoughCredit($value);
        return $this->newCredit($value);
    }

    /**
     * @param $amount
     * @return Credit
     */
    private function newCredit($amount): Credit
    {
        return new static($amount, $this->currency);
    }

    /**
     * @param Credit $a
     * @param Credit $b
     */
    private function assertSameCurrency(Credit $a, Credit $b)
    {
        if ($a->currency()->key() != $b->currency()->key()) {
            throw new CurrencyMismatchException;
        }
    }

    /**
     * @param $amount
     */
    private function assertIsInteger($amount)
    {
        if (!is_int($amount)) {
            throw new OverflowException;
        }
    }

    /**
     * @param $amount
     */
    private function assertNotEnoughCredit($amount)
    {
        if ($amount < 0) {
            throw new NotEnoughCreditException();
        }
    }
}
