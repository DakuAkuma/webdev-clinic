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

    public function visits(Request $request) {
        $doctors = DB::table('clinic-doctors')->select('id', 'surname', 'name', 'patronymic', 'spec', 'cabinet')->get();
        // Проверяем, произошел ли выбор специальности
        if($request->has('spec')) {
            $pickable = DB::table('clinic-doctors')->select('id', 'surname', 'name', 'patronymic', 'spec', 'cabinet')->where('spec', $request->input('spec'))->get();
        } else {
            $pickable = $doctors;
        }
        // Получаем все записи от данного пользователя
        $hotVisits = DB::table('clinic-visits')->where([ ['id_patient', session('user')->id], ['date', '=', date('Y-m-d')] ])->orderBy('date')->get();
        $actualVisits = DB::table('clinic-visits')->where([ ['id_patient', session('user')->id], ['date', '>', date('Y-m-d')] ])->orderBy('date')->get();
        $archiveVisits = DB::table('clinic-visits')->where([ ['id_patient', session('user')->id], ['date', '<', date('Y-m-d')] ])->orderBy('date')->get();
        //echo var_dump($actualVisits[0]->id_medic);
        //echo var_dump($doctors->where('id', 2)->all()->spec);
        $specs = DB::table('clinic-doctors')->select('spec')->groupBy('spec')->orderBy('spec')->get()->all();
        //echo var_dump($specs);
        return view('visits', ['hot' => $hotVisits, 'actual' => $actualVisits, 'archive' => $archiveVisits, 'doctors' => $doctors, 'pickable' => $pickable, 'specials' => $specs]);
    }

    public function visit_validation(Request $request, $patient_id, $medic_id) {
        // for debug
        //dd($request);
        // Валидация данных
        $this->validate($request, [
            'date' => 'required|date|after:' . date('d.m.Y', strtotime("yesterday")) . '|before:' . date("d.m.Y", strtotime("+2 weeks"))
        ]);
        // Проверяем, что день не выходной
        if(date("l", strtotime($request->input('date'))) == "Saturday" || date("l", strtotime($request->input('date'))) == "Sunday") {
            return redirect('/visits/add')->with('status', 'bad date - weekends');
        }
        // Получаем id всех врачей данной специальности
        // И для удобства записываем это в виде массива
        $specDoctors = array();
        foreach (DB::table('clinic-doctors')->where('spec', DB::table('clinic-doctors')->where('id', $medic_id)->select('spec')->first()->spec)->select('id')->get()->all() as $doctor) {
            $specDoctors[] = $doctor->id;
        }
        //print_r($specDoctors);
        // Получаем все записи с врачами нужной специальности
        // Также переведем в массив для удобства
        $visitDoctors = array();
        foreach (DB::table('clinic-visits')->where([ ['id_patient', $patient_id], ['date', '>=', date('Y-m-d')] ])->whereIn('id_medic', $specDoctors)->orderBy('date')->get()->all() as $doctor) {
            $visitDoctors[] = $doctor->id_medic;
        }
        print_r($visitDoctors);
        // Проверяем, пустой ли массив (если пустой - всё ок, записей к врачам данной спеки - нет)
        // Иначе же, просто редирект + уведомление
        if (!empty($visitDoctors)) {
            return redirect('/visits/add')->with('status', 'visits - same spec');
        }
        if ($patient_id == session('user')->id) {
            DB::table('clinic-visits')->insert([
                'date' => date('Y-m-d', strtotime($request->input('date'))),
                'id_medic' => $medic_id,
                'id_patient' => $patient_id
            ]);
            return redirect('/visits/add')->with('status', 'visit added');
        }
    }

    public function visit_update(Request $request, $id) {
        $this->validate($request, [
            'date' => 'required|date|after:' . date('d.m.Y', strtotime("today")) . '|before:' . date("d.m.Y", strtotime("+2 weeks"))
        ]);
        if(date("l", strtotime($request->input('date'))) == "Saturday" || date("l", strtotime($request->input('date'))) == "Sunday") {
            return redirect('/visits/add')->with('status', 'bad date - weekends');
        }
        DB::table('clinic-visits')
                    ->where('id', $id)
                    ->update(['date' => $request->input('date')]);
        return redirect('/visits/add')->with('status', 'visit updated');
    }

    public function visit_delete($id) {
        // Получаем запись из таблицы, для верификации действий
        $visit = DB::table('clinic-visits')->where('id', $id)->first();
        // Проверяем, не обманывают ли нас
        if($visit->id_patient == session('user')->id) {
            // Удаляем
            DB::table('clinic-visits')->where('id', $id)->delete();
            // Редирект к остальным записям + статус.
            return redirect('/visits/add')->with('status', 'visit deleted');
        }
        // Обман/ошибка - редирект к записям.
        return redirect('/visits/add');
    }
}
