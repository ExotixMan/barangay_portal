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
        $script = 'C:\\Users\\Admin\\OneDrive\\Desktop\\Barangay Portal\\barangay-portal\\analytics\\analytics.py';

        $output = shell_exec("\"$python\" \"$script\" 2>&1");

        // After script runs — read the saved JSON file
        $forecastPath = public_path('analytics/forecast.json');

        if (!file_exists($forecastPath)) {
            return response()->json(['error' => 'Forecast file not found']);
        }

        $forecast = json_decode(file_get_contents($forecastPath), true);

        return response()->json($forecast);

    }


}
