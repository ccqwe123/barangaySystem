<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Blotter;
use App\CrimeType;
use App\PersonsOfInterest;
use Log;
use Auth;
use DB;
use Carbon\Carbon;

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
        return Blotter::leftjoin('blotter_personsofinterest','blotter.id','=','blotter_personsofinterest.blotter_id')
            ->leftjoin('crime_type','blotter.type_of_crime','crime_type.id')
            ->select([
                'blotter_personsofinterest.name',
                'blotter.date_of_incident',
                'crime_type.name as crime_type',
                'blotter.time_reported',
                'blotter.type',
                'blotter.id',
            ])
            ->where('blotter.barangay_id','=', Auth::user()->barangay_id)
            ->orderBy('blotter.id','desc')
            ->groupBy('blotter.id')
            ->paginate(3);
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
        // return Blotter::leftjoin('blotter_personsofinterest','blotter.id','=','blotter_personsofinterest.blotter_id')->findorfail($id);
        $blotter_person_complainant = PersonsOfInterest::select(['name as complainant_name','address','telephone','age'])
                ->where('type','victim')->where('blotter_id', $id)->get();
        $blotter_person_respondent = PersonsOfInterest::select(['name as respondent_name','address as respondent_address','telephone as respondent_telephone','age as respondent_age'])
                ->where('type','suspect')->where('blotter_id', $id)->get();
        $blotter_person_witness = PersonsOfInterest::select(['name as witness_name','address as witness_address','telephone as witness_telephone','age as witness_age'])
                ->where('type','witness')->where('blotter_id', $id)->get();
        $blotter = Blotter::findorfail($id);
        $crime_type = CrimeType::select('crime_type.id')
                        ->leftjoin('blotter','blotter.type_of_crime','crime_type.id')
                        ->where('blotter.id', $id)
                        ->get();
        $blotter_complainant_count = PersonsOfInterest::where('type','victim')->where('blotter_id', $id)->count();
        return response()->json([
            'blotter_person_complainant' => $blotter_person_complainant,
            'blotter_person_respondent' => $blotter_person_respondent,
            'blotter_person_witness' => $blotter_person_witness,
            'blotter_complainant_count' => $blotter_complainant_count,
            'blotter' => $blotter,
            'crime_type' => $crime_type,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // log::info($id);
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
                'type' => 'required',
                'lupon' => 'required',
                'date_of_incident' => 'required',
                'date_reported' => 'required|not_in:"Invalid date"',
                'address' => 'required',
                'case_summary' => 'required',
            ]);
           DB::table('blotter')
                ->where('id', $id)
                ->update([
                   'type'=> $request['type'],
                    'lupon'=> $request['lupon'],
                    'date_of_incident'=> $request['date_of_incident'],
                    'address'=> $request['address'],
                    'time_reported'=> $request['date_reported'],
                    'type_of_crime'=> $request['crime_type_id'],
                    'case_summary'=> $request['case_summary'],
                    'status'=> "Pending",
                    'hearing_count'=> 0,
                ]);

         // complainants
        $complaint = PersonsOfInterest::where('blotter_id', $id)
                    ->where('type','=','victim')
                    ->delete();
        for($x=0;$x<count($request->complaints);$x++)
        {
            PersonsOfInterest::create([
                'blotter_id'=>$id,
                'type'=>'victim',
                'name'=>$request->complaints[$x]['complainant_name'],
                'address'=>$request->complaints[$x]['address'],
                'age'=>$request->complaints[$x]['age'],
                'telephone'=>$request->complaints[$x]['telephone'],
            ]);
        }
        // // respondents or suspect
        $respo = PersonsOfInterest::where('blotter_id', $id)
                    ->where('type','=','suspect')
                    ->delete();
        for($x=0;$x<count($request->respondents);$x++)
        {
            PersonsOfInterest::create([
                'blotter_id'=>$id,
                'type'=>'suspect',
                'name'=>$request->respondents[$x]['respondent_name'],
                'address'=>$request->respondents[$x]['respondent_address'],
                'telephone'=>$request->respondents[$x]['respondent_telephone'],
                'age'=>$request->respondents[$x]['respondent_age'],
            ]);
        }
        // //witness
        $witness = PersonsOfInterest::where('blotter_id', $id)
                    ->where('type','=','witness')
                    ->delete();
        for($x=0;$x<count($request->witnesses);$x++)
        {
            PersonsOfInterest::create([
                'blotter_id'=>$id,
                'type'=>'witness',
                'name'=>$request->witnesses[$x]['witness_name'],
                'address'=>$request->witnesses[$x]['witness_address'],
                'telephone'=>$request->witnesses[$x]['witness_telephone'],
                'age'=>$request->witnesses[$x]['witness_age'],
            ]);
        }
        // return $blotter;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Blotter::findOrFail($id);
        $data->delete();
        $person = PersonsOfInterest::where('blotter_id', $id)->delete();
    }
}
