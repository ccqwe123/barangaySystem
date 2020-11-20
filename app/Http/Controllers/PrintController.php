<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Log;
use Auth;
use App\BusinessClearance;
use App\Residents;
use App\Varpath;
use PDF;
class PrintController extends Controller
{
	public function bClearance(Request $request)
	{
		$getsumm = Varpath::where('name','=','software_description')->where('barangay_id',1)->first();
		$arrayOfTags = ['{tag1}', '{tag2}'];

		$arrayOfValues = ['value of tag1', 'value of tag2'];

		$printThis = str_replace($arrayOfTags, $arrayOfValues, $getsumm->value);


		$pdf = PDF::loadView('pdf.business_clearance', compact('printThis'));
		return $pdf->stream('testinglangs.pdf');
		// return view('pdf.business_clearance');
	}
}
