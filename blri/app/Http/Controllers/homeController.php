<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\setuptype;
use App\SecurityType;
use App\ProductReceiveType;
use App\ProductDistribution;
use App\AdjustmentMenu;
use App\Reporting;//model name;
use App\User;
use App\EmployeeInformation;


class homeController extends Controller
{
  
    public function index()
    {
        $setuptypes= setuptype::all();
        $securitytypes=SecurityType::all();
        $productreceivetypes=ProductReceiveType::all();
        $productdistributions=ProductDistribution::all();
        $adjustments=AdjustmentMenu::all();
        $reportings=Reporting::all();
        $users=User::all();
        //$employeeinfo=EmployeeInformation::all();
        //dd($sections[0]->division);
        return view('home.index')->with('setuptypes',$setuptypes)
        ->with('securitytypes',$securitytypes)
        ->with('productreceivetypes',$productreceivetypes)
        ->with('productdistributions',$productdistributions)
        ->with('users',$users)
    
        ->with('adjustments',$adjustments)->with('reportings',$reportings);
        

        //dd($sections[0]->division);
        
    }
}
