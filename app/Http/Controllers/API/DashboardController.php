<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Log;
use DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBarangayInfo()
    {
        $data = DB::table('barangay')
                ->select('barangay.barangay_name', DB::RAW("count(residents.barangay_id) as count"))
                ->leftjoin('residents','barangay.id','=','residents.barangay_id')
                ->groupBy('barangay.barangay_name')
                ->get();
        $array_name = [];
        $array_count = [];
        foreach($data as $key => $value)
        {
            // log::info($value->barangay_name);
            $array_name[$key] = [$value->barangay_name];
            $array_count[$key] = [$value->count];
            ++$key;
        }
        return json_encode([
            'array_name' =>$array_name,
            'array_count' =>$array_count
        ]);

    }
    public function index(Request $request)
    {
        $total_users = DB::table('users')->count();
        $total_res = DB::table('residents')->count();
        $total_bar = DB::table('barangay')->count();
        $populationMale20 = $this->getPopulationCount('Male',0,20, $request->barangay_id);
        $populationFemale20 = $this->getPopulationCount('Female',0,20, $request->barangay_id);
        $populationMale40 = $this->getPopulationCount('Male',21,40, $request->barangay_id);
        $populationFemale40 = $this->getPopulationCount('Female',21,40, $request->barangay_id);        
        $populationMale60 = $this->getPopulationCount('Male',41,60, $request->barangay_id);
        $populationFemale60 = $this->getPopulationCount('Female',41,60, $request->barangay_id);        
        $populationMale80 = $this->getPopulationCount('Male',61,80, $request->barangay_id);
        $populationFemale80 = $this->getPopulationCount('Female',61,80, $request->barangay_id);    
        $populationMale100 = $this->getPopulationCount('Male',81,100, $request->barangay_id);
        $populationFemale100 = $this->getPopulationCount('Female',81,100, $request->barangay_id);
        $populationMale120 = $this->getPopulationCount('Male',101,120, $request->barangay_id);
        $populationFemale120 = $this->getPopulationCount('Female',101,120, $request->barangay_id);

        return response()->json([
            'populationMale20' => $populationMale20,
            'populationFemale20' => $populationFemale20,
            'populationMale40' => $populationMale40,
            'populationFemale40' => $populationFemale40,
            'populationMale60' => $populationMale60,
            'populationFemale60' => $populationFemale60,
            'populationMale80' => $populationMale80,
            'populationFemale80' => $populationFemale80,
            'populationMale100' => $populationMale100,
            'populationFemale100' => $populationFemale100,
            'populationMale120' => $populationMale120,
            'populationFemale120' => $populationFemale120,
            'total_res' => $total_res,
            'total_bar' => $total_bar,
            'total_users' => $total_users,
        ]);
        // return Response()->json(array(
        //     'populationMale20' => $populationMale20,
        //     'populationFemale20' => $populationFemale20,
        //     'populationMale40' => $populationMale40,
        //     'populationFemale40' => $populationFemale40,
        //     'populationMale60' => $populationMale60,
        //     'populationFemale60' => $populationFemale60,
        //     'populationMale80' => $populationMale80,
        //     'populationFemale80' => $populationFemale80,
        //     'populationMale100' => $populationMale100,
        //     'populationFemale100' => $populationFemale100,
        //     'populationMale120' => $populationMale120,
        //     'populationFemale120' => $populationFemale120,
        // ));
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
        //
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

    function getPopulationCount($gender,$lower_limit,$upper_limit, $barangay_id)
    {
        if($barangay_id =='' || $barangay_id== NULL || $barangay_id == 0){
            $res = DB::table('residents')
            ->where('gender',$gender)
            // ->orWhere('barangay_id', $barangay_id)
            ->whereBetween('age',[$lower_limit,$upper_limit])
            ->count();
        }else{
            $res = DB::table('residents')
            ->where('gender',$gender)
            ->where('barangay_id', $barangay_id)
            ->whereBetween('age',[$lower_limit,$upper_limit])
            ->count();
        }

        return json_encode($res);
    }

}
