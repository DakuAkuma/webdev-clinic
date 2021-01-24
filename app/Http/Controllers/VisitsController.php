<?php

namespace App\Http\Controllers;

use App\Visit;
use App\Doctor;
use App\Patient;
use Illuminate\Http\Request;

class VisitsController extends Controller
{
    public function getVisits(Request $request) {
        if (empty(session('userInfo'))) {
            redirect()->back();
        }
        $doctors = Doctor::select('id', 'surname', 'name', 'patronymic', 'spec', 'cabinet')->get();
        // Проверяем, произошел ли выбор специальности
        if($request->has('spec')) {
            $pickable = Doctor::where('spec', $request->input('spec'))->select('id', 'surname', 'name', 'patronymic', 'spec', 'cabinet')->get();
        } else {
            $pickable = $doctors;
        }
        // Получаем все записи от данного пользователя
        $hotVisits = Visit::where([ ['patient_id', session('user')->id], ['date', '=', date('Y-m-d')] ])->orderBy('date')->get();
        $actualVisits = Visit::where([ ['patient_id', session('user')->id], ['date', '>', date('Y-m-d')] ])->orderBy('date')->get();
        $archiveVisits = Visit::where([ ['patient_id', session('user')->id], ['date', '<', date('Y-m-d')] ])->orderBy('date')->get();
        $specs = Doctor::select('spec')->groupBy('spec')->orderBy('spec')->get()->all();
        return view('visits', ['hot' => $hotVisits, 'actual' => $actualVisits, 'archive' => $archiveVisits, 'doctors' => $doctors, 'pickable' => $pickable, 'specials' => $specs]);
    }

    public function newVisit(Request $request, $medic_id) {
        // Валидация данных
        $this->validate($request, [
            'date' => 'required|date|after:' . date('d.m.Y', strtotime("yesterday")) . '|before:' . date("d.m.Y", strtotime("+2 weeks"))
        ]);
        // Проверяем, что день не выходной
        if(date("l", strtotime($request->input('date'))) == "Saturday" || date("l", strtotime($request->input('date'))) == "Sunday") {
            return redirect('/visits')->with('status', 'bad date - weekends');
        }
        // Получаем id всех врачей данной специальности
        // И для удобства записываем это в виде массива
        $specDoctors = array();
        foreach (Doctor::where('spec', Doctor::where('id', $medic_id)->select('spec')->first()->spec)->select('id')->get()->all() as $doctor) {
            $specDoctors[] = $doctor->id;
        }
        // Получаем все записи с врачами нужной специальности
        // Также переведем в массив для удобства
        $visitDoctors = array();
        foreach (Visit::where([ ['patient_id', session('user')->id], ['date', '>=', date('Y-m-d')] ])->whereIn('medic_id', $specDoctors)->orderBy('date')->get()->all() as $doctor) {
            $visitDoctors[] = $doctor->id_medic;
        }
        // Проверяем, пустой ли массив (если пустой - всё ок, записей к врачам данной спеки - нет)
        // Иначе же, просто редирект + уведомление
        if (!empty($visitDoctors)) {
            return redirect('/visits')->with('status', 'visits - same spec');
        }
        if (session('userInfo')->role = "patient") {
            Visit::create([
                'date' => date('Y-m-d', strtotime($request->input('date'))),
                'medic_id' => $medic_id,
                'patient_id' => session('user')->id
            ]);
            return redirect('/visits')->with('status', 'visit added');
        } else {
            redirect()->back();
        }

    }

    public function visitUpdate(Request $request, $id) {
        $this->validate($request, [
            'date' => 'required|date|after:' . date('d.m.Y', strtotime("today")) . '|before:' . date("d.m.Y", strtotime("+2 weeks"))
        ]);
        if(date("l", strtotime($request->input('date'))) == "Saturday" || date("l", strtotime($request->input('date'))) == "Sunday") {
            return redirect('/visits')->with('status', 'bad date - weekends');
        }
        Visit::where('id', $id)
                ->update(['date' => $request->input('date')]);
        return redirect('/visits')->with('status', 'visit updated');
    }

    public function visitDelete($id) {
        // Проверяем, не обманывают ли нас
        if(session('user')->id == Visit::where('id', $id)->select('patient_id')->first()->patient_id) {
            // Удаляем
            Visit::destroy($id);
            // Редирект к остальным записям + уведомление.
            return redirect('/visits')->with('status', 'visit deleted');
        }
        // Обман/ошибка - редирект к записям.
        return redirect('/visits');
    }
}
