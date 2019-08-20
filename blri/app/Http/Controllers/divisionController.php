<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\setuptype;
use App\division;
use App\SecurityType;
use Illuminate\Support\Facades\DB;

class divisionController extends Controller
{
    public function index(){
      $setuptypes= setuptype::all();
      $divisions=division::all();
      $securitytypes=SecurityType::all();

      return view('setup.division')->with('setuptypes',$setuptypes)->with('divisions',$divisions)->with('securitytypes',$securitytypes);
       }
      
      public function divisionPost(Request $request){
               $this->validate( $request,[
                
                'divisionName'=>'required|unique:divisions',
              
               ]);
    	       $setuptypes= setuptype::all();
               $division=new division;
               $division->divisionName=$request->divisionName;
               $division->save();
               $divisions=division::all();
               

              return view('setup.division')->with('setuptypes',$setuptypes)->with('divisions',$divisions);
          }
        public function divedit(Request $request,$id)
     {
             $setuptypes= setuptype::all();
             $securitytypes=SecurityType::all();
             $division=division::find($id);
    	       return view('setup.divedit')->with('division',$division)->with('setuptypes',$setuptypes)->with('securitytypes',$securitytypes);
     }

     public function update(Request $request,$id)
    {
    	//dd('success');
    	          $this->validate( $request,[
                
                'divisionName'=>'required|unique:divisions',
              
               ]);
    	$division=division::find($id);
    	$division->divisionName=$request->divisionName;
    	$division->save();
      return redirect()->route('setup.division');
    	
    }          
       
    
               
    }


