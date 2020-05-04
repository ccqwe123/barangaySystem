<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use App\User;
use Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        if($user->type == 'admin'){
            return User::select('users.*','barangay.barangay_name')->leftjoin('barangay','users.barangay_id','=','barangay.id')->paginate(20);
        }else{
            return User::select('users.*','barangay.barangay_name')->leftjoin('barangay','users.barangay_id','=','barangay.id')->where('users.barangay_id', Auth::user()->barangay_id)->paginate(20);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::where('id', Auth::user()->id)->first();
        $this->validate($request, [
            'name' => 'required|min:2|string|max:299',
            'email' => 'required|min:6|email|string|max:299|unique:users',
            'username' => 'required|min:4|unique:users,username',
            'password' => 'required|min:6|max:299',
            'type' => 'required'
        ]);
        if($user->type == 'admin'){
            return User::create([
                'name'=> $request['name'],
                'email'=> $request['email'],
                'username'=> $request['username'],
                'password'=> Hash::make($request['password']),
                'type'=> $request['type'],
                'barangay_id'=> $request['barangay_id'],
            ]);
        }else{
            return User::create([
                'name'=> $request['name'],
                'email'=> $request['email'],
                'username'=> $request['username'],
                'password'=> Hash::make($request['password']),
                'type'=> $request['type'],
                'barangay_id'=> $user->barangay_id,
            ]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|min:2|string|max:299',
            'email' => 'required|min:6|email|string|max:299|unique:users,email,'.$user->id,
            'username' => 'required|min:4|unique:users,username,'.$user->id,
            'password' => 'sometimes|min:6|max:299',
            'type' => 'required'
        ]);
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        return User::where('id', Auth::user()->id)->first();
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
