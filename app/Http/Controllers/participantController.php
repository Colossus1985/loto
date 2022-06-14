<?php

namespace App\Http\Controllers;

use App\Models\Money;
use App\Models\Participants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class participantController extends Controller
{
    public function home()
    {
        $participants = Participants::query()
            ->get();
        return view('pages.main', ['participants' => $participants]);
    }

    public function addParticipant(Request $request)
    {
        $participant = new Participants();
        $participant->firstName = $request->inputFirstName;
        $participant->lastName = $request->inputLastName;
        $participant->pseudo = $request->inputPseudo;
        $participant->email = $request->inputEmail;
        $participant->tel = $request->inputTel;
        $participant->save();

        $money = new Money();
        $money->pseudo = $request->inputPseudo;
        $money->save();

        $pseudo = $request->inputPseudo;

        return redirect()->back()
            ->with('success', $pseudo.' fait parti de ton groupe!');
    }

    public function participantDelete($idParticipant)
    {
        // dd($idParticipant);
        $participant = Participants::query()
            ->select('pseudo')
            ->where('id', '=', $idParticipant)
            ->get();

        DB::table('participants')
            ->where('id', '=', $idParticipant)
            ->delete();

        return redirect()->back()
            ->with('success', $participant[0]->pseudo.' a été supprimé avec succès!');
    }

    public function updateParticipant(Request $request)
    {
        return redirect()->back()
            ->with('success', 'mis(e) à jour!');
    }
    
    public function participant($idParticipant)
    {
        $participant = Participants::query()
            ->where('id', '=', $idParticipant)
            ->get();

        // dd($participant[0]->totalAmount);
        $pseudo = $participant[0]->pseudo;
        $money = Money::query()
            ->where('pseudo', '=', $pseudo)
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.participant', ['participant' => $participant, 'actions' => $money]);
    }

    public function searchParticipant(Request $request)
    {
        $userSearched = trim($request -> get('inputParticipant'));
        $participants = Participants::query()
            ->where('pseudo', 'like', "%{$userSearched}%")
            ->orWhere('email', 'like', "%{$userSearched}%")
            ->orderBy('created_at', 'ASC')
            ->get();

        return view('pages.main', ['participants' => $participants]);
    }
    
}
