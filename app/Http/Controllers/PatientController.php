<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function sign_up_validate(Request $request) {
        //echo $request->input('seria') . " " . $request->input('nomer');
        $this->validate($request, [
            'surname' => 'required|min:2|max:48',
            'name' => 'required|min:2|max:48',
            'patronymic' => 'nullable|min:2|max:48',
            'address' => 'required|min:25|max:255',
            'login' => 'required|min:5|max:32|unique:clinic-users,login',
            'seria' => 'required|size:4',
            'nomer' => 'required|size:6',
            'phone' => 'required|min:11|max:20',
            'birthdate' => 'required|date|after:' . date('d.m.Y', strtotime("-110 years")) . '|before:' . date("d.m.Y", strtotime("-18 years")),
            'class' => 'required'
        ]);
        // Создание УЗ пользователя и получение актуального user_ID.
        $user_id = DB::table('clinic-users')->insertGetId([
            'login' => $request->input('login'), 
            'password' => md5($request->input('seria') . $request->input('nomer')), 
            'role' => 'patient'
        ]);
        // Добавление пациента в БД.
        DB::table('clinic-patients')->insert([
            'surname' => $request->input('surname'), 
            'name' => $request->input('name'), 
            'patronymic' =>  $request->input('patronymic'),
            'tel' =>  $request->input('phone'),
            'address' => $request->input('address'),
            'birthdate' => $request->input('birthdate'),
            'passport' => $request->input('seria') . " " . $request->input('nomer'),
            'class' => $request->input('class'),
            'status' => 'Здоров',
            'user_id' => $user_id
        ]);
        // Редирект на страницу входа.
        return redirect('/users/sign_in')->with('status', 'successful create');
    }

    public function visits() {
        // Получаем все записи от данного пользователя
        $hotVisits = DB::table('clinic-visits')->where([ ['id_patient', session('user')->id], ['date', '=', date('Y-m-d')] ])->orderBy('date')->get();
        $actualVisits = DB::table('clinic-visits')->where([ ['id_patient', session('user')->id], ['date', '>', date('Y-m-d')] ])->orderBy('date')->get();
        $archiveVisits = DB::table('clinic-visits')->where([ ['id_patient', session('user')->id], ['date', '<', date('Y-m-d')] ])->orderBy('date')->get();
        //echo var_dump($actualVisits[0]->id_medic);
        $doctors = DB::table('clinic-doctors')->select('id', 'surname', 'name', 'patronymic', 'spec', 'cabinet')->get();
        //echo var_dump($doctors->where('id', 2)->all()->spec);
        return view('visits', ['hot' => $hotVisits, 'actual' => $actualVisits, 'archive' => $archiveVisits, 'doctors' => $doctors]);
    }
}
