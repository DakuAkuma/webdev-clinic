<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {
        //dd($request);
        // Процесс валидации данных
        $this->validate($request, [
            'login' => 'required|max:32',
            'password' => 'required|max:255'
        ]);
        //Ищем такого пользователя в таблице.
        $count = DB::table('clinic-users')->select('id')->where([ 
            ['login', $request->input('login')]
        ])->count();
        // Если не находим, редирект на страницу регистрации.
        if ($count == 0) {
            return redirect('/users/sign_up')->with('status', 'no such user');
        }
        // Пытаемся получить данные пользователя с введеным паролем.
        $userInfo = DB::table('clinic-users')->select('id', 'login', 'role')->where([ 
            ['login', $request->input('login')], 
            ['password', md5($request->input('password'))]
        ])->first();
        // В случае ввода неверного пароля, редирект на страницу входа.
        if ($userInfo == null) {
            return redirect('/users/sign_in')->with('status', 'incorrect password');
        }
        $currentUser;
        if ($userInfo->role == "admin") {
            $currentUser = $userInfo;
        }
        elseif ($userInfo->role == "patient") {
            $currentUser = DB::table('clinic-patients')->where('user_id', $userInfo->id)->first();
        }
        elseif ($userInfo->role == "medic") {
            $currentUser = DB::table('clinic-doctors')->where('user_id', $userInfo->id)->first();
        }
        elseif ($userInfo->role == "employer") {
            $currentUser = DB::table('clinic-employees')->where('user_id', $userInfo->id)->first();
        }
        else {
            return redirect('/users/sign_up');
        }
        $request->session()->put('user', $currentUser);
        $request->session()->put('userInfo', $userInfo);
        return redirect('/users/profile');
    }
    
    public function logout() {
        // Очистка сессии.
        session()->flush();
        // Редирект на главную страницу.
        return redirect("/");
    }
}
