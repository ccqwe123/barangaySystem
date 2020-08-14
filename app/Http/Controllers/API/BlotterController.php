<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blotter;
use App\PersonsOfInterest;
use Log;
use Auth;

class BlotterController extends Controller
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
    public function index()
    {
        Log::info(Auth::user());
        return Blotter::leftjoin('blotter_personsofinterest','blotter.id','=','blotter_personsofinterest.blotter_id')
            ->leftjoin('crime_type','blotter.type_of_crime','crime_type.id')
            ->select([
                'blotter_personsofinterest.name',
                'blotter.date_of_incident',
                'crime_type.name as crime_type',
                'blotter.time_reported',
                'blotter.type',
            ])
            ->where('blotter.barangay_id','=', Auth::user()->barangay_id)
            ->orderBy('blotter.id','desc')
            ->paginate(10);
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
                'desposition' => 'required',
                'lupon' => 'required',
                'date_incident' => 'required',
                'date_reported' => 'required',
                'incident_address' => 'required',
                'case_summary' => 'required',
            ]);
        // log::info($request->complainants[0]['complainant_name']);
         $blotter = Blotter::create([
            'type'=> $request['desposition'],
            'lupon'=> $request['lupon'],
            'date_of_incident'=> $request['date_incident'],
            'address'=> $request['incident_address'],
            'time_reported'=> $request['date_reported'],
            'type_of_crime'=> $request['crime_type_id'],
            'case_summary'=> $request['case_summary'],
            'status'=> "Pending",
            'hearing_count'=> 0,
            'barangay_id'=> Auth::user()->barangay_id,
        ]);
         //complainants
        for($x=0;$x<count($request->complainants);$x++)
        {
            PersonsOfInterest::create([
                'blotter_id'=>$blotter->id,
                'type'=>'victim',
                'name'=>$request->complainants[$x]['complainant_name'],
                'address'=>$request->complainants[$x]['address'],
                'age'=>$request->complainants[$x]['age'],
                'telephone'=>$request->complainants[$x]['telephone'],
            ]);
        }
        //respondents or suspect
        for($x=0;$x<count($request->respondents);$x++)
        {
            PersonsOfInterest::create([
                'blotter_id'=>$blotter->id,
                'type'=>'suspect',
                'name'=>$request->respondents[$x]['respondent_name'],
                'address'=>$request->respondents[$x]['respondent_address'],
                'telephone'=>$request->respondents[$x]['respondent_telephone'],
                'age'=>$request->respondents[$x]['respondent_age'],
            ]);
        }
        //witness
        for($x=0;$x<count($request->witnesses);$x++)
        {
            PersonsOfInterest::create([
                'blotter_id'=>$blotter->id,
                'type'=>'witness',
                'name'=>$request->witnesses[$x]['witness_name'],
                'address'=>$request->witnesses[$x]['witness_address'],
                'telephone'=>$request->witnesses[$x]['witness_telephone'],
                'age'=>$request->witnesses[$x]['witness_age'],
            ]);
        }
        return $blotter;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
