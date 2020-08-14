<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Lib\Credit;
use App\Lib\Currency;
use App\Lib\Wallet;
use App\Lib\WalletTypes;
use App\User;
use App\Wallet as WalletModel;
use Assert\Assertion;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * Class WalletRepository
 * @package App\Repositories
 */
class WalletRepository implements WalletRepositoryInterface
{
    /**
     * @param Wallet $wallet
     */
    public function create(Wallet $wallet, string $userEmail)
    {
        $user = User::where('email', $userEmail)->first();
        $walletModel = new WalletModel();
        $walletModel->name = $wallet->name();
        $walletModel->balance = 0;
        $walletModel->type = $wallet->type()->key();
        $walletModel->user_id = $user->id;
        $walletModel->currency = $wallet->currency();
        $walletModel->save();
    }

    public function update(Wallet $wallet, int $id)
    {
        $walletModel = WalletModel::where('id', $id)->first();
        $walletModel->name = $wallet->name();
        $walletModel->balance = $wallet->balance();
        $walletModel->type = $wallet->type()->key();
        $walletModel->currency = $wallet->currency();
        $walletModel->save();
    }

    /**
     * @param string $name
     * @param string $email
     * @return Wallet
     */
    public function findUserWallet(string $name, string $email)
    {
        $user = User::where('email', $email)->first();
        $wallet = \App\Wallet::where('name', $name)->where('user_id', $user->id)->first();

        if ($wallet) {
            return new Wallet($wallet->name, new WalletTypes($wallet->type), new Credit(0, new Currency($wallet->currency)));
        }
        throw new ModelNotFoundException('the wallet with ' . $name . ' is not found for user!');
    }

    /**
     * @param int $id
     * @param string $email
     * @return Wallet
     */
    public function findIdUserWallet(int $id, string $email)
    {
        $user = User::where('email', $email)->first();
        $wallet = \App\Wallet::where('id', $id)->where('user_id', $user->id)->first();
        if ($wallet) {
            return new Wallet($wallet->name, new WalletTypes($wallet->type), new Credit($wallet->balance, new Currency($wallet->currency)));
        }
        throw new ModelNotFoundException('the wallet with ' . $id . ' is not found for user!');
    }

    /**
     * @param string $email
     * @return int
     * @throws \Assert\AssertionFailedException
     */
    public function countUserWallet(string $email): int
    {
        /** @var User $user */
        $user = User::where('email',$email)->first();
        Assertion::notEmpty($user, 'user is not found');
        return $user->wallets()->get()->count();
    }

    /**
     * @param User $user
     * @return array
     */
    public function all(User $user): array
    {
        return $user->wallets()->get()->toArray();
    }


    public function overAll(User $user)
    {
        $result = DB::select(DB::raw('select sum(balance) as result from wallets where  user_id = :id'), ['id' => $user->id]);
        return $result[0]->result;
    }
}
