<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployController extends Controller
{

    public function records(Request $request) {
        // Коллекции
        $patients = DB::table('clinic-patients')->whereNotIn('status', ['Умер'])->select('id','surname','name','patronymic', 'birthdate', 'class', 'status')->get();
        $illnesses = DB::table('clinic-illnesses')->select('id', 'illness')->get();
        $visits = array();
        // Массивы объектов
        $archiveRecords = array();
        $archiveVisits = array();
        $archiveDoctors = array();
        $archiveIllnesses = array();
        // Проверка, выбран ли пациент.
        if($request->has('patient')) {
            // Делим полученную информацию, записываем в массив
            $actData = explode(" " ,$request->input('patient'), 3);
            // Проверяем, имеется ли отчество
            if (!empty($actData[2])) {
                // Получили пациента в виде объекта (так проще обращаться к параметрам)
                $actualPatient = array_last($patients->where('surname', $actData[0])->where('name', $actData[1])->where('patronymic', $actData[2])->all());
            } else {
                // Получили пациента в виде объекта (так проще обращаться к параметрам)
                $actualPatient = array_last($patients->where('surname', $actData[0])->where('name', $actData[1])->all());
            }
            $visits = DB::table('clinic-visits')->where([ ['id_medic', session('user')->id], ['id_patient', $actualPatient->id], ['date', date('Y-m-d')] ])->get();
            // Если пацент выбран, загружаем его записи.
            $archiveRecords = DB::table('clinic-records')->where('id_patient', $actualPatient->id)->get()->all();
            //echo var_dump($archiveRecords);
            // Получаем данные из записей
            foreach ($archiveRecords as $record) {
                if(isset($record->id_medic)) {
                    $archiveDoctors[] = DB::table('clinic-doctors')->select('surname', 'name', 'patronymic', 'spec')->where('id', $record->id_medic)->first();
                }
                if(isset($record->id_visit)) {
                    $archiveVisits[] = DB::table('clinic-visits')->select('date')->where('id', $record->id_visit)->first();
                }
                if(isset($record->id_illness)) {
                    $archiveIllnesses[] = DB::table('clinic-illnesses')->select('illness')->where('id', $record->id_illness)->first();
                }
            }
            //echo var_dump($archiveVisits);
        }
        return view('record', ['patients' => $patients, 'records' => $archiveRecords, 'visits' => $archiveVisits, 'doctors' => $archiveDoctors, 'illnesses' => $archiveIllnesses, 'allIlls' => $illnesses, 'actual' => $visits]);
        
    }

    public function records_validate(Request $request) {
        // Валидация
        $this->validate($request, [
            'fio' => 'required',
            'illness' => 'required',
            'disabled' => 'required|min:1|max:55',
            'ambulary' => 'required',
            'dispans' => 'required',
            'date' => 'nullable|date|before:tomorrow|after:yesterday',
            'note' => 'nullable|max:500'
        ]);
        if (session('userInfo')->role == "medic" && session('userInfo')->id == session('user')->user_id) {
            // Делим полученную информацию, записываем в массив
            $actData = explode(" " ,$request->input('fio'), 3);
            $patientId;
            $visitId;
            $illnessId = array_last(DB::table('clinic-illnesses')->where('illness', $request->input('illness'))->select('id')->get()->all())->id;
            $patients = DB::table('clinic-patients')->whereNotIn('status', ['Умер'])->select('id','surname','name','patronymic', 'birthdate', 'class', 'status')->get();
            // Проверяем, имеется ли отчество
            if (!empty($actData[2])) {
                // Получаем ID пациента
                $patientId = array_last($patients->where('surname', $actData[0])->where('name', $actData[1])->where('patronymic', $actData[2])->all())->id;
            } else {
                // Получаем ID пациента
                $patientId = array_last($patients->where('surname', $actData[0])->where('name', $actData[1])->all())->id;
            }
            if ($request->input('ambulary') == "Не требуется" && $illnessId == 1) {
                DB::table('clinic-patients')
                            ->where('id', $patientId)
                            ->update(['status' => 'Здоров']);
            }
            elseif ($request->input('ambulary') == "Не требуется" && $illnessId == 2) {
                DB::table('clinic-patients')
                            ->where('id', $patientId)
                            ->update(['status' => 'Вылечился']);
            }
            elseif ($request->input('ambulary') == "Требуется" && ($illnessId != 2 || $illnessId != 1)) {
                DB::table('clinic-patients')
                            ->where('id', $patientId)
                            ->update(['status' => 'Лечится']);
            }
            elseif ($request->input('ambulary') == "Срочно требуется" && ($illnessId != 2 || $illnessId != 1)) {
                DB::table('clinic-patients')
                            ->where('id', $patientId)
                            ->update(['status' => "Направлен в стационар"]);
            }
            if ($request->input('date') !== null) {
                $visitId = array_last(DB::table('clinic-visits')->where([
                    ['id_patient', $patientId],
                    ['id_medic', session('user')->id],
                    ['date', $request->input('date')]
                ])->all())->id;
            } else {
                $visitId = null;
            }
            // Добавляем запись
            DB::table('clinic-records')->insert([
                'disabled' => $request->input('disabled'),
                'ambulary' => $request->input('ambulary'),
                'dispans' => $request->input('dispans'),
                'note' => $request->input('note'),
                'id_medic' => session('user')->id,
                'id_patient' => $patientId,
                'id_visit' => $visitId,
                'id_illness' => $illnessId
            ]);
            return redirect('/records/add')->with('status', 'record - added');
        }
        return redirect('/records/add');
    }
}
