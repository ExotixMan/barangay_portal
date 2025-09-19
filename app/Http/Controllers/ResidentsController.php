<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Residents;

class ResidentsController extends Controller
{
    public function index(){
        return view('barangay_system.index');
    }

    public function register_res(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:residents,email',
            'address' => 'required|string',
            'birthdate' => 'required|date',
            'contact' => 'required|string|max:11',
            'username' => 'required|string|unique:residents,username',
            'password' => 'required|string|confirmed|min:8'
        ]);

        $data['password'] = Hash::make($data['password']);

        Residents::create($data);

        dd('Test record saved!');
    }

    public function login_res(Request $request){

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
        $user = Residents::where('username', '=', $request->username)->first();
        if($user) {
            if (Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                return view('barangay_system.index');
            } else {
                return redirect('login')->with('fail', 'Password not matches.');
            }
        } else {
            return redirect('login')->with('fail', 'This email is not registered.');
        }
    }

}
