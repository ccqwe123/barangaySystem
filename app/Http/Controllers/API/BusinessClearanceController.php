<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Auth;
use App\BusinessClearance;
use App\Residents;

class BusinessClearanceController extends Controller
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
        if(Auth::user()->type == 'admin') {

        }else{
            return BusinessClearance::orderBy('id','desc')->paginate(10);
        }
        // return BusinessClearance::latest()->paginate(10);
    }
    public function fetchResidents(Request $request)
    {
        if(isset($request->clearance_id)){
            return Residents::select('residents.*', DB::raw('CONCAT(residents.first_name," ", residents.last_name) AS full_name'))->where('id',$request->clearance_id)->get();
        }else{
            return Residents::select('residents.*', DB::raw('CONCAT(residents.first_name," ", residents.last_name) AS full_name'))->get();
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
        $this->validate($request, [
            'business_name' => 'required|min:2|string|max:299',
            'location' => 'required|min:2|string|max:299',
            'requestor_resident_id' => 'required',
            'address' => 'required|min:2|string|max:299',
            'remarks' => 'required|min:2|string|max:299',
            'status' => 'required'
        ]);
        
         $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        return BusinessClearance::create([
            'business_name'=> $request['business_name'],
            'location'=> $request['location'],
            'requestor_resident_id'=> $request['requestor_resident_id'],
            'address'=> $request['address'],
            'name'=> $request['name'],
            'remarks'=> $request['remarks'],
            'status'=> $request['status'],
            'barangay_id'=> $request['barangay_id'],
        ]);


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
        $bc = BusinessClearance::findOrFail($id);
        $this->validate($request, [
            'business_name' => 'required|min:2|string|max:299',
            'location' => 'required|min:2|string|max:299',
            'requestor_resident_id' => 'required',
            'address' => 'required|min:2|string|max:299',
            'remarks' => 'required|min:2|string|max:299',
            'status' => 'required'
        ]);
        $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        $bc->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bc = BusinessClearance::findOrFail($id);
        $bc->delete();
    }
}
