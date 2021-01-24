<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Doctor;
use App\Employee;
use App\User;
use App\Illness;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index() {
        if(session('userInfo')->role == "admin") {
            return view('dashboard', [
                'patients' => Patient::whereNotNull('user_id')->get(), 
                'doctors' => Doctor::whereNotNull('user_id')->get(), 
                'employees' => Employee::whereNotNull('user_id')->get(), 
                'users' => User::all()
            ]);
        }
        return redirect()->back();
    }

    public function updatePage() {
        if(session('userInfo')->role == "admin") {
            return view('update_page', [
                'patients' => Patient::whereNotNull('user_id')->get(), 
                'doctors' => Doctor::whereNotNull('user_id')->get(), 
                'employees' => Employee::whereNotNull('user_id')->get(), 
            ]);
        }
        return redirect()->back();
    }

    public function updateDoctor(Request $request, $medicId) {
        if(session('userInfo')->role == "admin") {
            $this->validate($request, [
                'spec' => 'nullable|max:48',
                'quality' => 'nullable|max:55',
                'salary' => 'nullable|max:10',
                'exp' => 'nullable|max:32',
                'cabinet' => 'nullable|min:3|max:3'
            ]);
            if($request->has('spec') && ($request->input('spec') != array_last(Doctor::where('id', $medicId)->select('spec')->get()->all())->spec)) {
                Doctor::where('id', $medicId)->update(['spec' => $request->input('spec')]);
            }
            if($request->has('quality') && ($request->input('quality') != array_last(Doctor::where('id', $medicId)->select('quality')->get()->all())->quality)) {
                Doctor::where('id', $medicId)->update(['quality' => $request->input('quality')]);
            }
            if($request->has('salary') && ($request->input('salary') != array_last(Doctor::where('id', $medicId)->select('salary')->get()->all())->salary) && intval($request->input('salary')) > 0) {
                Doctor::where('id', $medicId)->update(['salary' => intval($request->input('salary'))]);
            }
            if($request->has('exp') && ($request->input('exp') != array_last(Doctor::where('id', $medicId)->select('exp')->get()->all())->exp)) {
                Doctor::where('id', $medicId)->update(['exp' => $request->input('exp')]);
            }
            if($request->has('cabinet') && ($request->input('cabinet') != array_last(Doctor::where('id', $medicId)->select('cabinet')->get()->all())->cabinet) && intval($request->input('cabinet')) > 100) {
                Doctor::where('id', $medicId)->update(['cabinet' => intval($request->input('cabinet'))]);
            }
            return redirect('/admin/update')->with('status', 'successful update');
        }
        return redirect()->back();
    }

    public function updateEmployee(Request $request, $employeeId) {
        if(session('userInfo')->role == "admin") {
            $this->validate($request, [
                'rank' => 'nullable|max:255',
                'salary' => 'nullable|max:10',
                'exp' => 'nullable|max:32',
            ]);
            if($request->has('rank') && ($request->input('rank') != array_last(Employee::where('id', $employeeId)->select('rank')->get()->all())->rank)) {
                Employee::where('id', $employeeId)->update(['rank' => $request->input('rank')]);
            }
            if($request->has('salary') && ($request->input('salary') != array_last(Employee::where('id', $employeeId)->select('salary')->get()->all())->salary) && intval($request->input('salary')) > 0) {
                Employee::where('id', $employeeId)->update(['salary' => intval($request->input('salary'))]);
            }
            if($request->has('exp') && ($request->input('exp') != array_last(Employee::where('id', $employeeId)->select('exp')->get()->all())->exp)) {
                Employee::where('id', $employeeId)->update(['exp' => $request->input('exp')]);
            }
            return redirect('/admin/update')->with('status', 'successful update');
        }
        return redirect()->back();
    }

    public function updateUser(Request $request, $userId) {
        if(session('userInfo')->role == "admin") {
            $this->validate($request, [
                'login' => 'nullable|min:5|max:32|unique:users,login',
                'password' => 'nullable|min:10|max:255',
            ]);
            // Проверяем, заполнено ли поле login
            if($request->has('login')) {
                // Благодаря валидации данных не проверяем на совпадение с актуальной информацией, сразу обновляем.
                User::where('id', $userId)
                        ->update(['login' => $request->input('login')]);
            }
            // Проверяем, заполнено ли поле password
            // проверяем совпадают ли введенные данные с актуальной информацией
            // если ввод не совпадает с актуальной информацией, обновляем
            if((md5($request->input('password')) != array_last(User::where('id', $userId)->select('password')->get()->all())->password) && $request->has('password')) {
                User::where('id', $userId)
                            ->update(['password' => md5($request->input('password'))]);
            }
            return redirect('/admin')->with('status', 'successful update');
        }
        return redirect()->back();
    }

    public function deleteUser($userId) {
        if(session('userInfo')->role == "admin" && array_last(User::where('id', $userId)->get()->all())->role != "admin") {
            User::destroy($userId);
            return redirect('/admin')->with('status', 'successful delete');
        }
        return redirect()->back();
    }

    public function deletePatient($patientId) {
        if(session('userInfo')->role == "admin" && array_last(User::where('id', $userId)->get()->all())->role != "admin") {
            User::destroy(array_last(Patient::where('id',$patientId)->select('user_id')->get()->all())->user_id);
            return redirect('/admin')->with('status', 'successful delete');
        }
        return redirect()->back();
    }

    public function deleteDoctor($medicId) {
        if(session('userInfo')->role == "admin" && array_last(User::where('id', $userId)->get()->all())->role != "admin") {
            User::destroy(array_last(Doctor::where('id',$medicId)->select('user_id')->get()->all())->user_id);
            return redirect('/admin')->with('status', 'successful delete');
        }
        return redirect()->back();
    }

    public function deleteEmployee($employeeId) {
        if(session('userInfo')->role == "admin" && array_last(User::where('id', $userId)->get()->all())->role != "admin") {
            User::destroy(array_last(Employee::where('id',$employeeId)->select('user_id')->get()->all())->user_id);
            return redirect('/admin')->with('status', 'successful delete');
        }
        return redirect()->back();
    }

    public function getIllnesses() {
        if (session('userInfo')->role == "admin") {
            return view('illnesses', ['illnesses' => Illness::all()]);
        }
        return redirect()->back();
    }

    public function addIllness(Request $request) {
        if (session('userInfo')->role == "admin") {
            $this->validate($request, [
                'illness' => 'required|max:255',
            ]);
            Illness::create([
                'illness' => $request->input('illness')
            ]);
        }
        return redirect()->back();
    }

    public function updateIllness(Request $request, $illness_id) {
        if (session('userInfo')->role == "admin") {
            $this->validate($request, [
                'illness' => 'nullable|max:255',
            ]);
            if($request->has('illness') && $request->input('illness') != array_last(Illness::where('id', $illness_id)->select('illness')->get()->all())->illness) {
                Illness::where('id', $illness_id)
                        ->update(['illness' => $request->input('illness')]);
                return redirect('/admin/illness');
            }
        }
        return redirect()->back();
    }

    public function deleteIllness($illness_id) {
        if (session('userInfo')->role == "admin") {
            Illness::destroy($illness_id);
            return redirect('/admin/illness');
        }
        return redirect()->back();
    }
}
