<?php

namespace App\Http\Controllers;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;
use Egulias\EmailValidator\Parser\PartParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class participantController extends Controller
{
    public function home()
    {
        $participants = Participants::query()
            ->orderBy('nameGroup', 'asc')
            ->get();
        
        $groups = Groups::query()
            ->get();

        $arrayFondsByGroup = $this->fonds($groups);
        $arrayGainByGroup = $this->gains($groups);

        return view('pages.main', [
            'participants' => $participants, 
            'fonds' => $arrayFondsByGroup, 
            'sommeGainsByGroups' => $arrayGainByGroup,
            'groups' => $groups]);
    }

    public function addParticipant(Request $request)
    {
        $pseudo = $request->inputPseudo;
        $email = $request->inputEmail;

        if ($email == null || $email == "") {
            $participantExist = Participants::query()
            ->where('pseudo', '=', $pseudo)
            ->get();
            
            if (count($participantExist) != 0) {
                return redirect()->back()
                    ->with('error', $pseudo.' déjà existant!');
            }
        } else {
            $participantExist = Participants::query()
            ->where('pseudo', '=', $pseudo)
            ->where('email', '=', $email)
            ->get();
            
            if (count($participantExist) != 0) {
                return redirect()->back()
                    ->with('error', $pseudo.' ou '.$email.' déjà existant!');
            }
        }
        
        $participant = new Participants();
        $participant->firstName = $request->inputFirstName;
        $participant->lastName = $request->inputLastName;
        $participant->nameGroup = $request->inputNameGroup;
        $participant->pseudo = $pseudo;
        $participant->email = $email;
        $participant->tel = $request->inputTel;
        $participant->save();

        $participant = Participants::query()
            ->where('pseudo', '=', $pseudo)
            ->get();
        
        $id_pseudo = $participant[0]->id;

        $money = new Money();
        $money->pseudo = $request->inputPseudo;
        $money->id_pseudo = $id_pseudo;
        $money->save();

        return redirect()->back()
            ->with('success', '"'.$pseudo.'" enregistré(e)!');
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

        return redirect()->route('home')
            ->with('success', $participant[0]->pseudo.' a été supprimé avec succès!');

    }

    public function updateParticipant(Request $request, $idParticipant)
    {
        $pseudo = $request->inputPseudo;
        $inputNameGroupNew = $request->inputNameGroupNew;
        if  ($inputNameGroupNew == "" || $inputNameGroupNew == Null) {
            $inputNameGroup = $request->inputNameGroupOld;
        } elseif ($inputNameGroupNew == "null") {
            $inputNameGroup = Null;
        } else {
            $inputNameGroup = $inputNameGroupNew;
        }
        
        $participant = Participants::find($idParticipant);
        $participant->firstName = $request->inputFirstName;
        $participant->lastName = $request->inputLastName;
        $participant->nameGroup = $inputNameGroup;
        $participant->pseudo = $request->inputPseudo;
        $participant->email = $request->inputEmail;
        $participant->tel = $request->inputTel;
        $participant->save();

        return redirect()->back()
            ->with('success', $pseudo.' mis(e) à jour!');
    }
    
    public function participant($idParticipant)
    {
        $participants = Participants::query()
            ->get();

        $participant = Participants::query()
            ->where('id', '=', $idParticipant)
            ->get();

        // dd($participant[0]->totalAmount);
        $id_pseudo = $participant[0]->id;
        $money = Money::query()
            ->where('id_pseudo', '=', $id_pseudo)
            ->orderBy('id', 'desc')
            ->get();

        $groups = Groups::query()
            ->get();

        $arrayFondsByGroup = $this->fonds($groups);
        $arrayGainByGroup = $this->gains($groups);

        return view('pages.participant', 
            ['participant' => $participant, 
            'actions' => $money, 
            'participants' => $participants,
            'fonds' => $arrayFondsByGroup,
            'sommeGainsByGroups' => $arrayGainByGroup,
            'groups' => $groups]);
    }

    public function searchParticipant(Request $request)
    {
        $userSearched = trim($request -> get('inputParticipant'));
        
        $participants = Participants::query()
            ->where('pseudo', 'like', "%{$userSearched}%")
            ->orderBy('created_at', 'ASC')
            ->get();

        if (count($participants) == 0) {
            return redirect()->route('home')
                ->with('error', 'il n\'y pas de Pseudo contenant "'.$userSearched.'"');
        }

        $groups = Groups::query()
            ->get();

        $arrayFondsByGroup = $this->fonds($groups);
        $arrayGainByGroup = $this->gains($groups);

        return view('pages.main', [
            'participants' => $participants, 
            'fonds' => $arrayFondsByGroup, 
            'sommeGainsByGroups' => $arrayGainByGroup,
            'groups' => $groups]);
    }

    public function fonds($groups)
    {
        $arrayFondsByGroup = [];
        if (count($groups) != 0) {
            for ($i = 0;  $i < count($groups) ; $i++) {
                $participantOfGroup = Participants::query()
                    ->where('nameGroup', '=', $groups[$i]->nameGroup)
                    ->get();

                $fonds = 0.00;
                if (count($participantOfGroup) != 0) {
                    for ($a = 0;  $a < count($participantOfGroup); $a++) {
                        $fonds = $fonds + $participantOfGroup[$a]->amount;
                    }

                    $fonds = number_format($fonds, 2);
                    array_push($arrayFondsByGroup, ['nameGroup' => $groups[$i]->nameGroup, 'fonds' => $fonds]);
                    
                }
            }
        }
        return $arrayFondsByGroup;
    }

    public function gains($groups)
    {
        $arrayGainByGroup = [];
        if (count($groups) != 0) {
            for ($i = 0;  $i < count($groups) ; $i++) {
               
                $gainGroup = Gains::query()
                    ->where('nameGroup', '=', $groups[$i]->nameGroup)
                    ->get();

                $sommeGains = 0.00;
                if (count($gainGroup) != 0) {
                    for ($a = 0; $a < count($gainGroup); $a++) {
                        $sommeGains = $sommeGains + $gainGroup[$a]->amount;
                    }   
                    $sommeGains = number_format($sommeGains, 2);
                    array_push($arrayGainByGroup, ['nameGroup' => $groups[$i]->nameGroup, 'sommeGains' => $sommeGains]); 
                    
                } 
            }
        }
        return $arrayGainByGroup;
    }
    
}



