<?php
declare(strict_types=1);

namespace App\Lib;

use Assert\Assertion;

/**
 * Class Wallet
 * @package App\Lib
 */
class Wallet
{
    /**
     * @var Credit
     */
    private Credit $credit;
    /**
     * @var WalletTypes
     */
    private WalletTypes $type;
    /**
     * @var string
     */
    private string $name;

    /**
     * @var Currency
     */
    private Currency $currency;

    /**
     * Wallet constructor.
     * @param string $name
     * @param WalletTypes $type
     * @param Credit $credit
     */
    public function __construct(string $name, WalletTypes $type, Credit $credit)
    {
        $this->name = $name;
        $this->type = $type;
        $this->credit = $credit;
        $this->currency = $credit->currency();
    }

    /**
     * @param int $amount
     * @param Currency $currency
     * @throws \Assert\AssertionFailedException
     */
    public function addBalance(int $amount, Currency $currency)
    {
        Assertion::notEq($this->type->key(), WalletTypes::GIFT, 'u can not add balance to ur gift card');
        $credit = new Credit($amount, $currency);
        $this->credit = $this->credit->add($credit);
    }

    /**
     * @param int $amount
     */
    public function subtractBalance(int $amount)
    {
        $credit = new Credit($amount, $this->credit->currency());
        $this->credit = $this->credit->subtract($credit);
    }

    /**
     * @return integer
     */
    public function balance()
    {
        return $this->credit->amount();
    }

    /**
     * @return WalletTypes
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function currency()
    {
        return $this->currency->key();
    }
}
