<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;
use App\Varpath;
use App\User;
use App\Barangay;
use Auth;
use Carbon\Carbon;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $varpath = Varpath::where('barangay_id', Auth::user()->barangay_id)->get();
        $user_barangay = User::join('barangay','users.barangay_id','=','barangay.id')->where('users.id', Auth::user()->id)->first();
        return json_encode([
            'varpath' =>$varpath,
            'user_barangay' =>$user_barangay
        ]);

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
        $date_now = Carbon::now();
        $var = Varpath::where('barangay_id',Auth::user()->barangay_id)->first();
        $collection = collect($request->all());
        if($var == '' || $var == NULL){
            foreach ($collection as $var) {
                if($var)
                {
                    DB::table('var_path')
                    ->insert([
                        'name' => $var,
                        'value' => $var,
                        'barangay_id' => Auth::user()->barangay_id,
                        'created_at' => $date_now,
                    ]);
                }else {}
            }
        log::info($request);
            $update_existing_data = Varpath::where('barangay_id', Auth::user()->barangay_id)->get();
            foreach ($update_existing_data as $var_rename) {
                if($var_rename->name == $request->barangay_city) {
                    //update barangay city
                    DB::table('var_path')
                        ->where('name', $request->barangay_city)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_city',
                    ]);
                }else if($var_rename->name == $request->barangay_id) {
                    //update barangay name
                    DB::table('var_path')
                        ->where('name', $request->barangay_id)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_name',
                    ]);
                }else if($var_rename->name == $request->barnagay_zone) {
                    // update barangay zone
                    DB::table('var_path')
                        ->where('name', $request->barnagay_zone)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barnagay_zone',
                    ]);
                }else if($var_rename->name == $request->barangay_address) {
                    // update barangay address
                    DB::table('var_path')
                        ->where('name', $request->barangay_address)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_address',
                    ]);
                }else if($var_rename->name == $request->barangay_contact) {
                    //update barangay contact
                    DB::table('var_path')
                        ->where('name', $request->barangay_contact)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_contact',
                    ]);
                }else if($var_rename->name == $request->software_name) {
                    //update software name
                    DB::table('var_path')
                        ->where('name', $request->software_name)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'software_name',
                    ]);
                }else if($var_rename->name == $request->software_description) {
                    //update software description
                    DB::table('var_path')
                        ->where('name', $request->software_description)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'software_description',
                    ]);
                }
            }
        }else{
            $old_data = Varpath::where('barangay_id', Auth::user()->barangay_id);
            $old_data->delete();
            //update
            $collection = collect($request->all());
             foreach ($collection as $var) {
                if(count($var)>0)
                {
                    DB::table('var_path')
                    ->insert([
                        'name' => $var,
                        'value' => $var,
                        'barangay_id' => Auth::user()->barangay_id,
                        'created_at' => $date_now,
                    ]);
                }else {}
            }
            $update_existing_data = Varpath::where('barangay_id', Auth::user()->barangay_id)->get();
            foreach ($update_existing_data as $var_rename) {
                if($var_rename->name == $request->barangay_city) {
                    //update barangay city
                    DB::table('var_path')
                        ->where('name', $request->barangay_city)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_city',
                    ]);
                }else if($var_rename->name == $request->barangay_id) {
                    //update barangay name
                    DB::table('var_path')
                        ->where('name', $request->barangay_id)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_name',
                    ]);
                }else if($var_rename->name == $request->barnagay_zone) {
                    // update barangay zone
                    DB::table('var_path')
                        ->where('name', $request->barnagay_zone)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barnagay_zone',
                    ]);
                }else if($var_rename->name == $request->barangay_address) {
                    // update barangay address
                    DB::table('var_path')
                        ->where('name', $request->barangay_address)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_address',
                    ]);
                }else if($var_rename->name == $request->barangay_contact) {
                    //update barangay contact
                    DB::table('var_path')
                        ->where('name', $request->barangay_contact)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'barangay_contact',
                    ]);
                }else if($var_rename->name == $request->software_name) {
                    //update software name
                    DB::table('var_path')
                        ->where('name', $request->software_name)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'software_name',
                    ]);
                }else if($var_rename->name == $request->software_description) {
                    //update software description
                    DB::table('var_path')
                        ->where('name', $request->software_description)
                        ->where('barangay_id', Auth::user()->barangay_id)
                        ->update([
                            'name' => 'software_description',
                    ]);
                }
        }}

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
    public function setBarangayLogo()
    {
        
    }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getBarangayLogo()
    {
        $brgy_id = Auth::user()->barangay_id;
        return DB::table('barangay_logo')->where('barangay_id', $brgy_id)->get();
    }
    public function updateBarangayLogo(Request $request)
    {
        // Log::info($request);
        $brgy = Barangay::where('id', Auth::user()->barangay_id)->first();
        $check_data = DB::table('barangay_logo')->where('barangay_id', Auth::user()->barangay_id)->first();
        if(count($check_data)>0)
        {
            $existing_logo1 = $check_data->barangay_logo1;
            $existing_logo2 = $check_data->barangay_logo2;
        }else{
            $existing_logo1 = '';
            $existing_logo2 = '';
        }
        if(!empty($request->barangay_logo1) && strlen($request->barangay_logo1)>500)
        {
            if($request->barangay_logo1 != $existing_logo1)
            {
                $barangay_logo1 = $brgy->barangay_name.'_logo1.' . explode('/', explode(':', substr($request->barangay_logo1, 0, strpos($request->barangay_logo1, ';')))[1])[1];
                \Image::make($request->barangay_logo1)->save(public_path('images/certificate/').$barangay_logo1);

                $logo1 = public_path('images/certificate/').$existing_logo1;
                if(count($existing_logo1)>0){
                    //  if(file_exists($logo1)){
                    //     @unlink($logo1);
                    // }
                }else{
                }
               
            }
        }else{
           $barangay_logo1 =  $existing_logo1;
        }

        
        if(!empty($request->barangay_logo2) && strlen($request->barangay_logo2)>500)
        {
            if($request->barangay_logo2 != $existing_logo2)
            {
                $barangay_logo2 = $brgy->barangay_name.'_logo2.' . explode('/', explode(':', substr($request->barangay_logo2, 0, strpos($request->barangay_logo2, ';')))[1])[1];
                \Image::make($request->barangay_logo2)->save(public_path('images/certificate/').$barangay_logo2);
                $logo2 = public_path('images/certificate/').$existing_logo2;
                if(count($existing_logo2)>0){
                    //  if(file_exists($logo2)){
                    //     @unlink($logo2);
                    // }
                }else{
                }
            }
        }else {
            $barangay_logo2 = $existing_logo2;
        }
            
        
        if(count($check_data)>0)
        {
            // update
            DB::table('barangay_logo')
                ->where('barangay_id', Auth::user()->barangay_id)
                ->update([
                    'barangay_logo1' => $barangay_logo1,
                    'barangay_logo2' => $barangay_logo2,
                    'barangay_id' => Auth::user()->barangay_id,
            ]);
        }else{
            DB::table('barangay_logo')
                ->insert([
                    'barangay_logo1' => $barangay_logo1,
                    'barangay_logo2' => $barangay_logo2,
                    'barangay_id' => Auth::user()->barangay_id,
            ]);
        }
        return ['message' => "Barangay Logo successfully Updated!"];

    }
    public function destroy($id)
    {
        //
    }
    public function fetchVariable()
    {
        return DB::table('var_path')->where('barangay_id', Auth::user()->barangay_id)->get();
    }
}
