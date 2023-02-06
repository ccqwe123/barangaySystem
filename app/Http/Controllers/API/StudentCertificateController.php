<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Auth;
use App\StudentCertificate;

class StudentCertificateController extends Controller
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
    public function searchStudent()
    {
        if ($search = \Request::get('search')) {
            $clearance = StudentCertificate::leftjoin('residents','student_certificate.requestor_resident_id','=','residents.id')
                ->where(function($query) use ($search){
                $query->where('student_certificate.school_name','LIKE',"%$search%")
                        ->orWhere('student_certificate.date','LIKE',"%$search%")
                        ->orWhere('student_certificate.student_name','LIKE',"%$search%")
                        ->orWhere('residents.first_name','LIKE',"%$search%")
                        ->orWhere('residents.last_name','LIKE',"%$search%");
            })
            ->where('student_certificate.barangay_id', Auth::user()->barangay_id) 
            ->paginate(10);
        }else{
            $clearance = StudentCertificate::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
        }

        return $clearance;
    }
    public function index()
    {
        return StudentCertificate::orderBy('id','desc')->where('barangay_id', Auth::user()->barangay_id)->paginate(10);
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
            'school_name' => 'required|min:2|string|max:299',
            'date' => 'required|min:2|string|max:299',
            'requestor_resident_id' => 'required',
            'purpose' => 'required|min:2|string|max:299',
        ]);

         $request->merge(['barangay_id' => Auth::user()->barangay_id]);
        return StudentCertificate::create([
            'school_name'=> $request['school_name'],
            'date'=> $request['date'],
            'requestor_resident_id'=> $request['requestor_resident_id'],
            'purpose'=> $request['purpose'],
            'student_name'=> $request['name'],
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
        $bc = StudentCertificate::findOrFail($id);
        $this->validate($request, [
            'school_name' => 'required|min:2|string|max:299',
            'date' => 'required|min:2|string|max:299',
            'requestor_resident_id' => 'required',
            'purpose' => 'required|min:2|string|max:299',
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
        $bc = StudentCertificate::findOrFail($id);
        $bc->delete();
    }
}
