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
            ->where('id_pseudo', '=', $idParticipant)
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
        $action->id_pseudo = $idParticipant;
        $action->amount = number_format($amount, 2);
        $action->credit = number_format($credit, 2);
        $action->save();

        return redirect()->back()
            ->with('success', $credit.'€ ajouté sur le compte de '. $pseudo);
    }

    public function debitMoney(Request $request, $idParticipant)
    {
        $participant = Participants::query()
            ->where('id', '=', $idParticipant)
            ->get();

        $pseudo = $participant[0]->pseudo;

        $money = Money::query()
            ->where('id_pseudo', '=', $idParticipant)
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
        $action->id_pseudo = $idParticipant;
        $action->amount = number_format($amount, 2);
        $action->debit = number_format($debit, 2);
        $action->save();

        return redirect()->back()
            ->with('success', $debit.'€ retiré du compte de '. $pseudo);
    }

    public function debitAll(Request $request)
    {
        $arrayParticipantWin = $request->inputParticipantWinArray;

        $debitValue = $request->inputAmount;
        $nbPersonnes = count($arrayParticipantWin);
        $debitIndividuel = bcdiv($debitValue, $nbPersonnes, 2); //downRounding 0.9999 = 0.99
        
        for ($i = 0; $i < count($arrayParticipantWin); $i++) {
            $participant = Participants::query()
                ->where('pseudo', '=', $arrayParticipantWin[$i])
                ->get();
            $idParticipant = $participant[0]->id;
                
            $pseudo = $participant[0]->pseudo;

            $money = Money::query()
                ->where('id_pseudo', '=', $idParticipant)
                ->orderBy('id', 'desc')
                ->get();

            $debit = $debitIndividuel;
            $amount = $money[0]->amount;
                $amount = $amount - $debit;
            $totalAmount = $participant[0]->totalAmount;
        
            $participant = Participants::find($idParticipant);
            $participant->amount = number_format($amount, 2);
            $participant->totalAmount = number_format($totalAmount, 2);
            $participant->save();
        
            $action = new Money();
            $action->pseudo = $pseudo;
            $action->id_pseudo = $idParticipant;
            $action->amount = number_format($amount, 2);
            $action->debit = number_format($debit, 2);
            $action->save();
        }

        return redirect()->back()
            ->with('success', $debit.' € retiré des comptes des Participant');
    }
        
}
