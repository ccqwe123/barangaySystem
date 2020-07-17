<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Log;
use App\Varpath;

class HomeController extends Controller
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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function login()
    {
        // $var = DB::table('var_path')->where('name','=','system_title')->first();
        $var = Varpath::where('name','=','system_title')->first();
        return view('auth.login', ['system_title'=>$var]);
    }
    public function index()
    {
        return view('home');
    }
}
