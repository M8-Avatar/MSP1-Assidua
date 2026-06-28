<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('dashboard.admin');
    }

    public function apprenant(Request $request)
    {
        return view('dashboard.apprenant', [
            'user' => $request->user(),
        ]);
    }
}