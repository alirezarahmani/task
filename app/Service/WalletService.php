<?php
declare(strict_types=1);

namespace App\Service;

use App\Exceptions\NotEnoughCreditException;
use App\Lib\Credit;
use App\Lib\Currency;
use App\Lib\Wallet;
use App\Lib\WalletTypes;
use App\Repositories\WalletRepository;
use App\Repositories\WalletRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Class WalletService
 * @package App\Service
 */
class WalletService
{
    /**
     * @var WalletRepositoryInterface
     */
    private WalletRepositoryInterface $repository;

    /**
     * WalletService constructor.
     * @param WalletRepository $walletRepository
     */
    public function __construct(WalletRepository $walletRepository)
    {
        $this->repository = $walletRepository;
    }

    /**
     * @param string $name
     * @param int $type
     * @param string $userEmail
     */
    public function add(string $name, string $userEmail, int $type)
    {
        try {
            $this->repository->findUserWallet($name, $userEmail);
        } catch (ModelNotFoundException $e) {
            $wallet = new Wallet($name, new WalletTypes($type), new Credit(0, new Currency()));
            $this->repository->create($wallet, $userEmail);
        }
    }

    /**
     * @param int $id
     * @param int $amount
     * @param string $userEmail
     * @param Currency $currency
     * @throws \Assert\AssertionFailedException
     */
    public function addRecord(int $id, string $userEmail, int $amount, Currency $currency)
    {
        try {
            /** @var Wallet $wallet */
            $wallet = $this->repository->findIdUserWallet($id, $userEmail);
            $wallet->addBalance($amount, $currency);
            $this->repository->update($wallet, $id);
        } catch (ModelNotFoundException $modelNotFoundException) {
            throw new \InvalidArgumentException('wallet with id: ' . $id . ' is not exist');
        }
    }

    /**
     * @param int $id
     * @param string $userEmail
     * @param int $amount
     */
    public function subtractRecord(int $id, string $userEmail, int $amount)
    {
        try {
            /** @var Wallet $wallet */
            $wallet = $this->repository->findIdUserWallet($id, $userEmail);
            $wallet->subtractBalance($amount);
            $this->repository->update($wallet, $id);
        } catch (ModelNotFoundException $modelNotFoundException) {
            throw new \InvalidArgumentException('wallet ' . $id . ' not exist');
        } catch (NotEnoughCreditException | \OverflowException $enoughCreditException) {
            throw new \InvalidArgumentException('ur wallet does not has enough credit');
        }
    }

    /**
     * @param $email
     * @return bool
     * @throws \Assert\AssertionFailedException
     */
    public function isEmpty($email)
    {
        $WalletCount = $this->repository->countUserWallet($email);
        return $WalletCount <= 0 ? true : false;
    }
}
