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
    private Credit $balance;
    /**
     * @var WalletTypes
     */
    private WalletTypes $type;
    /**
     * @var string
     */
    private string $name;

    /**
     * Wallet constructor.
     * @param string $name
     * @param WalletTypes $type
     * @param Credit $balance
     */
    public function __construct(string $name, WalletTypes $type, Credit $balance)
    {
        $this->name = $name;
        $this->type = $type;
        $this->balance = $balance;
    }

    /**
     * @param int $amount
     * @param Currency $currency
     * @throws \Assert\AssertionFailedException
     */
    public function addBalance(int $amount, Currency $currency)
    {
        Assertion::notEq($this->type->key(), WalletTypes::GIFT, 'u can not add balance to ur gift card');
        $balance = new Credit($amount, $currency);
        $this->balance = $this->balance->add($balance);
    }

    /**
     * @param int $amount
     */
    public function subtractBalance(int $amount)
    {
        $balance = new Credit($amount, $this->balance->currency());
        $this->balance = $this->balance->subtract($balance);
    }

    /**
     * @return integer
     */
    public function balance()
    {
        return $this->balance->amount();
    }

    /**
     * @return WalletTypes
     */
    public function type()
    {
        return $this->type;
    }

    public function name()
    {
        return $this->name;
    }
}
