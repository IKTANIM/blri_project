<?php

namespace App\Http\Controllers;

use App\Project;
use App\division;
use App\Reporting;
use App\setuptype;
use App\AdjustmentMenu;
use App\Emp_assign;
use App\SerialInfo;
use App\SecurityType;
use App\ProductReceiveType;
use App\ProductReleaseInfo;
use App\EmployeeInformation;
use App\ProductDistribution;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class productreleaseController extends Controller
{
    public function getAllAssignedEmployees($project)
    {
        $allAssignedEmployees=[];
        array_push($allAssignedEmployees, $project->employeeinfo);
                
        $assignedEmployees=$project->assignedEmployees;
        foreach ($assignedEmployees as $key => $item) {
            array_push($allAssignedEmployees, $item->employeeinfo);
        }
        return $allAssignedEmployees;
    }
    public function index(Request $request)
    {
     if ($request->session()->get('user')) {
         $setuptypes= setuptype::all();
         $securitytypes= SecurityType::all();
         $productreceivetypes=ProductReceiveType::all();
         $productdistributions=ProductDistribution::all();
         $adjustments=AdjustmentMenu::all();
         $reportings=Reporting::all();
         $projects= Project::all();
         $divisions=division::all();
         $serialInfo=SerialInfo::all();
         $productReleaseInfo= ProductReleaseInfo::all();
         $allAssignedEmployees=[];
        if(old('projectName')){
            $project=Project::find(old('projectName'));
            if($project){
               $allAssignedEmployees=$this->getAllAssignedEmployees($project);
            }
        }

         return view('product distribution.product release')
               ->with('setuptypes', $setuptypes)
               ->with('securitytypes', $securitytypes)
               ->with('productreceivetypes', $productreceivetypes)
               ->with('productdistributions', $productdistributions)
               ->with('adjustments', $adjustments)
               ->with('divisions', $divisions)
               ->with('projects', $projects)
               ->with('serialInfo', $serialInfo)
               ->with('productReleaseInfo', $productReleaseInfo)
               ->with('assignedEmployees', $allAssignedEmployees)
               ->with('reportings', $reportings);
     }
     else{
          return "<h2>The route you are looking for is not available.</h2>";
      }
    }

    public function saveContent(Request $request,ProductReleaseInfo $productReleaseInfo)
    {
        $productReleaseInfo->releaseDate=date('Y-m-d',strtotime(str_replace('/','-',$request->releaseDate)));
        $productReleaseInfo->division_id=$request->deptName;
        $productReleaseInfo->project_id=$request->projectName;
        $productReleaseInfo->employee_information_id=$request->employeeName;
        $productReleaseInfo->serial_info_id=$request->serialNo;
        $productReleaseInfo->receiveBy=$request->session()->get('user')->id;
        $productReleaseInfo->save();
        // return true;
    }

    public function store(Request $request)
    {
            // dd($request->all());
        $this->validate($request,[
          'serialNo'=>'required | unique:product_release_infos,serial_info_id',
          'releaseDate'=>'required | date_format:d/m/Y| before_or_equal: today',
          'deptName'=>'required',
          'projectName'=>'required',
          'employeeName'=>'required'
        ]);
        
        $this->saveContent($request,new ProductReleaseInfo);
        

        return redirect()->route('product distribution.product release');

    }
    public function editItemFromReleaseList(Request $request)
    {
        $isProductReleaseInfoAvailable = ProductReleaseInfo::find($request->productReleaseId);
        if($isProductReleaseInfoAvailable){
            $projects= Project::all();
            $divisions=division::all();
            $serialInfo=SerialInfo::all();
            $productReleaseInfo= ProductReleaseInfo::all();
            $project=Project::find($isProductReleaseInfoAvailable->project_id);
            $allAssignedEmployees=[];
            if($project){
               $allAssignedEmployees=$this->getAllAssignedEmployees($project);
            }

            return view('product distribution.ajaxEditProductRelease')
                ->with('divisions', $divisions)
                ->with('projects', $projects)
                ->with('serialInfo', $serialInfo)
                ->with('productReleaseInfo', $productReleaseInfo)
               ->with('assignedEmployees', $allAssignedEmployees)
                ->with('availableProductReleaseInfo', $isProductReleaseInfoAvailable);
        }
    }
    public function updateItemFromReleaseList(Request $request)
    {
        
            // dd($request->all());
        $validator = Validator::make($request->all(),[
          'serialNo'=>['required',Rule::unique('product_release_infos','serial_info_id')->ignore($request->productReleaseInfoId)],
          'releaseDate'=>'required | date_format:d/m/Y| after_or_equal: today',
          'deptName'=>'required',
          'projectName'=>'required',
          'employeeName'=>'required'
        ]);
        
        if ($validator->fails()) {      
            return ["error",$validator->errors()];
        }
        $isProductReleaseInfoAvailable = ProductReleaseInfo::find($request->productReleaseInfoId);
        $this->saveContent($request, $isProductReleaseInfoAvailable);

        return ["success"];

    }

    public function showEmployeeBasedOnProject(Request $request)
    {
        $project=Project::find($request->projectId);

        if($project){
             $allAssignedEmployees=$this->getAllAssignedEmployees($project);
             return ["success",$allAssignedEmployees];
        }
        else{
             return ["error"];
        }
    }
    public function clearProductRelease()
    {
        if(session()->has('user')){
            $productReleaseInfo=ProductReleaseInfo::where('status','pending')->delete();
            return($productReleaseInfo);
            // if (count($productReleaseInfo)>0) {
            //     foreach ($productReleaseInfo as $key => $item) {
            //         $item->status="released";
            //         $item->save();
            //     }
            // }
        }
    }
    public function saveProductRelease()
    {
        if(session()->has('user')){
            $productReleaseInfo=ProductReleaseInfo::all();
            $k=0;
            //$productReleaseInfo=ProductReleaseInfo::where('status','pending')->get();
           
                foreach ($productReleaseInfo as $key => $item) {
                    $item->status="released";
                    $item->save();
                }
                return "success";
           
        }
        return "error";
    }

    public function deleteItemFromReleaseList(Request $request)
    {
        if ($request->ajax()) {
            $isAvailable= ProductReleaseInfo::find($request->id);
            $isDelete=false;
            if ($isAvailable) {
                $isDelete= $isAvailable->delete();
            }
            return $isDelete ? 'deleted' : 'error';
        }
    }
}
