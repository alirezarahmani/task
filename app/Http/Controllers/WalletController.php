<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AddRequest;
use App\Http\Requests\SubtractRequest;
use App\Http\Requests\WalletRequest;
use App\Lib\Currency;
use App\Lib\WalletTypes;
use App\Service\WalletService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class WalletController
 * @package App\Http\Controllers
 */
class WalletController extends Controller
{
    /**
     * @var WalletService
     */
    private WalletService $service;

    /**
     * WalletController constructor.
     * @param WalletService $service
     */
    public function __construct(WalletService $service)
    {
        $this->middleware('auth');
        /** @var WalletService service */
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        return view('wallet/add')->with(['types' => WalletTypes::allTags()]);
    }

    /**
     * @param WalletRequest $walletRequest
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(WalletRequest $walletRequest)
    {
        try {
            $email = Auth::guard('web')->user()->email;
            $this->service->add($walletRequest->get('name'), $email, intval($walletRequest->get('type')));
            return redirect('home')->with( 'Thanks for registering!');
        } catch (\InvalidArgumentException | ModelNotFoundException $e) {
            return redirect('home')->withErrors(['a wallet with same name already exist']);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function balance($id)
    {
        return view('wallet/balance')->with(['id' => $id]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Assert\AssertionFailedException
     */
    public function subtract($id)
    {
        return view('wallet/subtract')->with(['id' => $id]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Assert\AssertionFailedException
     */
    public function saveBalance(AddRequest $request, int $id)
    {
        try {
            $email = Auth::user()->email;
            $amount = $request->get('credit');
            $this->service->addRecord($id, $email, $amount, new Currency());
            return redirect('home');
       } catch (\InvalidArgumentException $e) {
        return redirect('home')->withErrors([$e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function saveSubtract(SubtractRequest $request, $id)
    {
        try {
            $email = Auth::user()->email;
            $amount = $request->get('credit');
            $this->service->subtractRecord($id, $email, $amount);
            return redirect('home')->withErrors(['Thanks for registering!']);
        } catch (\InvalidArgumentException $e) {
            return redirect('home')->withErrors(['you can not subtract more than your balance']);
        }
    }
}
