<?php

use App\Http\Controllers\gainController;
use App\Http\Controllers\moneyController;
use App\Http\Controllers\participantController;
use Illuminate\Support\Facades\Route;

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
Route::get('', [participantController::class, 'home'])->name('home');
Route::get('participant/{idParticipant}', [participantController::class, 'participant'])->name('participant');
Route::post('addParticipant', [participantController::class, 'addParticipant'])->name('addParticipant');
Route::delete('participantDelete/{idParticipant}', [participantController::class, 'participantDelete'])->name('participantDelete');
Route::post('updateParticipant/{idParticipant}', [participantController::class, 'updateParticipant'])->name('updateParticipant');
Route::get('searchParticipant', [participantController::class, 'searchParticipant'])->name('searchParticipant');

Route::post('addMoney/{idParticipant}', [moneyController::class, 'addMoney'])->name('addMoney');
Route::post('debitMoney/{idParticipant}', [moneyController::class, 'debitMoney'])->name('debitMoney');
Route::post('retriveMoney/{idParticipant}', [moneyController::class, 'retriveMoney'])->name('retriveMoney');
Route::post('debitAll', [moneyController::class, 'debitAll'])->name('debitAll');

Route::post('addGain', [gainController::class, 'addGain'])->name('addGain');
Route::get('getGainHistory', [gainController::class, 'getGainHistory'])->name('getGainHistory');
