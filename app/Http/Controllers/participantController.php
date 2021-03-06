<?php

namespace App\Http\Controllers;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;
use App\Models\User;
use Egulias\EmailValidator\Parser\PartParser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class participantController extends Controller
{
    public function home($userId)
    {
        if ($userId == "all") {
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
        } else {
            $participants = Participants::query()
            ->where('id', '=', $userId)
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
    }

    public function addParticipant(Request $request)
    {
        $controle = $this->controlesInputs($request);
        if (!$controle[0]['bool']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $request -> validate([
            'inputFirstName' => 'required',
            'inputLastName' => 'required',
            'inputPseudo' => 'required',
            'inputTel' => 'required',
            'inputPassword' => 'required',
            'inputPassword_confirmation' => 'required|same:inputPassword'
        ]);

        $pseudo = $request->inputPseudo;
        $email = $request->inputEmail;
        $password = $request->inputPassword;

//###---controle if pseudo or mail are already in use---###############################################
        if ($email == null || $email == "") {
            $participantExist = Participants::query()
                ->where('pseudo', '=', $pseudo)
                ->get();
            
            if (count($participantExist) != 0) {
                return redirect()->back()
                    ->with('error', $pseudo.' d??j?? existant!');
            }
        } else {
            $participantExist = Participants::query()
                ->where('email', '=', $email)
                ->get();
            
            if (count($participantExist) != 0) {
                return redirect()->back()
                    ->with('error', $email.' d??j?? existant!');
            }
        }

//###---Add participant in UsersTable---###############################################
        $user = new User();
        $user->firstName = $request->inputFirstName;  
        $user->lastName = $request->inputLastName; 
        $user->pseudo = $pseudo;   
        $user->email = $email;  
        $user->phone = $request->inputTel;  
        $user->password = Hash::make($password);
        $user->save();

//###---Add participant in ParticipantsTable---###############################################
        $participant = new Participants();
        $participant->firstName = $request->inputFirstName;
        $participant->lastName = $request->inputLastName;
        $participant->nameGroup = $request->inputNameGroup;
        $participant->pseudo = $pseudo;
        $participant->email = $email;
        $participant->tel = $request->inputTel;
        $participant->save();

//###---Add participant in moneyTable---###############################################
        $participant = Participants::query()
            ->where('pseudo', '=', $pseudo)
            ->get();
        $id_pseudo = $participant[0]->id;
        $money = new Money();
        $money->pseudo = $pseudo;
        $money->id_pseudo = $id_pseudo;
        $money->save();

        return redirect()->back()
            ->with('success', $pseudo." enregistr??(e) avec succ??s !");
    }

    public function participantDelete($idParticipant)
    {
        
        $participant = Participants::query()
            ->select('pseudo')
            ->where('id', '=', $idParticipant)
            ->get();
        $pseudo = $participant[0]->pseudo;

        DB::table('participants')
            ->where('id', '=', $idParticipant)
            ->delete();

        DB::table('users')
            ->where('pseudo', '=', $pseudo)
            ->delete();

        if (Auth::user()->admin == 1 && Auth::user()->id != $idParticipant) {
            return redirect()->route('home', 'all')
                ->with('success', $pseudo.' a ??t?? supprim?? avec succ??s!');

        } else if (Auth::user()->admin == 1 && Auth::user()->id == $idParticipant) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('logReg')
                ->with('success', 'Vous devez vous reenrigestrez');

        } else if (Auth::user()->admin == 0) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('logReg')
                ->with('success', 'Vous ne faite plus parti(e) de "Loto avec Flo"');
        }
            
        
    }

    public function updateParticipant(Request $request, $idParticipant)
    {
        $request->validate([
            'inputPasswordActuel' => 'required|current_password',
        ]);

        $controle = $this->controlesInputs($request);
        if (!$controle[0]['bool']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $pseudo = $request->inputPseudo;

        $inputNameGroupNew = $request->inputNameGroupNew;
        if  ($inputNameGroupNew == "" || $inputNameGroupNew == Null) {
            $inputNameGroup = $request->inputNameGroupOld;
        } elseif ($inputNameGroupNew == "pas de groupe") {
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
        try {
            $participant->save();
        } catch (Exception $e){
            return redirect()->back()
                ->with('error', 'La mise ?? jour a ??chou??!');
        }
        
        $user = User::find($idParticipant);
        if ($request -> inputPassword != "" || $request -> inputPassword != null) {
            $user->password = Hash::make($request -> inputPassword);
        } 
        try {
            $user->save();
        } catch (Exception $e){
            return redirect()->back()
                ->with('error', 'La mise ?? jour a ??chou??!');
        }

        return redirect()->back()
            ->with('success', $pseudo.' mis(e) ?? jour!');
    }
    
    public function participant($idParticipant)
    {
        $participants = Participants::query()
            ->get();

        $participant = Participants::query()
            ->where('id', '=', $idParticipant)
            ->get();
            // dd($participant);
        // dd($participant[0]->nameGroup);
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
        $controle = $this->controlesInputs($request);
        if (!$controle[0]['bool']) {
            return redirect()->back()
                    ->with('error', $controle[0]['message']);
        }

        $userSearched = $request->inputParticipant;
        
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

    public function changeGroup(Request $request, $idParticipant)
    {
        // dd($request);
        $participant = Participants::find($idParticipant);
        $participant->nameGroup = $request->inputNameGroupNew;
        try {
            $participant->save();
        } catch(Exception) {
            return redirect()->back()
                ->with('error', 'Un probl??me de mise a jour a ??t?? rencontr?? !');
        }
        
        return redirect()->back()
            ->with('success', 'Modification de groupe a ??t?? enregistr??e avec succ??s !');
    }

//####################################################################################################

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
    
    public function controlesInputs($request)
    {
        $arrayControles = [];
        $regexInputName = "/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-z??????????????@])*))*(\s)*$/";
        $regexInputGeneral = "/^(\s)*[A-Za-z0-9]+((\s)?((\'|\-|\.)?([A-Za-z0-9??????????????@])*))*(\s)*$/";
        $regexPhone = "/^([0-9]*)$/";
        
        if ($request->inputPasswordActuel != null || $request->inputPasswordActuel != "") {
            $pwd_actuel = $request->inputPasswordActuel;
            if (!preg_match($regexInputGeneral, $pwd_actuel)) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res trop sp??ciaux dans le mot de passe!"]);
                return $arrayControles;
            }
        }

        if (($request->inputPassword != null || $request->inputPassword != '') 
            && ($request->inputPassword_confirmation != null || $request->inputPassword_confirmation != '')) {
            $pwd_one = $request->inputPassword;
            $pwd_two = $request->inputPassword_confirmation;
            if (!preg_match($regexInputGeneral, $pwd_one) 
                || (!preg_match($regexInputGeneral, $pwd_two))) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res trop sp??ciaux dans le nouveau mot de passe!"]);
                return $arrayControles;
            }
            if ($pwd_one != $pwd_two) {
                array_push($arrayControles, ['bool' => false, 'message' => "Les deux nouveaux mot de passes ne correspondent pas!"]);
                return $arrayControles;
            }
        }
        
        if ($request->inputTel != null || $request->inputTel != '') {
            $phone = $request->inputTel;
            if (!preg_match($regexPhone, $phone)) {
                array_push($arrayControles, ['bool' => false, 'message' => "Veuillez rentrer seulement des num??ros sans espaces pour le num??ro de t??l??phone, s'il vous plait!"]);
                return $arrayControles;
            }
        }
        
        if ($request->inputFirstName != null || $request->inputFirstName != '') {
            $firstName = $request->inputFirstName;
            if (!preg_match($regexInputName, $firstName)) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res sp??ciaux et chiffres dans le prenom!"]);
                return $arrayControles;
            }
        }

        if ($request->inputLastName != null || $request->inputLastName != '') {
            $lastName = $request->inputLastName;
            if (!preg_match($regexInputName, $lastName)) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res sp??ciaux et chiffres dans le nom!"]);
                return $arrayControles;
            }
        }
        
        if ($request->inputPseudo != null || $request->inputPseudo != '') {
            $pseudo = $request->inputPseudo;
            if (!preg_match($regexInputGeneral, $pseudo)) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res sp??ciaux dans le pseudo!"]);
                return $arrayControles;
            }
        }

        if ($request->inputEmail != null || $request->inputEmail != "") {
            $email = $request->inputEmail;
            if (!preg_match($regexInputGeneral, $email)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res sp??ciaux dans le email!"]);
            return $arrayControles;
            }
        }

        if ($request->inputParticipant != null || $request->inputParticipant != '') {
            $pseudoSearch = $request->inputParticipant;
            if (!preg_match($regexInputGeneral, $pseudoSearch)) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charact??res sp??ciaux dans le champs de recherche!"]);
                return $arrayControles;
            }
        }
        
        //###---If all is alright sending back true with empty message---###

        array_push($arrayControles, ['bool' => true, 'message' => ""]);
            return $arrayControles;
    }
}



