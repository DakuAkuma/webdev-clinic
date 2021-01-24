<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Patient;
use App\Doctor;
use App\Employee;

class DefaultRoutesController extends Controller
{
    public function main() {
        $amountPatients = Patient::where('status', 'not like', 'Умер')->whereNotNull('user_id')->count();
        $amountDoctors = Doctor::all()->count();
        $amountEmployees = Employee::all()->count();
        //echo User::where('login', 'Akumasik')->where('password', md5('2441123511'))->get()->count();
        return view('main', ['amountPatients' => $amountPatients, 'amountDoctors' => $amountDoctors, 'amountEmployees' => $amountEmployees]);
    }

    public function profile(Request $request) {
        // Проверяем на всякий случай, авторизован ли пользователь.
         if (session()->has('userInfo')) {
            // Определяем-с кто авторизован
            if (session('userInfo')->role == "patient") {
                $request->session()->put('user', Patient::where('user_id', session('userInfo')->id)->first());
                //dd(session('user')->name);
                return view('patient');
            }
            if (session('userInfo')->role == "medic") {
                $request->session()->put('user', Doctor::where('user_id', session('userInfo')->id)->first());
                return view('medic');
            }
            if (session('userInfo')->role == "employer") {
                $request->session()->put('user', Employee::where('user_id', session('userInfo')->id)->first());
                return view('employ');
            }
            if (session('userInfo')->role == "admin") {
                return view('admin-lk');
            }
        }
        return redirect('/login')->with('status', 'non authorized');
    }
}
