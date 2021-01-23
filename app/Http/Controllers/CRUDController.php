<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CRUDController extends Controller
{
    public function create(Request $request) {

    }

    public function read($id) {
        // Защита от подсмотров от имени другого пользователя.
        if(session('userInfo')->role == "patient" && $id == session('userInfo')->id) {
            $records = DB::table('clinic-records')->where('id_patient', session('user')->id)->get();
            $doctors = array();
            $visits = array();
            $illnesses = array();
            //echo var_dump($records);
            if($records->count() != 0) {
                foreach ($records as $record) {
                    if(isset($record->id_medic)) {
                        $doctors[] = DB::table('clinic-doctors')->select('surname', 'name', 'patronymic', 'spec')->where('id', $record->id_medic)->first();
                    }
                    if(isset($record->id_visit)) {
                        $visits[] = DB::table('clinic-visits')->select('date')->where('id', $record->id_visit)->first();
                    }
                    if(isset($record->id_illness)) {
                        $illnesses[] = DB::table('clinic-illnesses')->select('illness')->where('id', $record->id_illness)->first();
                    }
                }
            }
            // print_r($doctors);
            // print_r(is_null($visits));
            // print_r($illnesses);
            return view('records', [ 'records' => $records, 'doctors' => $doctors, 'visits' => $visits, 'illnesses' => $illnesses ]);
        }
    }

    public function update(Request $request, $id) {
        // for degug
        //dd($request->except(['_token', 'patronymic']));
        // Обработка поступившего пользователя, определяем с какой из 3 таблиц работать.
        if (session('userInfo')->role == "patient" && $id == session('userInfo')->id) {
            $this->validate($request, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'address' => 'nullable|min:25|max:255',
                'login' => 'nullable|min:5|max:32|unique:clinic-users,login',
                'password' => 'nullable|min:10|max:255',
                'seria' => 'nullable|size:4',
                'nomer' => 'nullable|size:6',
                'phone' => 'nullable|min:11|max:20',
                'class' => 'nullable'
            ]);
            $patientData = DB::table('clinic-patients')->where('user_id', $id)->first();
            $userData = DB::table('clinic-users')->select('login', 'password')->where('id', $id)->first();
            //echo print_r($patientData), '<br>', print_r($userData);

            /* !!!Блок обновления персональных данных пациента!!! */

            // Проверяем, заполнено ли поле surname
            if($request->has('surname')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('surname') != $patientData->surname) {
                    DB::table('clinic-patients')
                                ->where('id', $patientData->id)
                                ->update(['surname' => $request->input('surname')]);
                }
            }
            // Проверяем, заполнено ли поле name
            if($request->has('name')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('name') != $patientData->name) {
                    DB::table('clinic-patients')
                                ->where('id', $patientData->id)
                                ->update(['name' => $request->input('name')]);
                }
            }
            // Проверяем, заполнены ли поля seria и nomer (иначе нельзя менять данные)
            if($request->has('seria') && $request->has('nomer')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('seria') != substr($patientData->passport,0,4) && $request->input('nomer') != substr($patientData->passport,5,10)) {
                    DB::table('clinic-patients')
                                ->where('id', $patientData->id)
                                ->update(['passport' => $request->input('seria') . " " . $request->input('nomer')]);
                }
            }
            // Проверяем, заполнено ли поле address
            if($request->has('address')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('address') != $patientData->address) {
                    DB::table('clinic-patients')
                                ->where('id', $patientData->id)
                                ->update(['address' => $request->input('address')]);
                }
            }
            // Проверяем, заполнено ли поле phone
            if($request->has('phone')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('phone') != $patientData->tel) {
                    DB::table('clinic-patients')
                                ->where('id', $patientData->id)
                                ->update(['tel' => $request->input('phone')]);
                }
            }
            // Проверяем, изменено ли значение поля class.
            if($request->input('class') != $patientData->class) {
                // В случае, если изменен соц. статус, заносим в таблицу, без доп. проверок.
                DB::table('clinic-patients')
                                ->where('id', $patientData->id)
                                ->update(['class' => $request->input('class')]);
            }

            /* !!!Блок обновления данных пользователя!!! */

            // Проверяем, заполнено ли поле login
            if($request->has('login')) {
                // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
                DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['login' => $request->input('login')]);
            }
            // Проверяем, заполнено ли поле password
            if($request->has('password')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if(md5($request->input('password')) != $userData->password) {
                    DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['password' => md5($request->input('password'))]);
                }
            }
            $request->session()->put('userInfo', DB::table('clinic-users')->where('id', session('userInfo')->id)->first());
            $request->session()->put('user', DB::table('clinic-patients')->where('id', $id)->first());
        }
        elseif(session('userInfo')->role == "admin" && $id == session('userInfo')->id) {
            $this->validate($request, [
                'login' => 'nullable|min:5|max:32|unique:clinic-users,login',
                'password' => 'nullable|min:10|max:255'
            ]);
            $userData = DB::table('clinic-users')->select('login', 'password')->where('id', $id)->first();
            // Заполнено ли поле login
            if($request->has('login')) {
                // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
                DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['login' => $request->input('login')]);
            }
            // Проверяем, заполнено ли поле password
            if($request->has('password')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if(md5($request->input('password')) != $userData->password) {
                    DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['password' => md5($request->input('password'))]);
                }
            }
            $request->session()->put('user', DB::table('clinic-users')->where('id', $id)->first());
        }
        elseif(session('userInfo')->role == "employer" && $id == session('userInfo')->id) {
            $this->validate($request, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'login' => 'nullable|min:5|max:32|unique:clinic-users,login',
                'password' => 'nullable|min:10|max:255',
                'phone' => 'nullable|min:11|max:20'
            ]);
            $employData = DB::table('clinic-employees')->where('user_id', $id)->first();
            $userData = DB::table('clinic-users')->select('login', 'password')->where('id', $id)->first();
            /* Блок работы с персональными данными сотрудника */
            if($request->has('surname')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('surname') != $employData->surname) {
                    DB::table('clinic-employees')
                                ->where('id', $employData->id)
                                ->update(['surname' => $request->input('surname')]);
                }
            }
            // Проверяем, заполнено ли поле name
            if($request->has('name')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('name') != $employData->name) {
                    DB::table('clinic-employees')
                                ->where('id', $employData->id)
                                ->update(['name' => $request->input('name')]);
                }
            }
            // Проверяем, заполнено ли поле phone
            if($request->has('phone')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('phone') != $employData->tel) {
                    DB::table('clinic-employees')
                                ->where('id', $employData->id)
                                ->update(['tel' => $request->input('phone')]);
                }
            }
            
            /* Блок работы с данными пользователя */

            // Заполнено ли поле login
            if($request->has('login')) {
                // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
                DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['login' => $request->input('login')]);
            }
            // Проверяем, заполнено ли поле password
            if($request->has('password')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if(md5($request->input('password')) != $userData->password) {
                    DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['password' => md5($request->input('password'))]);
                }
            }
            $request->session()->put('userInfo', DB::table('clinic-users')->where('id', session('userInfo')->id)->first());
            $request->session()->put('user', DB::table('clinic-employees')->where('user_id', $id)->first());
        }
        elseif(session('userInfo')->role == "medic" && $id == session('userInfo')->id) {
            $this->validate($request, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'login' => 'nullable|min:5|max:32|unique:clinic-users,login',
                'password' => 'nullable|min:10|max:255',
                'phone' => 'nullable|min:11|max:20'
            ]);
            $medicData = DB::table('clinic-doctors')->where('user_id', $id)->first();
            $userData = DB::table('clinic-users')->select('login', 'password')->where('id', $id)->first();
            /* Блок работы с персональными данными сотрудника */
            if($request->has('surname')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('surname') != $medicData->surname) {
                    DB::table('clinic-doctors')
                                ->where('id', $medicData->id)
                                ->update(['surname' => $request->input('surname')]);
                }
            }
            // Проверяем, заполнено ли поле name
            if($request->has('name')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('name') != $medicData->name) {
                    DB::table('clinic-doctors')
                                ->where('id', $medicData->id)
                                ->update(['name' => $request->input('name')]);
                }
            }
            // Проверяем, заполнено ли поле phone
            if($request->has('phone')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($request->input('phone') != $medicData->tel) {
                    DB::table('clinic-doctors')
                                ->where('id', $medicData->id)
                                ->update(['tel' => $request->input('phone')]);
                }
            }
            
            /* Блок работы с данными пользователя */

            // Заполнено ли поле login
            if($request->has('login')) {
                // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
                DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['login' => $request->input('login')]);
            }
            // Проверяем, заполнено ли поле password
            if($request->has('password')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if(md5($request->input('password')) != $userData->password) {
                    DB::table('clinic-users')
                                ->where('id', $id)
                                ->update(['password' => md5($request->input('password'))]);
                }
            }
            $request->session()->put('userInfo', DB::table('clinic-users')->where('id', session('userInfo')->id)->first());
            $request->session()->put('user', DB::table('clinic-doctors')->where('user_id', $id)->first());
        }
        // После обновления данных, редирект в профиль с поздравлениями об успешном изменении данных пользователя :)
        
        return redirect('/users/profile')->with('status', 'successful update');
     }  
 
     public function delete($id) {
        // for debug
        //dd($request);
        // Проверка на совпадение id текущего пользователя с запрашиваемым на удаление id.
        if (session('userInfo')->id == $id) {
            DB::table('clinic-users')
                        ->where('id', $id)
                        ->delete();
            // Обнуляем сессию.
            session()->flush();
            // Редирект на главную.
            return redirect('/')->with('status', 'successful delete');
        }
        // Попытка обмануть - редирект в профиль.
        return redirect('/users/profile');
     }

}
