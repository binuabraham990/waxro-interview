<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Balance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;


class HomeController extends Controller {

    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct() {
        $this->middleware('guest')->except([
            'dashboard'
        ]);
    }

    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request) {

        if (Auth::check()) {
            
            $data = DB::table('users')
                    ->join('balances', 'users.id', '=', 'balances.user_id')
                    ->select('users.name', 'users.email', 'balances.balance')
                    ->where('users.id', auth()->user()->id)
                    ->first();
            return View::make('home.dashboard', compact('data'));
        }

        return redirect()->route('login')
                        ->withErrors([
                            'email' => 'Please login to access the dashboard.',
                        ])->onlyInput('email');
    }

}
