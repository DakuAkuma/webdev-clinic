<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DefaultRoutesController extends Controller
{
    public function main() {
        $amountPatients = DB::table('clinic-patients')
                                ->where([['status', 'not like', 'Умер']])
                                ->whereNotNull('user_id')
                                ->count();
        $amountDoctors = DB::table('clinic-doctors')
                                ->whereNotNull('user_id')
                                ->count();
        $amountEmploy = DB::table('clinic-employees')
                                ->whereNotNull('user_id')
                                ->count();
        return view('main', ['amountPatients' => $amountPatients, 'amountDoctors' => $amountDoctors, 'amountEmploy' => $amountEmploy]);
    }

    public function sign_in() {
        // Проверяем, авторизован ли пользователь.
        if (session()->has('userInfo')) {
            return redirect('/users/profile');
        }
        return view('auth');
    }

    public function sign_up() {
        // Проверяем, авторизован ли пользователь.
        if (session()->has('userInfo')) {
            return redirect('/users/profile');
        }
        return view('sign_up');
    }

    public function profile() {
         // Проверяем на всякий случай, авторизован ли пользователь.
         if (session()->has('userInfo')) {
            // Определяем-с кто авторизован
            if (session('userInfo')->role == "patient") {
                return view('patient');
            }
            if (session('userInfo')->role == "medic") {
                return view('medic');
            }
            if (session('userInfo')->role == "employer") {
                return view('employ');
            }
            if (session('userInfo')->role == "admin") {
                return view('admin-lk');
            }
        }
        return redirect('/users/sign_in')->with('status', 'non authorized');
    }
}
