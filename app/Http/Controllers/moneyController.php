<?php

namespace App\Http\Controllers;

use App\Models\Money;
use App\Models\Participants;
use Illuminate\Http\Request;

class moneyController extends Controller
{
    public function addMoney(Request $request, $idParticipant)
    {
        $participant = Participants::query()
            ->where('id', '=', $idParticipant)
            ->get();

        $pseudo = $participant[0]->pseudo;

        $money = Money::query()
            ->where('pseudo', '=', $pseudo)
            ->orderBy('id', 'desc')
            ->get();

        $credit = $request->inputMontant;
        $amount = $money[0]->amount;
            $amount = $amount + $credit;
        $totalAmount = $participant[0]->totalAmount;
            $totalAmount = $totalAmount + $credit;
        

        $participant = Participants::find($idParticipant);
        $participant->amount = number_format($amount, 2);
        $participant->totalAmount = number_format($totalAmount, 2);
        $participant->save();

        $action = new Money();
        $action->pseudo = $pseudo;
        $action->amount = number_format($amount, 2);
        $action->credit = number_format($credit, 2);
        $action->save();

        return redirect()->back()
            ->with('success', $credit.'€ ajouté sur le compte de '. $pseudo);
    }

    public function retriveMoney(Request $request, $idParticipant)
    {
        $participant = Participants::query()
            ->where('id', '=', $idParticipant)
            ->get();

        $pseudo = $participant[0]->pseudo;

        $money = Money::query()
            ->where('pseudo', '=', $pseudo)
            ->orderBy('id', 'desc')
            ->get();

        $debit = $request->inputMontant;
        $amount = $money[0]->amount;
            $amount = $amount - $debit;
        $totalAmount = $participant[0]->totalAmount;
        

        $participant = Participants::find($idParticipant);
        $participant->amount = number_format($amount, 2);
        $participant->totalAmount = number_format($totalAmount, 2);
        $participant->save();

        $action = new Money();
        $action->pseudo = $pseudo;
        $action->amount = number_format($amount, 2);
        $action->debit = number_format($debit, 2);
        $action->save();

        return redirect()->back()
            ->with('success', $debit.'€ retiré du compte de '. $pseudo);
    }
}
