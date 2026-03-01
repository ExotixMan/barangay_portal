<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        return view('admin.dashboard');
    }

    public function forecastRequests()
    {
        $python = 'C:\\Users\\Admin\\AppData\\Local\\Programs\\Python\\Python312\\python.exe';
        $script = 'C:\\Users\\Admin\\OneDrive\\Desktop\\Barangay Portal\\barangay-portal\\analytics\\predictive_requests_forecast.py';

        $output = shell_exec("\"$python\" \"$script\" 2>&1");
        // Dump output for debugging
        // dd($output);

        $forecast = json_decode($output, true);

        //for debugging
        if(!$forecast) {
            return response()->json(['error' => $output]);
        }

        return response()->json($forecast);

    }


}
