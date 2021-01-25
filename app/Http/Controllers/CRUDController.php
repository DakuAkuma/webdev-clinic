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

    public function createPage(Request $request) {
        if (session('userInfo')->role == "admin") {
            return view('create_page');
        }
        return redirect()->back();
    }



    public function create(Request $request, $userRole) {
        if (session('userInfo')->role == "admin") {
            // Проверяем тип учетной записи (пациент, сотрудник, медик).
            $data = collect($request->except(['_token']))->all();
            if($userRole == "patient") {
                // Валидация введенных данных
                $data['passport'] = $data['seria'] . " " . $data['nomer']; 
                $this->validate($data, [
                    'surname' => 'required|min:2|max:48',
                    'name' => 'required|min:2|max:48',
                    'patronymic' => 'nullable|min:2|max:48',
                    'address' => 'required|min:25|max:255',
                    'login' => 'required|min:5|max:32|unique:users,login',
                    'password' => 'required|min:10|max:255',
                    'seria' => 'required|size:4',
                    'nomer' => 'required|size:6',
                    'passport' => 'unique:patients,passport'
                    'phone' => 'required|min:11|max:20',
                    'birthdate' => 'required|date|after:' . date('d.m.Y', strtotime("-110 years")) . '|before:' . date("d.m.Y", strtotime("-18 years")),
                    'class' => 'required'
                ]);

                $newUser = User::create([
                    'login' => $data['login'],
                    'password' => md5($data['password']),
                    'role' => 'patient',
                ]);

                Patient::create([
                    'surname' => $data['surname'],
                    'name' => $data['name'],
                    'patronymic' => $data['patronymic'],
                    'tel' => $data['phone'],
                    'address' => $data['address'],
                    'birthdate' => $data['birthdate'],
                    'passport' => $data['passport'],
                    'class' => $data['class'],
                    'status' => 'Здоров',
                    'user_id' => $newUser->id
                ]);
            }

            elseif($userRole == "employer") {
                // Валидация введенных данных
                $this->validate($data, [
                    'surname' => 'required|min:2|max:48',
                    'name' => 'required|min:2|max:48',
                    'patronymic' => 'nullable|min:2|max:48',
                    'rank' => 'required|max:255',
                    'salary' => 'required|max:10',
                    'exp' => 'required|max:32',
                    'login' => 'required|min:5|max:32|unique:users,login',
                    'password' => 'required|min:10|max:255',
                    'tel' => 'required|min:11|max:20|unique:employees,tel',
                ]);

                if(intval($data['salary']) <= 0) {
                    return redirect('/admin')->with('status', 'too low salary');
                }

                $newUser = User::create([
                    'login' => $data['login'],
                    'password' => md5($data['password']),
                    'role' => 'employer',
                ]);

                Employee::create([
                    'surname' => $data['surname'],
                    'name' => $data['name'],
                    'patronymic' => $data['patronymic'],
                    'tel' => $data['tel'],
                    'rank' => $data['rank'],
                    'salary' => intval($data['salary']),
                    'exp' => $data['exp'],
                    'user_id' => $newUser->id
                ]);
            }

            elseif($userRole == "medic") {
                // Валидация введенных данных
                $this->validate($data, [
                    'surname' => 'required|min:2|max:48',
                    'name' => 'required|min:2|max:48',
                    'patronymic' => 'nullable|min:2|max:48',
                    'tel' => 'required|min:11|max:20|unique:doctors,tel',
                    'spec' => 'required|max:48',
                    'quality' => 'required|max:55',
                    'salary' => 'required|max:10',
                    'exp' => 'required|max:32',
                    'cabinet' => 'required|min:3|max:3|unique:doctors,cabinet',
                    'login' => 'required|min:5|max:32|unique:users,login',
                    'password' => 'required|min:10|max:255',
                    
                ]);

                if(intval($data['salary']) <= 0) {
                    return redirect('/admin')->with('status', 'too low salary');
                }

                if(intval($data['cabinet']) <= 100) {
                    return redirect('/admin')->with('status', 'cabinet <3');
                }

                $newUser = User::create([
                    'login' => $data['login'],
                    'password' => md5($data['password']),
                    'role' => 'medic',
                ]);

                Doctor::create([
                    'surname' => $data['surname'],
                    'name' => $data['name'],
                    'patronymic' => $data['patronymic'],
                    'tel' => $data['tel'],
                    'spec' => $data['spec'],
                    'quality' => $data['quality'],
                    'salary' => intval($data['salary']),
                    'exp' => $data['exp'],
                    'cabinet' => intval($data['cabinet']),
                    'user_id' => $newUser->id
                ]);
            }
            redirect('/admin')->with('status', 'successful create');
        }
        return view('create_page');
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
        $data = collect($request->except(['_token']))->all();
        if (session('userInfo')->role == "patient") {
            $data['passport'] = $data['seria'] . " " . $data['nomer']; 
            $this->validate($data, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'address' => 'nullable|min:25|max:255',
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
                'seria' => 'nullable|size:4',
                'nomer' => 'nullable|size:6',
                'passport' => 'nullable|unique:patients,passport',
                'phone' => 'nullable|min:11|max:20',
                'class' => 'nullable'
            ]);

            /* !!!Блок обновления персональных данных пациента!!! */

            // Проверяем, заполнено ли поле surname и
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['surname'] != session('user')->surname) && $request->has('surname')) {
                Patient::where('id', session('user')->id)
                        ->update(['surname' => $data['surname']]);
            }

            // Проверяем, заполнено ли поле name
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['name'] != session('user')->name) && $request->has('name')) {
                Patient::where('id', session('user')->id)
                        ->update(['name' => $data['surname']]);
            }
            // Проверяем, заполнены ли поля seria и nomer (иначе нельзя менять данные)
            if($request->has('seria') && $request->has('nomer')) {
                // Проверяем совпадают ли введенные данные с актуальной информацией
                // Если ввод не совпадает с актуальной информацией, обновляем
                if($data['passport'] != session('user')->passport) {
                    Patient::where('id', session('user')->id)
                            ->update(['passport' => $data['passport']]);
                }
            } elseif (($request->has('seria') && !$request->has('nomer')) || (!$request->has('seria') && $request->has('nomer'))) {
              return redirect('/profile')->with('status', 'passport error');
            }

            // Проверяем, заполнено ли поле address
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['address'] != session('user')->address) && $request->has('address')) {
                Patient::where('id', session('user')->id)
                        ->update(['address' => $data['address']]);
            }
            // Проверяем, заполнено ли поле phone
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['phone'] != session('user')->tel) && $request->has('phone')) {
                Patient::where('id', session('user')->id)
                        ->update(['tel' => $data['phone']]);
            }
            // Проверяем, изменено ли значение поля class.
            if($data['class'] != session('user')->class && !empty($data['class'])) {
                // В случае, если изменен соц. статус, заносим в таблицу, без доп. проверок.
                Patient::where('id', session('user')->id)
                        ->update(['class' => $data['class']]);
            }
            $request->session()->put('user', Patient::where('id', session('user')->id)->first());
        }
        elseif(session('userInfo')->role == "employer") {
            $this->validate($data, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
                'phone' => 'nullable|min:11|max:20|unique:employees,tel'
            ]);

            /* Блок работы с персональными данными сотрудника */

            // Проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['surname'] != session('user')->surname) && $request->has('surname')) {
                Employee::where('id', session('user')->id)
                        ->update(['surname' => $data['surname']]);
            }
            // Проверяем, заполнено ли поле name
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['name'] != session('user')->name) && $request->has('name')) {
                Employee::where('id', session('user')->id)
                        ->update(['name' => $data['name']]);
            }
            // Проверяем, заполнено ли поле phone
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['phone'] != session('user')->tel) && $request->has('phone')) {
                Employee::where('id', session('user')->id)
                        ->update(['tel' => $data['phone']]);
            }
            $request->session()->put('user', Employee::where('user_id', session('user')->id)->first());
        }
        elseif(session('userInfo')->role == "medic") {
            $this->validate($data, [
                'surname' => 'nullable|min:2|max:48',
                'name' => 'nullable|min:2|max:48',
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
                'phone' => 'nullable|min:11|max:20|unique:doctors,tel'
            ]);

            /* Блок работы с персональными данными сотрудника */

            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['surname'] != session('user')->surname) && $request->has('surname')) {
                Doctor::where('id', session('user')->id)
                        ->update(['surname' => $data['surname']]);
            }
            // Проверяем, заполнено ли поле name
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['name'] != session('user')->name) && $request->has('name')) {
                Doctor::where('id', session('user')->id)
                        ->update(['name' => $data['name']]);
            }
            // Проверяем, заполнено ли поле phone
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if(($data['phone'] != session('user')->tel) && $request->has('phone')) {
                Doctor::where('id', session('user')->id)
                        ->update(['tel' => $data['phone']]);
            }
            $request->session()->put('user', Doctor::where('user_id', session('user')->id)->first());
        }

        /* Блок работы с данными пользователя (не зависит от роли пользователя) */

        // Проверяем, заполнено ли поле login
        if($request->has('login')) {
            // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
            User::where('id', session('userInfo')->id)
                    ->update(['login' => $data['login']]);
        }
        // Проверяем, заполнено ли поле password
        // проверяем совпадают ли введенные данные с актуальной информацией
        // если ввод не совпадает с актуальной информацией, обновляем
        if((md5($data['password']) != session('userInfo')->password) && $request->has('password')) {
            User::where('id', session('userInfo')->id)
                        ->update(['password' => md5($data['password'])]);
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
