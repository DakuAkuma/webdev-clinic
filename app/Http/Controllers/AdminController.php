<?php

namespace App\Http\Controllers;

use App\Patient;
use App\Doctor;
use App\Employee;
use App\User;
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

    public function deleteUser($userId) {
        if(session('userInfo')->role == "admin" && array_last(User::where('id', $userId)->get()->all())->role != "admin") {
            User::destroy($userId);
            return redirect('/admin')->with('status', 'successful delete');
        }
        return redirect()->back();
    }

    public function deletePatient($patientId) {

    }
}
