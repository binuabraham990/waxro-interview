<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller {

    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct() {
        $this->middleware('guest')->except([
            'loadDeposit', 'saveDeposit', 'loadWithdraw', 'saveWithdraw', 'loadTransfer', 'saveTransfer', 'statement'
        ]);
    }

    /**
     * Display a deposit form.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadDeposit() {

        return view('transaction.deposit');
    }

    /**
     * Save deposit value to the account of logged in user
     * 
     * @param Request $request
     * @return type
     */
    public function saveDeposit(Request $request) {

        $amount = $request->amount;
        $userId = auth()->user()->id;

        DB::beginTransaction();

        try {

            $balance = Balance::query()->where('user_id', $userId)->first();
            if (!$balance) {
                $balance = Balance::create([
                            'user_id' => $userId,
                            'balance' => $amount
                ]);
            } else {
                $balance->update([
                    'balance' => $balance->balance + $amount
                ]);
            }

            Transaction::create([
                'amount' => $amount,
                'type' => 'Credit',
                'details' => 'Deposit',
                'balance' => $balance->balance,
                'user_id' => $userId
            ]);
            DB::commit();
        } catch (Exception $exc) {
            DB::rollBack();
            return redirect()->route('load_withdraw')
                            ->withErrors([
                                'amount' => 'There is something wrong happened. Please check the process again.',
                            ])->onlyInput('amount');
        }



        return redirect('deposit');
    }

    /**
     * Load withdraw form
     * 
     * @return type
     */
    public function loadWithdraw() {

        return view('transaction.withdraw');
    }

    /**
     * Save withdraw value to the account of logged in user
     * 
     * @param Request $request
     * @return type
     */
    public function saveWithdraw(Request $request) {

        $amount = $request->amount;
        $userId = auth()->user()->id;

        DB::beginTransaction();

        try {

            $balance = Balance::query()->where('user_id', $userId)->first();
            if (!$balance || (int) $balance->balance < (int) $amount) {
                return redirect()->route('load_withdraw')
                                ->withErrors([
                                    'amount' => 'Not enough fund in your account.',
                                ])->onlyInput('amount');
            } else {
                $balance->update([
                    'balance' => $balance->balance - $amount
                ]);

                Transaction::create([
                    'amount' => $amount,
                    'type' => 'Debit',
                    'details' => 'Withdraw',
                    'balance' => $balance->balance,
                    'user_id' => $userId
                ]);
            }

            DB::commit();
        } catch (Exception $exc) {
            DB::rollBack();
            return redirect()->route('load_withdraw')
                            ->withErrors([
                                'amount' => 'There is something wrong happened. Please check the process again.',
                            ])->onlyInput('amount');
        }



        return redirect('withdraw');
    }

    /**
     * Load transfer form
     * 
     * @return type
     */
    public function loadTransfer() {

        return view('transaction.transfer');
    }

    /**
     * Do the transfer procedure
     * - Decrease from source user - logged in user
     * - Add to destination user - user as per the received email
     * 
     * @param Request $request
     * @return type
     */
    public function saveTransfer(Request $request) {

        $userId = auth()->user()->id;
        $amount = $request->amount;
        $userEmail = $request->email;

        DB::beginTransaction();

        try {

            $sourceBalance = Balance::query()->where('user_id', $userId)->first();
            $destinationUser = User::query()->where('email', $userEmail)->first();
            if (!$destinationUser) {

                return redirect()->route('load_transfer')
                                ->withErrors([
                                    'email' => 'No user exist.',
                                ])->onlyInput('email');
            }
            if (!$sourceBalance || (int) $sourceBalance->balance < (int) $amount) {
                return redirect()->route('load_transfer')
                                ->withErrors([
                                    'amount' => 'Not enough fund in your account.',
                                ])->onlyInput('amount');
            } else {
                $sourceBalance->update([
                    'balance' => $sourceBalance->balance - $amount
                ]);

                $destinationBalance = Balance::query()->where('user_id', $destinationUser->id)->first();
                if (!$destinationBalance) {
                    $destinationBalance = Balance::create([
                                'user_id' => $destinationUser->id,
                                'balance' => $amount
                    ]);
                } else {

                    $destinationBalance->update([
                        'balance' => $sourceBalance->balance + $amount
                    ]);
                }

                Transaction::create([
                    'amount' => $amount,
                    'type' => 'Debit',
                    'details' => 'Transfer to ' . $userEmail,
                    'balance' => $sourceBalance->balance,
                    'user_id' => $sourceBalance->user_id
                ]);

                Transaction::create([
                    'amount' => $amount,
                    'type' => 'Credit',
                    'details' => 'Transfer from ' . auth()->user()->email,
                    'balance' => $destinationBalance->balance,
                    'user_id' => $destinationBalance->user_id
                ]);
            }

            DB::commit();
        } catch (Exception $exc) {
            DB::rollBack();
            return redirect()->route('load_transfer')
                            ->withErrors([
                                'amount' => 'There is something wrong happened. Please check the process again.',
                            ])->onlyInput('amount');
        }



        return redirect('transfer');
    }

    /**
     * Load statements.
     * 
     * @return type
     */
    public function statement() {
        
        
        $userId = auth()->user()->id;
        $transactions = Transaction::query()->where('user_id', $userId)->get();
        
        return view('transaction.statement', ['data' => $transactions]);
    }

}
