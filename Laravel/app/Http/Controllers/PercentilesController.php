<?php

namespace App\Http\Controllers;

class PercentilesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verPercentiles()
    {
    	return view('percentiles');
    }

}