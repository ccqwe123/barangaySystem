<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use Auth;
use App\ResidentCertificate;

class ResidentCertificateController extends Controller
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
        return ResidentCertificate::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
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
        return ResidentCertificate::create([
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
         $resident = ResidentCertificate::findOrFail($id);
        $this->validate($request, [
            'requestor_resident_id' => 'required',
            'address' => 'required|min:2|string|max:299',
            'purpose' => 'required|min:2|string|max:299',
        ]);
        $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        $resident->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resident = ResidentCertificate::findOrFail($id);
        $resident->delete();
    }
}
