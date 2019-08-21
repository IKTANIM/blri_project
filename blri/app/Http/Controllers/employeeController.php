<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\setuptype;
use App\SecurityType;


class employeeController extends Controller
{
    public function index(){
    	$securitytypes=SecurityType::all();
    	$setuptypes= setuptype::all();
       
        //dd($sections[0]->division);
        return view('setup.employee')->with('setuptypes',$setuptypes)->with('securitytypes',$securitytypes);
    
      }
}