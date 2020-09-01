<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use Auth;
use App\Indigency;

class IndigencyCertificateController extends Controller
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
    public function searchIndifency()
    {
        if ($search = \Request::get('search')) {
            $clearance = Indigency::leftjoin('residents','barangay_indigency.requestor_resident_id','=','residents.id')
                ->where(function($query) use ($search){
                $query->where('barangay_indigency.name','LIKE',"%$search%")
                        ->orWhere('barangay_indigency.address','LIKE',"%$search%")
                        ->orWhere('residents.first_name','LIKE',"%$search%")
                        ->orWhere('residents.last_name','LIKE',"%$search%");
            })
            ->where('barangay_indigency.barangay_id', Auth::user()->barangay_id) 
            ->paginate(10);
        }else{
            $clearance = Indigency::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
        }

        return $clearance;
    }
    public function index()
    {
        return Indigency::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
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
        return Indigency::create([
            'requestor_resident_id'=> $request['requestor_resident_id'],
            'name'=> $request['name'],
            'address'=> $request['address'],
            'purpose'=> $request['purpose'],
            'barangay_id'=> $request['barangay_id'],
        ]);
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
        $indigency = Indigency::findOrFail($id);
        $this->validate($request, [
            'requestor_resident_id' => 'required',
            'address' => 'required|min:2|string|max:299',
            'purpose' => 'required|min:2|string|max:299',
        ]);
        $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        $indigency->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $indigency = Indigency::findOrFail($id);
        $indigency->delete();
    }
}
