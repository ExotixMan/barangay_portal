<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Residents;
use PDO;

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
            'password' => 'required|string|confirmed'
        ]);

        $data['password'] = Hash::make($data['password']);

        Residents::create($data);

        return redirect('login')->with('success', 'Account registered successfully!');
    }

    public function login_res(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return redirect('login')->with('fail', 'Invalid username or password.');
    }

    public function destroy(Request $request)
    {
        $request->session()->forget('loginId');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

}
