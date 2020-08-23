<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Barangay;
use App\Residents;
use DB;
use Log;
use Auth;

class ResidentController extends Controller
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
    public function searchResidents()
    {
      if ($search = \Request::get('search')) {
        log::info($search);
            $res = Residents::select('residents.*','barangay.barangay_name','barangay.id as brgy_id')->leftjoin('barangay','residents.barangay_id','=','barangay.id')
            ->where('residents.barangay_id', Auth::user()->barangay_id)
            ->where(function($query) use ($search){
                $query->where('residents.first_name','LIKE',"%$search%")
                        ->orWhere('residents.middle_name','LIKE',"%$search%")
                        ->orWhere('residents.last_name','LIKE',"%$search%")
                        ->orWhere('residents.birthdate','LIKE',"%$search%")
                        ->orWhere('residents.age','LIKE',"%$search%")
                        ->orWhere('residents.gender','LIKE',"%$search%")
                        ->orWhere('residents.civil_status','LIKE',"%$search%")
                        ->orWhere('residents.mobile_no','LIKE',"%$search%")
                        ->orWhere('residents.citizenship','LIKE',"%$search%")
                        ->orWhere('residents.employment_status','LIKE',"%$search%")
                        ->orWhere('residents.address','LIKE',"%$search%")
                        ->orWhere('barangay.barangay_name','LIKE',"%$search%");
            })->paginate(10);
        }else{
            $res = Residents::select('residents.*','barangay.barangay_name','barangay.id as brgy_id')->leftjoin('barangay','residents.barangay_id','=','barangay.id')
            ->where('residents.barangay_id', Auth::user()->barangay_id)
            ->paginate(10);
        }
        return $res;
    }
    public function index()
    {
        $test = Residents::select('residents.*','barangay.barangay_name','barangay.id as brgy_id')->leftjoin('barangay','residents.barangay_id','=','barangay.id')
            ->where('residents.barangay_id', Auth::user()->barangay_id)
            ->paginate(10);
        // $test->merge(['test' => 'testtest']);
        return $test;
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
        if($request->employment_status == 'Unemployed')
        {
            $this->validate($request, [
                'first_name' => 'required|min:2|string|max:299',
                'middle_name' => 'required|min:2|string|max:299',
                'last_name' => 'required|min:2|string|max:299',
                'birthdate' => 'required',
                'age' => 'required|numeric',
                'gender' => 'required',
                'civil_status' => 'required',
                'citizenship' => 'required',
                'employment_status' => 'required',
            ]);
        }else if($request->employment_status == NULL){
            $this->validate($request, [
                'first_name' => 'required|min:2|string|max:299',
                'middle_name' => 'required|min:2|string|max:299',
                'last_name' => 'required|min:2|string|max:299',
                'birthdate' => 'required',
                'age' => 'required|numeric',
                'gender' => 'required',
                'civil_status' => 'required',
                'citizenship' => 'required',
                'employment_status' => 'required',
            ]);
        }else if($request->employment_status == 'Employed' || $request->employment_status == 'Self-employed'){
            $this->validate($request, [
                'first_name' => 'required|min:2|string|max:299',
                'middle_name' => 'required|min:2|string|max:299',
                'last_name' => 'required|min:2|string|max:299',
                'birthdate' => 'required',
                'age' => 'required|numeric',
                'gender' => 'required',
                'civil_status' => 'required',
                'citizenship' => 'required',
                'employment_status' => 'required',
                'occupation' => 'required',
            ]);
        }
        
        return Residents::create([
            'first_name'=> $request['first_name'],
            'middle_name'=> $request['middle_name'],
            'last_name'=> $request['last_name'],
            'birthdate'=> $request['birthdate'],
            'birthplace'=> $request['birthplace'],
            'age'=> $request['age'],
            'gender'=> $request['gender'],
            'civil_status'=> $request['civil_status'],
            'mobile_no'=> $request['mobile_no'],
            'citizenship'=> $request['citizenship'],
            'address'=> $request['address'],
            'employment_status'=> $request['employment_status'],
            'occupation'=> $request['employment_status'] == 'Unemployed' ? '' : $request['occupation'],
            'barangay_id'=> Auth::user()->barangay_id
            // 'barangay_id'=> $request['barangay_id']
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
            'first_name' => 'required|min:2|string|max:299',
            'middle_name' => 'required|min:2|string|max:299',
            'last_name' => 'required|min:2|string|max:299',
            'birthdate' => 'required',
            'age' => 'required|numeric',
            'gender' => 'required',
            'civil_status' => 'required',
            'citizenship' => 'required',
            // 'barangay_id' => 'required',
        ]);
        return DB::table('residents')
                ->where('id', $id)
                ->update([
                    'first_name'=> $request['first_name'],
                    'middle_name'=> $request['middle_name'],
                    'last_name'=> $request['last_name'],
                    'birthdate'=> $request['birthdate'],
                    'birthplace'=> $request['birthplace'],
                    'age'=> $request['age'],
                    'gender'=> $request['gender'],
                    'civil_status'=> $request['civil_status'],
                    'mobile_no'=> $request['mobile_no'],
                    'citizenship'=> $request['citizenship'],
                    'address'=> $request['address'],
                    'employment_status'=> $request['employment_status'],
                    'occupation'=> $request['employment_status'] == 'Unemployed' ? NULL : $request['occupation'],
                    // 'barangay_id'=> $request['barangay_id']
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
        $res = Residents::findOrFail($id);
        $res->delete();
    }

    public function barangaySelected(Request $request)
    {
            $data = Barangay::where('id',$request->barangay_id)
            ->get();
        return response()->json($data);
    }

      public function getBarangay(Request $request)
    {
        $data = Barangay::get();
        return response()->json($data);
    }
}
