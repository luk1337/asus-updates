<?php

namespace App\Http\Controllers;

use App\Category;
use App\Device;
use App\Http\Requests;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard')
            ->with('devices', Device::all())
            ->with('categories', Category::all());
    }
}
