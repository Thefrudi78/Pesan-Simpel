<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::all();
        return view('dashboard.index', compact('users'));
    }
}
