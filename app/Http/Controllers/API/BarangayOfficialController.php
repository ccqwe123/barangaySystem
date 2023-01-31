<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BarangayOfficials;
use DB;
use Log;
use Auth;
use App\User;

class BarangayOfficialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // $this->middleware('api');
        $this->middleware('auth:api');
    }

    public function index()
    {
         return BarangayOfficials::select('barangay.barangay_name', 'barangay_officials.*')
                     ->leftjoin('barangay','barangay_officials.barangay_id','=','barangay.id')
                     ->paginate(100);
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
        log::info($request);
        $check_user_if_admin = User::where('id', Auth::user()->id)->first();
        if($check_user_if_admin->type == 'admin'){
            $this->validate($request, [
                'name' => 'required|min:2|string|max:299',
                'position' => 'required',
                'barangay_id' => 'required'
            ]);
        }else{
            $this->validate($request, [
                'name' => 'required|min:2|string|max:299',
                'position' => 'required',
            ]);
        }
        
        $captain = BarangayOfficials::where('position', $request->position)
                    ->where('barangay_id', Auth::user()->barangay_id)
                    ->get();
        // check if barangay captain already exist
        if($request->position == 'barangay captain')
        {
            if(count($captain) > 0)
            {
                return response()->json(['errors' => 'You cant add more than 1 Barangay Captain'], 422);
            }else{
                return BarangayOfficials::create([
                    'name'=> $request['name'],
                    'position'=> $request['position'],
                    'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                ]);
            }
        // check maximum kagawad
        }else if($request->position == 'kagawad')
        {
            if(count($captain) > 6)
            {
                return response()->json(['errors' => 'You cant add more than 7 Barangay Kagawad'], 422);
            }else{
                return BarangayOfficials::create([
                    'name'=> $request['name'],
                    'position'=> $request['position'],
                    'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                ]);
            }
        }else if($request->position == 'secretary')
        {
            if(count($captain) > 0)
            {
                return response()->json(['errors' => 'You cant add more than 1 Barangay Secretary'], 422);
            }else{
                return BarangayOfficials::create([
                    'name'=> $request['name'],
                    'position'=> $request['position'],
                    'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                ]);
            }
        }else if($request->position == 'treasurer')
        {
            if(count($captain) > 0)
            {
                return response()->json(['errors' => 'You cant add more than 1 Trasurer'], 422);
            }else{
                return BarangayOfficials::create([
                    'name'=> $request['name'],
                    'position'=> $request['position'],
                    'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                ]);
            }
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
        $check_user = User::where('id', Auth::user()->id)->first();
        $check_position = BarangayOfficials::where('id',$id)->first();

        if($check_user->type == 'admin'){
            $this->validate($request, [
                'name' => 'required|min:2|string|max:299',
                'position' => 'required',
                'barangay_id' => 'required'
            ]);
        }else{
            $this->validate($request, [
                'name' => 'required|min:2|string|max:299',
                'position' => 'required',
            ]);
        }
        // log::info($request);
        $captain = BarangayOfficials::where('position', $request->position)
                    ->where('barangay_id', $request->barangay_id)
                    ->get();
        if(count($captain)>0)
        {
            
            if($request->position == 'barangay captain')
            {
                if($check_position->position == $captain[0]->position)
                {
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);
                }else{
                    if(count($captain) > 0)
                    {
                        return response()->json(['errors' => 'You cant add more than 1 Barangay Captain'], 422);
                    }
                }
            
            }else if($request->position == 'kagawad')
            {
                if($check_position->position == $captain[0]->position)
                {
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);

                }elseif(count($captain) > 6){
                        return response()->json(['errors' => 'You cant add more than 7 Barangay Kagawad'], 422);
                }else{
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);
                }
            }else if($request->position == 'secretary')
            {
                if($check_position->position == $captain[0]->position)
                {
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);

                }elseif(count($captain) > 0){
                        return response()->json(['errors' => 'You cant add more than 1 Secretary'], 422);
                }else{
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);
                }
            }else if($request->position == 'treasurer')
            {
                if($check_position->position == $captain[0]->position)
                {
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);

                }elseif(count($captain) > 0){
                        return response()->json(['errors' => 'You cant add more than 1 Trasurer'], 422);
                }else{
                    return DB::table('barangay_officials')
                    ->where('id', $id)
                    ->update([
                        'name'=> $request['name'],
                        'position'=> $request['position'],
                        'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                    ]);
                }
            }
        }else{
             return DB::table('barangay_officials')
                ->where('id', $id)
                ->update([
                    'name'=> $request['name'],
                    'position'=> $request['position'],
                    'barangay_id'=> Auth::user()->type == 'admin' ? $request['barangay_id'] : Auth::user()->barangay_id,
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = BarangayOfficials::findOrFail($id);
        $data->delete();
    }
    public function fetchOfficials()
    {
        $captain = BarangayOfficials::where('barangay_id', Auth::user()->barangay_id)
                                ->where('position','=','barangay captain')
                                ->first();
        $secretary = BarangayOfficials::where('barangay_id', Auth::user()->barangay_id)
                                ->where('position','=','secretary')
                                ->first();
        $treasurer = BarangayOfficials::where('barangay_id', Auth::user()->barangay_id)
                                ->where('position','=','treasurer')
                                ->first();                                             
        $kagawad = BarangayOfficials::where('barangay_id', Auth::user()->barangay_id)
                                ->where('position','=','kagawad')
                                ->get();
        return response()->json([
            'captain' => $captain,
            'secretary' => $secretary,
            'treasurer' => $treasurer,
            'kagawad' => $kagawad
        ]);
    }
    // function getKagawad($position, $barangay_id)
    // {
    //     if($barangay_id =='' || $barangay_id== NULL || $barangay_id == 0){
    //         $kagawad = DB::table('barangay_officials')
    //         ->where('position',$position)
    //         ->get();
    //     }else{
    //         $kagawad = DB::table('barangay_officials')
    //         ->where('position',$position)
    //         ->where('barangay_id', $barangay_id)
    //         ->get();
    //     }

    //     return json_encode($kagawad);
    // }
}
