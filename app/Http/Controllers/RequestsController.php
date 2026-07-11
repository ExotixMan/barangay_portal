<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;
use App\Models\Residents;
use Illuminate\Support\Facades\Auth;

class RequestsController extends Controller
{
    public function service(){
        $resident = Auth::user();

        return view('barangay_system.services', compact('resident'));
    }
}
