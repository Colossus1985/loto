<?php

namespace App\Http\Controllers;

use App\Models\Gains;
use App\Models\Groups;
use App\Models\Money;
use App\Models\Participants;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class logController extends Controller
{
    public function logReg()
    {
        $participants = Participants::query()
            ->orderBy('nameGroup', 'asc')
            ->get();
        
        $groups = Groups::query()
            ->get();

        $arrayFondsByGroup = $this->fonds($groups);
        $arrayGainByGroup = $this->gains($groups);

        return view('pages.log', [
            'participants' => $participants, 
            'fonds' => $arrayFondsByGroup, 
            'sommeGainsByGroups' => $arrayGainByGroup,
            'groups' => $groups]);
    }

    public function register_action(Request $request) 
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
        $user = new Users();
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
            ->with('success', "Félicitations, vous etes enrégistré en tant que ".$pseudo." ! Veuillez vous loguer");
    }

    public function login_action(Request $request)
    {
        $request->validate([
            'inputRegister' => 'required',
            'password' => 'required',
        ]);
        
        if (Auth::attempt(['pseudo' => $request->inputRegister, 'password' => $request->password])) {
            $request->session()->regenerate();
            if (Auth::user()->admin == 1) {
                return redirect()->route('home', 'all')
                    ->with('success', 'Welcome '.Auth::user()->pseudo);
            } else {
                return redirect()->route('home', Auth::user()->id)
                    ->with('success', 'Welcome '.Auth::user()->pseudo);
            }
        } elseif (Auth::attempt(['email' => $request->inputRegister, 'password' => $request->password])) {
            $request->session()->regenerate();
            if (Auth::user()->admin == 1) {
                return redirect()->route('home', 'all')
                    ->with('success', 'Welcome '.Auth::user()->pseudo);
            } else {
                return redirect()->route('home', Auth::user()->id)
                    ->with('success', 'Welcome '.Auth::user()->pseudo);
            }
        }
        return back() 
            ->with('error', 'wrong identifier or password');
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('logReg')
            ->with('success', 'Vous etes deloggué(e). A bientot');
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
        $regexInputPseudoPdw = "/^(\s)*[A-Za-z0-9]+((\s)?((\'|\-|\.)?([A-Za-z0-9éèîôàêç@])*))*(\s)*$/";
        $regexPhone = "/^([0-9]*)$/";

        $pwd_one = $request->inputPassword;
        $pwd_two = $request->inputPassword_confirmation;
        if (!preg_match($regexInputPseudoPdw, $pwd_one) 
            || (!preg_match($regexInputPseudoPdw, $pwd_two))) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères trop spéciaux dans le mot de passe!"]);
            return $arrayControles;
        }
        if ($pwd_one != $pwd_two) {
            array_push($arrayControles, ['bool' => false, 'message' => "Les deux mot de passes ne correspondent pas!"]);
            return $arrayControles;
        }

        $phone = $request->inputTel;
        if (!preg_match($regexPhone, $phone)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Veuillez rentrer seulement des numéros sans espaces pour le numéro de téléphone, s'il vous plait!"]);
            return $arrayControles;
        }

        $firstName = $request->inputFirstName;
        $lastName = $request->inputLastName;
        $pseudo = $request->inputPseudo;
        // dd($firstName);
        if (!preg_match($regexInputName, $firstName)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux et chiffres dans le prenom!"]);
            return $arrayControles;
        }
        if (!preg_match($regexInputName, $lastName)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux et chiffres dans le nom!"]);
            return $arrayControles;
        }
        if (!preg_match($regexInputPseudoPdw, $pseudo)) {
            array_push($arrayControles, ['bool' => false, 'message' => "Attention aux charactères spéciaux dans le pseudo!"]);
            return $arrayControles;
        }
    }
}
