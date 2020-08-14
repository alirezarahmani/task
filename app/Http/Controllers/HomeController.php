<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Repositories\WalletRepository;
use App\Service\WalletService;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var WalletService
     */
    private WalletService $service;

    /**
     * Create a new controller instance.
     * @param WalletService $walletService
     * @return void
     */
    public function __construct(WalletService $walletService)
    {
        $this->service = $walletService;
        $this->middleware(['auth','verified']);
    }

    /**
     * @return \Illuminate\Routing\Redirector|\Illuminate\View\View
     * @throws \Assert\AssertionFailedException
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::guard('web')->user();
        $first = $this->service->isEmpty($user->email);
        // check if has any wallet else send it to wallet page
        if ($first) {
            return redirect('/wallet/add')->send();
        }

        $repo = new WalletRepository();
        return view('home')->with(['wallets' => $repo->all($user), 'all' => $repo->overAll($user)]);
    }
}
