<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CrimeType;
use App\Blotter;
use Log;

class CrimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetchData(Request $request)
    {
        if($request->crime_id){
            $crime = Blotter::select(['crime_type.name as crime_type','crime_type.id'])
            ->leftjoin('crime_type','blotter.type_of_crime','=','crime_type.id')
            ->where('blotter.id', $request->crime_id)
            ->get();
        }else{
            $crime = CrimeType::get();
        }
        return $crime;
    }
    public function index()
    {
        return CrimeType::orderBy('id','desc')->paginate(10);
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
            'name' => 'required',
        ]);
        return CrimeType::create([
            'name'=> $request['name'],
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
         $crime = CrimeType::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
        ]);
        $crime->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $crime = CrimeType::findOrFail($id);
        $crime->delete();
    }
}
