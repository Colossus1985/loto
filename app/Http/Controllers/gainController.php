<?php

namespace App\Http\Controllers;

use App\Models\Gains;
use Illuminate\Http\Request;

class gainController extends Controller
{
    public function addGain(Request $request)
    {
        
        $gainValue = $request->inputAmount;
        $nbPersonnes = $request->inputNbPersonnes;
        $gainIndividuel = bcdiv($gainValue, $nbPersonnes, 2); //downRounding 0.9999 = 0.99

        $gain = new Gains();
        $gain->amount = $gainValue;
        $gain->date = $request->inputDate;
        $gain->nbPersonnes = $nbPersonnes;
        $gain->gainIndividuel = $gainIndividuel;
        $gain->save();
        
        return redirect()->back()
            ->with('success', 'felicitation, votre gain de '. $gainValue. '€ à été enrégistré. 🥳🥳🥳🥳🥳🥳');
    }

    public function getGainHistory()
    {
        $gains = Gains::query()
            ->get();
        
        $sommeGains = 0.00;
        if (count($gains) != 0) {
            for ($i = 0; $i < count($gains); $i++) {
                $sommeGains = $sommeGains + $gains[$i]->amount;
            }
            $sommeGains = number_format($sommeGains, 2);
        }

        return view('pages.gains', ['gains' => $gains, 'sommeGains' => $sommeGains]);
    } 
}
