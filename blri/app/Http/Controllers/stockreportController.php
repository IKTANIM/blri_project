<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\setuptype;
use App\SecurityType;
use App\ProductReceiveType;
use App\ProductDistribution;
use App\Reporting;//model name;
use App\AdjustmentMenu;

class stockreportController extends Controller
{
public function index()
    {
        $setuptypes= setuptype::all();
        $securitytypes=SecurityType::all();
        $productreceivetypes=ProductReceiveType::all();
        $productdistributions=ProductDistribution::all();
        $reportings=Reporting::all();
        $adjustments=AdjustmentMenu::all();

        //dd($sections[0]->division);
        return view('reporting.stock report')->with('setuptypes',$setuptypes)->with('securitytypes',$securitytypes)->with('productreceivetypes',$productreceivetypes)->with('productdistributions',$productdistributions)->with('reportings',$reportings)->with('adjustments',$adjustments);
    }
    
}
