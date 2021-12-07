<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Index page.
     * 
     * @return view
     */
    public function index()
    {
        return view('index');
    }
}
