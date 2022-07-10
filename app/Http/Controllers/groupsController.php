<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\Participants;
use Illuminate\Http\Request;

class groupsController extends Controller
{
    public function addGroup(Request $request)
    {
        $controle = $this->controlesInputs($request);
        if (!$controle[0]['bool']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

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
            ->with('success', 'Le groupe "'.$nameGroup.'" à été crée avec succès!');
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

    public function controlesInputs($request)
    {
        $arrayControles = [];
        $regexInputName = "/^(\s)*[A-Za-z0-9éèîôàêç@]+((\s)?((\'|\-|\.)?([A-Za-z0-9éèîôàêç@])*))*(\s)*$/";

        $nameGroup = $request->inputNameGroup;
        if (!preg_match($regexInputName, $nameGroup)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux dans le nom du groupe!"]);
            return $arrayControles;
        }

//###---If all is alright, sending back true with empty message---###
        array_push($arrayControles, ['bool' => true, 'message' => ""]);
            return $arrayControles;
    }
}
