<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            //'username' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string',  'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $creator = [
            'email'=>$data['email'],
            'password' => Hash::make($data['password']),
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'is_student' => $data['student'],
            'is_olydorf' => $data['olydorf'],
            'shirt_cut' => $data['shirtCut'],
            'shirt_size' => $data['shirtSize'],
            'birthday' => $data['birthday'],
            'mobile' => $data['phone'],
            'about_you' => $data['aboutyou'],
            'has_gesundheitszeugnis' => $data['gesundheitszeugnis'],
        ];

        if(isset($data['studiengang'])) {
            $creator['studiengang'] = $data['studiengang'];
        }
        if(isset($data['uni'])) {
            $creator['uni'] = $data['uni'];
        }
        if(isset($data['semester'])) {
            $creator['semester'] = $data['semester'];
        }
        if(isset($data['olycat'])) {
            $creator['oly_cat'] = $data['olycat'];
            $c = $data['olycat'];
            if($c == 'Bungalow') {
                $creator['street'] = 'Connollystraße';
                $creator['hausnummer'] = '3';
            }
            else if($c == 'Hochhaus') {
                $creator['street'] = 'Helene-Mayer-Ring';
                $creator['hausnummer'] = '7';
            }
            $creator['plz']='80809';
            $creator['ort'] = 'München';
        }
        if(isset($data['isPraside']) || isset($data['ausschussSelect']) ) {
            $creator['is_pflichtschicht'] = '1';
        }
        if(isset($data['olycatDetail'])) {
            $creator['oly_room'] = $data['olycatDetail'];
        }
        if(isset($data['vereinSel'])) {
            $creator['is_verein'] = $data['vereinSel'];
        }
        if(isset($data['isBierstube'])) {
            $creator['is_bierstube'] = $data['isBierstube'];
        }
        if(isset($data['isDisco'])) {
            $creator['is_disco'] = $data['isDisco'];
        }
        if(isset($data['isPraside'])) {
            $creator['is_praside'] = $data['isPraside'];
        }
        if(isset($data['isDauerjob'])) {
            $creator['is_dauerjob'] = $data['isDauerjob'];
        }
        if(isset($data['ausschussSelect'])) {
            $creator['ausschuss'] = $data['ausschussSelect'];
        }
        if(isset($data['strasse'])) {
            $creator['street'] = $data['strasse'];
        }
        if(isset($data['hausnummer'])) {
            $creator['hausnummer'] = $data['hausnummer'];
        }
        if(isset($data['plz'])) {
            $creator['plz'] = $data['plz'];
        }
        if(isset($data['ort'])) {
            $creator['ort'] = $data['ort'];
        }
        if(isset($data['ehemalig'])) {
            $creator['is_ehemalig'] = $data['ehemalig'];
        }

        return User::create($creator);

        /*return User::create([
            //'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'is_student' => $data['student'],
            'is_olydorf' => $data['olydorf'],
            'shirt_cut' => $data['shirtCut'],
            'shirt_size' => $data['shirtSize'],
            'birthday' => $data['birthday'],
            'mobile' => $data['phone'],
            'studiengang' => $data['studiengang'],
            'uni' => $data['uni'],
            'semester' => $data['semester'],
            'oly_cat' => $data['olycat'],
            'oly_room' => $data['olycatDetail'],
            'is_verein' => $data['vereinSel'],
            'is_bierstube' => $data['isBierstube'],
            'is_disco' => $data['isDisco'],
            'is_praside' => $data['isPraside'],
            'is_dauerjob' => $data['isDauerjob'],
            'ausschuss' => $data['ausschussSelect'],
            'street' => $data['strasse'],
            'hausnummer' => $data['hausnummer'],
            'plz' => $data['plz'],
            'ort' => $data['ort'],
            'is_ehemalig' => $data['ehemalig'],
            'about_you' => $data['aboutyou'],
            'has_gesundheitszeugnis' => $data['gesundheitszeugnis'],
        ]);*/
    }
}
