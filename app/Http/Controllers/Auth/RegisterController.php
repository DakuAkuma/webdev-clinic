<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Patient;
use Validator;
use App\Http\Controllers\Controller;
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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/profile';

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
            'surname' => 'required|min:2|max:48',
            'name' => 'required|min:2|max:48',
            'patronymic' => 'nullable|min:2|max:48',
            'address' => 'required|min:25|max:255',
            'login' => 'required|min:5|max:32|unique:users,login',
            'seria' => 'required|size:4',
            'nomer' => 'required|size:6',
            'phone' => 'required|min:11|max:20',
            'birthdate' => 'required|date|after:' . date('d.m.Y', strtotime("-110 years")) . '|before:' . date("d.m.Y", strtotime("-18 years")),
            'class' => 'required'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $newUser = User::create([
            'login' => $data['login'],
            'password' => md5($data['seria'] . $data['nomer']),
            'role' => 'patient',
        ]);

        Patient::create([
            'surname' => $data['surname'],
            'name' => $data['name'],
            'patronymic' => $data['patronymic'],
            'tel' => $data['phone'],
            'address' => $data['address'],
            'birthdate' => $data['birthdate'],
            'passport' => $data['seria'] . " " . $data['nomer'],
            'class' => $data['class'],
            'status' => 'Здоров',
            'user_id' => $newUser->id
        ]);
        
        session()->put('userInfo', $newUser);
        return $newUser;
    }
}
