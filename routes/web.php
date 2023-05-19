<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(HomeController::class)->group(function() {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
});

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});


/**
 * Transaction routes
 */
Route::controller(TransactionController::class)->group(function()   {
    Route::get('/deposit', 'loadDeposit')->name('load_deposit');
    Route::post('/save-deposit', 'saveDeposit')->name('save_deposit');
    Route::get('/withdraw', 'loadWithdraw')->name('load_withdraw');
    Route::post('/save-withdraw', 'saveWithdraw')->name('save_withdraw');
    Route::get('/transfer', 'loadTransfer')->name('load_transfer');
    Route::post('/save-transfer', 'saveTransfer')->name('save_transfer');
    Route::get('/statement', 'statement')->name('load_statement');
});