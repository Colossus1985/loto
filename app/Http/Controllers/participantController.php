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
            // 'inputEmail' => 'email',
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
                    ->with('error', $pseudo.' déjà existant!');
            }
        } else {
            $participantExist = Participants::query()
                ->where('email', '=', $email)
                ->get();
            
            if (count($participantExist) != 0) {
                return redirect()->back()
                    ->with('error', $email.' déjà existant!');
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
            ->with('success', $pseudo." enregistré(e) avec succès !");
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
                ->with('error', 'La mise à jour a échoué!');
        }

        
        $user = User::find($idParticipant);
        if ($request -> inputPassword != "" || $request -> inputPassword != null) {
            $user->password = Hash::make($request -> inputPassword);
        } 
        try {
            $user->save();
        } catch (Exception $e){
            return redirect()->back()
                ->with('error', 'La mise à jour a échoué!');
        }

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
            // dd($participant);
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

//####################################################################

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
        $regexInputName = "/^(\s)*[A-Za-z]+((\s)?((\'|\-|\.)?([A-Za-zéèîôàêç@])*))*(\s)*$/";
        $regexInputGeneral = "/^(\s)*[A-Za-z0-9]+((\s)?((\'|\-|\.)?([A-Za-z0-9éèîôàêç@])*))*(\s)*$/";
        $regexPhone = "/^([0-9]*)$/";

        $pwd_actuel = $request->inputPasswordActuel;
        
        if ($request->inputPasswordActuel != null || $request->inputPasswordActuel != "") {
            if (!preg_match($regexInputGeneral, $pwd_actuel)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères trop spéciaux dans le mot de passe!"]);
            return $arrayControles;
            }
        }

        if (($request->inputPassword != null || $request->inputPassword != '') 
            && ($request->inputPassword_confirmation != null || $request->inputPassword_confirmation != '')) {
            $pwd_one = $request->inputPassword;
            $pwd_two = $request->inputPassword_confirmation;
            if (!preg_match($regexInputGeneral, $pwd_one) 
                || (!preg_match($regexInputGeneral, $pwd_two))) {
                array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères trop spéciaux dans le nouveau mot de passe!"]);
                return $arrayControles;
            }
            if ($pwd_one != $pwd_two) {
            array_push($arrayControles, ['bool' => false, 'message' => "Les deux nouveaux mot de passes ne correspondent pas!"]);
            return $arrayControles;
            }
        }
        
        $phone = $request->inputTel;
        if (!preg_match($regexPhone, $phone)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Veuillez rentrer seulement des numéros sans espaces pour le numéro de téléphone, s'il vous plait!"]);
            return $arrayControles;
        }

        $firstName = $request->inputFirstName;
        $lastName = $request->inputLastName;
        $pseudo = $request->inputPseudo;
        
        $email = $request->inputEmail;
        // dd($firstName);
        if (!preg_match($regexInputName, $firstName)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux et chiffres dans le prenom!"]);
            return $arrayControles;
        }
        if (!preg_match($regexInputName, $lastName)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux et chiffres dans le nom!"]);
            return $arrayControles;
        }
        if (!preg_match($regexInputGeneral, $pseudo)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux dans le pseudo!"]);
            return $arrayControles;
        }
        if ($request->inputEmail != null || $request->inputEmail != "") {
            if (!preg_match($regexInputGeneral, $email)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux dans le email!"]);
            return $arrayControles;
            }
        }
        

//###---If all is alright sending back 'true' with empty message---###

        array_push($arrayControles, ['bool' => true, 'message' => ""]);
            return $arrayControles;
    }
}



