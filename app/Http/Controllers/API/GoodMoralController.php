<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GoodMoral;
use Log;
use Auth;

class GoodMoralController extends Controller
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
    public function searchGoodmoral()
    {
        if ($search = \Request::get('search')) {
            $clearance = GoodMoral::leftjoin('residents','good_moral.requestor_resident_id','=','residents.id')
                ->where(function($query) use ($search){
                $query->where('good_moral.name','LIKE',"%$search%")
                        ->orWhere('good_moral.address','LIKE',"%$search%")
                        ->orWhere('residents.first_name','LIKE',"%$search%")
                        ->orWhere('residents.last_name','LIKE',"%$search%");
            })
            ->where('good_moral.barangay_id', Auth::user()->barangay_id)
            ->paginate(10);
        }else{
            $clearance = GoodMoral::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
        }

        return $clearance;
    }
    public function index()
    {
        return GoodMoral::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
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
            'requestor_resident_id' => 'required',
            'address' => 'required|min:2|string|max:299',
            'purpose' => 'required|min:2|string|max:299',
        ]);

         $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        return GoodMoral::create([
            'requestor_resident_id'=> $request['requestor_resident_id'],
            'name'=> $request['name'],
            'address'=> $request['address'],
            'purpose'=> $request['purpose'],
            'barangay_id'=> $request['barangay_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function fetchData(Request $request)
    {
        return GoodMoral::where('barangay_id', Auth::user()->barangay_id)->where('id',$request->id)->first();
    }
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
        $gm = GoodMoral::findOrFail($id);
        $this->validate($request, [
            'requestor_resident_id' => 'required',
            'address' => 'required|min:2|string|max:299',
            'purpose' => 'required|min:2|string|max:299',
        ]);
        $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        $gm->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gm = GoodMoral::findOrFail($id);
        $gm->delete();
    }
}
