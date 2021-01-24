<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Doctor;
use App\Visit;
use App\Record;
use App\Illness;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function getRecords(Request $request) {
        // Коллекции
        $availablePatients = Patient::where('status', 'not like', 'Умер')->whereNotNull('user_id')->select('id','surname','name','patronymic')->get();
        $illnesses = Illness::select('id', 'illness')->get();
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
                $actualPatient = array_last($availablePatients->where('surname', $actData[0])->where('name', $actData[1])->where('patronymic', $actData[2])->all());
                $request->session()->put('currentPatient', $actualPatient);
            } else {
                // Получили пациента в виде объекта (так проще обращаться к параметрам)
                $actualPatient = array_last($availablePatients->where('surname', $actData[0])->where('name', $actData[1])->all());
                $request->session()->put('currentPatient', $actualPatient);
            }
            $visits = Visit::where([ ['medic_id', session('user')->id], ['patient_id', $actualPatient->id], ['date', date('Y-m-d')] ])->get();
            // Если пацент выбран, загружаем его записи.
            $archiveRecords = Record::where('patient_id', $actualPatient->id)->get()->all();
            // Получаем данные из записей
            foreach ($archiveRecords as $id => $record) {
                if(isset($record->medic_id)) {
                    $archiveDoctors[$id] = Doctor::where('id', $record->medic_id)->select('surname', 'name', 'patronymic', 'spec')->first();
                }
                if(isset($record->visit_id)) {
                    $archiveVisits[$id] = Visit::where('id', $record->visit_id)->select('date')->first();
                }
                if(isset($record->illness_id)) {
                    $archiveIllnesses[$id] = Illness::where('id', $record->illness_id)->select('illness')->first();
                }
            }
        }
        return view('records', ['patients' => $availablePatients, 'records' => $archiveRecords, 'visits' => $archiveVisits, 'doctors' => $archiveDoctors, 'illnesses' => $archiveIllnesses, 'allIlls' => $illnesses, 'actual' => $visits]);
        
    }

    public function addRecord(Request $request, $id) {
        // Валидация
        $this->validate($request, [
            'illness' => 'required',
            'disabled' => 'required|max:55',
            'ambulary' => 'required|max:55',
            'dispans' => 'required|max:32',
            'date' => 'nullable|date|before:tomorrow|after:yesterday',
            'note' => 'nullable|max:500',
            'status' => 'required|max:55'
        ]);
        if (session('userInfo')->role == "medic") {
            $illnessId = array_last(Illness::where('illness', $request->input('illness'))->select('id')->get()->all())->id;
            $visitId = array_last(Visit::where([
                ['patient_id', session('currentPatient')->id],
                ['medic_id', session('user')->id],
                ['date', $request->input('date')]
            ])->get()->all());
            // Добавляем запись
            Record::create([
                'disabled' => $request->input('disabled'),
                'ambulary' => $request->input('ambulary'),
                'dispans' => $request->input('dispans'),
                'note' => $request->input('note'),
                'medic_id' => session('user')->id,
                'patient_id' => session('currentPatient')->id,
                'visit_id' => (empty($visitId)) ? $visitId : array_last($visitId)->id,
                'illness_id' => $illnessId
            ]);

            Patient::where('id', session('currentPatient')->id)
                    ->update(['status' => $request->input('status')]);

            $request->session()->forget('currentPatient');
            return redirect('/records')->with('status', 'record - added');
        }
        return redirect('/records');
    }
}
