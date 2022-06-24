<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Participants;
use Illuminate\Http\Request;

class groupsController extends Controller
{
    public function addGroup(Request $request)
    {
        $nameGroup = $request->inputNameGroup;
        $groupExists = Groups::query()
            ->where('nameGroup', '=', $nameGroup)
            ->get();
        if (count($groupExists) != 0) {
            return redirect()->back()
                ->with('error', 'Le groupe '.$nameGroup.' existe déjà!');
        }

        $group = new Groups();
        $group->nameGroup = $nameGroup;
        $group->save();

        return redirect()->back()
            ->with('success', 'Le groupe '.$nameGroup.' à été crée avec succès!');
    }

    public function participantGroup(Request $request)
    {
        $nameGroup = $request->inputNameGroup;
        $arrayParticipant = $request->inputParticipantArray;
// dd($arrayParticipant);
        for ($i = 0; $i < count($arrayParticipant); $i++) {
            $participant = Participants::query()
                ->where('pseudo', '=', $arrayParticipant[$i])
                ->get();

            $idParticipant = $participant[0]->id;

            $participantGroup = Participants::find($idParticipant);
            $participantGroup->nameGroup = $nameGroup;
            $participantGroup->save();
        }
        
        return redirect()->back()
            ->with('success', 'Nouvelles composition du group '.$nameGroup.' réussi!');
    }
}
