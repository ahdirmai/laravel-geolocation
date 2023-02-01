<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $unit = Auth::user()->userHasUnit->unit;


        // return $unit->longitude;
        $data = [
            'unit' => $unit
        ];

        return view('pages.user.dashboard.index', $data);
    }
}
