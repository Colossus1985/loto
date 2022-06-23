<?php

namespace App\Http\Controllers;

use App\Models\Gains;
use App\Models\Money;
use App\Models\Participants;
use Illuminate\Http\Request;

class gainController extends Controller
{
    public function addGain(Request $request)
    {
        $arrayParticipantWin = $request->inputParticipantWinArray;
        
        $gainValue = $request->inputAmount;
        $nbPersonnes = count($arrayParticipantWin);
        $gainIndividuel = bcdiv($gainValue, $nbPersonnes, 2); //downRounding 0.9999 = 0.99

        $gain = new Gains();
        $gain->amount = $gainValue;
        $gain->date = $request->inputDate;
        $gain->nbPersonnes = $nbPersonnes;
        $gain->gainIndividuel = $gainIndividuel;
        $gain->save();
        
        $addMoney = $request->inputAddGainAuto;
        if ($addMoney === "true") {
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

                $credit = $gainIndividuel;
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
                $action->creditGain = number_format($credit, 2);
                $action->save();
            }
            return redirect()->back()
                ->with('success', 'felicitation, votre gain de '. $gainValue. '€ à été enrégistré et partagé parmi les participant(s)! 🥳🥳🥳🥳🥳🥳');
        }

        return redirect()->back()
            ->with('success', 'felicitation, votre gain de '. $gainValue. '€ à été enrégistré. Pense à la distribution du Gain ;) 🥳🥳🥳🥳🥳🥳');
    }

    public function getGainHistory()
    {
        $participants = Participants::query()
            ->get();
        
        $gains = Gains::query()
            ->orderBy('date', 'desc')
            ->get();
        
        $sommeGains = 0.00;
        if (count($gains) != 0) {
            for ($i = 0; $i < count($gains); $i++) {
                $sommeGains = $sommeGains + $gains[$i]->amount;
            }
            $sommeGains = number_format($sommeGains, 2);
        }
        
        return view('pages.gains', ['gains' => $gains, 'sommeGains' => $sommeGains, 'participants' => $participants]);
    } 
}
