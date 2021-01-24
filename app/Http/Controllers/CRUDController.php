<?php

namespace App\Http\Controllers;

use App\User;
use App\Patient;
use App\Doctor;
use App\Employee;
use App\Record;
use App\Visit;
use App\Illness;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    public function create(Request $request) {

    }

    public function read() {
        // Защита от подсмотров от имени другого пользователя.
        if(session('userInfo')->role == "patient") {
            $records = Record::where('patient_id', session('user')->id)->get();
            $doctors = array();
            $visits = array();
            $illnesses = array();
            //echo var_dump($records);
            if($records->count() != 0) {
                foreach ($records as $id => $record) {
                    if(isset($record->medic_id)) {
                        $doctors[$id] = Doctor::where('id', $record->medic_id)->select('surname', 'name', 'patronymic', 'spec')->first();
                    }
                    if(isset($record->visit_id)) {
                        $visits[$id] = Visit::where('id', $record->visit_id)->select('date')->first();
                    }
                    if(isset($record->illness_id)) {
                        $illnesses[$id] = Illness::where('id', $record->illness_id)->select('illness')->first();
                    }
                }
            }
            return view('archive', [ 'records' => $records, 'doctors' => $doctors, 'visits' => $visits, 'illnesses' => $illnesses ]);
        } else {
            return redirect()->back();
        }
    }

    public function update(Request $request) {
        // Обработка поступившего пользователя, определяем с какой из 3 таблиц работать.
        if (session('userInfo')->role == "patient") {
            $this->validate($request, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'address' => 'nullable|min:25|max:255',
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
                'seria' => 'nullable|size:4',
                'nomer' => 'nullable|size:6',
                'phone' => 'nullable|min:11|max:20',
                'class' => 'nullable'
            ]);

            /* !!!Блок обновления персональных данных пациента!!! */

            // Проверяем, заполнено ли поле surname и
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('surname') != session('user')->surname) && $request->has('surname')) {
                Patient::where('id', session('user')->id)
                        ->update(['surname' => $request->input('surname')]);
            }

            // Проверяем, заполнено ли поле name
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('name') != session('user')->name) && $request->has('name')) {
                Patient::where('id', session('user')->id)
                        ->update(['name' => $request->input('name')]);
            }
            // Проверяем, заполнены ли поля seria и nomer (иначе нельзя менять данные)
            if($request->has('seria') && $request->has('nomer')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('seria') != substr(session('user')->passport,0,4) && $request->input('nomer') != substr(session('user')->passport,5,10)) {
                    Patient::where('id', session('user')->id)
                            ->update(['passport' => $request->input('seria') . " " . $request->input('nomer')]);
                }
            } elseif (($request->has('seria') && !$request->has('nomer')) || (!$request->has('seria') && $request->has('nomer'))) {
              return redirect('/profile')->with('status', 'passport error');
            }

            // Проверяем, заполнено ли поле address
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('address') != session('user')->address) && $request->has('address')) {
                Patient::where('id', session('user')->id)
                        ->update(['address' => $request->input('address')]);
            }
            // Проверяем, заполнено ли поле phone
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('phone') != session('user')->tel) && $request->has('phone')) {
                Patient::where('id', session('user')->id)
                        ->update(['tel' => $request->input('phone')]);
            }
            // Проверяем, изменено ли значение поля class.
            if($request->input('class') != session('user')->class && !empty($request->input('class'))) {
                // В случае, если изменен соц. статус, заносим в таблицу, без доп. проверок.
                Patient::where('id', session('user')->id)
                        ->update(['class' => $request->input('class')]);
            }
            $request->session()->put('user', Patient::where('id', session('user')->id)->first());
        }
        elseif(session('userInfo')->role == "employer") {
            $this->validate($request, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
                'phone' => 'nullable|min:11|max:20'
            ]);

            /* Блок работы с персональными данными сотрудника */

            // Проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('surname') != session('user')->surname) && $request->has('surname')) {
                Employee::where('id', session('user')->id)
                        ->update(['surname' => $request->input('surname')]);
            }
            // Проверяем, заполнено ли поле name
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('name') != session('user')->name) && $request->has('name')) {
                Employee::where('id', session('user')->id)
                        ->update(['name' => $request->input('name')]);
            }
            // Проверяем, заполнено ли поле phone
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('phone') != session('user')->tel) && $request->has('phone')) {
                Employee::where('id', session('user')->id)
                        ->update(['tel' => $request->input('phone')]);
            }
            $request->session()->put('user', Employee::where('user_id', session('user')->id)->first());
        }
        elseif(session('userInfo')->role == "medic") {
            $this->validate($request, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
                'phone' => 'nullable|min:11|max:20'
            ]);

            /* Блок работы с персональными данными сотрудника */

            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('surname') != session('user')->surname) && $request->has('surname')) {
                Doctor::where('id', session('user')->id)
                        ->update(['surname' => $request->input('surname')]);
            }
            // Проверяем, заполнено ли поле name
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('name') != session('user')->name) && $request->has('name')) {
                Doctor::where('id', session('user')->id)
                        ->update(['name' => $request->input('name')]);
            }
            // Проверяем, заполнено ли поле phone
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($request->input('phone') != session('user')->tel) && $request->has('phone')) {
                Doctor::where('id', session('user')->id)
                        ->update(['tel' => $request->input('phone')]);
            }
            $request->session()->put('user', Doctor::where('user_id', session('user')->id)->first());
        }

        /* Блок работы с данными пользователя (не зависит от роли пользователя) */

        // Проверяем, заполнено ли поле login
        if($request->has('login')) {
            // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
            User::where('id', session('userInfo')->id)
                    ->update(['login' => $request->input('login')]);
        }
        // Проверяем, заполнено ли поле password
        // проверяем совпадают ли введенные данные с актуальной информацией
        // если ввод не совпадает с актуальной информацией, обновляем
        if((md5($request->input('password')) != session('userInfo')->password) && $request->has('password')) {
            User::where('id', session('userInfo')->id)
                        ->update(['password' => md5($request->input('password'))]);
        }
        $request->session()->put('userInfo', User::where('id', session('userInfo')->id)->first());

        // После обновления данных, редирект в профиль с поздравлениями об успешном изменении данных пользователя :)
        
        return redirect('/profile')->with('status', 'successful update');
     }  
 
     public function delete() {
        User::destroy(session('userInfo')->id);
        session()->flush();
        return redirect('/')->with('status', 'successful delete');
     }

}
