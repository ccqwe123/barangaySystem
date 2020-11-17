<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Barangay;
use App\Residents;
use DB;
use Log;
use Auth;
class BarangayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchBarangay()
    {
        if ($search = \Request::get('search')) {
            $barangay = Barangay::where(function($query) use ($search){
                $query->where('barangay_name','LIKE',"%$search%");
            })->paginate(10);
        }else{
            $barangay = Barangay::select('barangay.*', DB::raw('CONCAT(residents.first_name," ", residents.last_name) AS full_name'))->leftjoin('residents','barangay.barangay_captain_id','=','residents.id')->paginate(10);
        }

        return $barangay;
    }
    public function index()
    {
        // return Barangay::latest()->paginate(10);
        return Barangay::select('barangay.*', DB::raw('CONCAT(residents.first_name," ", residents.last_name) AS full_name'))->leftjoin('residents','barangay.barangay_captain_id','=','residents.id')->paginate(10);
        
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
            'barangay_name' => 'required|min:2|string|max:299'
        ]);
        $brgy = Barangay::create([
            'barangay_name'=> $request['barangay_name']
        ]);
         $test = DB::table('barangay_logo')
                ->insert([
                    'barangay_logo1' => 'default_logo1.png',
                    'barangay_logo2' => 'default_logo2.png',
                    'barangay_id' => $brgy->id,
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
        $this->validate($request, [
            'barangay_name' => 'required|min:2|string|max:299'
        ]);
        $barangay = DB::table('barangay')
            ->where('id', $id)
            ->update([
                'barangay_name' => $request['barangay_name']
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bar = Barangay::findOrFail($id);
        if(Auth::user()->type == 'Admin' || Auth::user()->type == 'admin')
        {
            $bar->delete();
        }else{
            return response()->json(['errors' => 'You are not allowed to Delete the Barangay'], 422);
        }
    }

     public function fetchBarangayAll()
     {
        $data = Barangay::get();
        return response()->json($data);
     }
     public function fetchBarangay(Request $request)
    {
            $data = Barangay::select('residents.id as resident_id','residents.barangay_id', DB::raw('CONCAT(residents.first_name," ", residents.last_name) AS full_name'))
            ->leftjoin('residents','barangay.barangay_captain_id','=','residents.id')
            ->where('barangay.id',$request->barangay_id)
            ->get();
        return response()->json($data);
    }

     public function getStates(Request $request)
    {
        $data = Residents::select('residents.id','residents.barangay_id', DB::raw('CONCAT(residents.first_name," ", residents.last_name) AS full_name'))->where('barangay_id',$request->barangay_id)->get();
        return response()->json($data);
    }
}
