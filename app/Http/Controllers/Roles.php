<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrimaryRole;
use App\Models\RoleNames;
use App\Models\UserDetailedRole;
use App\Models\UserSummarisedRole;
use App\Http\Controllers\AuditTrialController;



class Roles extends Controller
{


    protected $audit;

    public function __construct(AuditTrialController $auditTrialController)
    {
        $this->audit = $auditTrialController;
    }




    function CreatePrimaryRole(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
        
        $s = new PrimaryRole();

        if($req->filled("RoleName")){
            $s->RoleName = $req->RoleName;
        }

        if($req->filled("RoleFunction")){
            $s->RoleFunction = $req->RoleFunction;
        }

        $checker = $s->save();
        if($checker){
            return response()->json(["message"=>"Role created successfully"],200);
        }
        else{
           
            return response()->json(["message"=>"An error occured whiles creating a role"],400); 
        }



    }

    function DeletePrimaryRole(Request $req){
       
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
       
        $s = PrimaryRole::where("id",$req->id)->first();
        if($s !== null){
            $saver = $s->delete();
            if($saver){
                return response()->json(["message"=>"Deleted Successfully"],200);
            }
            else{
                return response()->json(["message"=>"Deletion was not successful"],400);
            }

        }
        
    }

    function CreateUserDetailedRole(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
        $PrimaryRoleList = PrimaryRole::where("RoleName",$req->RoleName)->get();

        foreach($RoleFunction as $PrimaryRoleList){
            $s = new UserDetailedRole();
            $s->UserId = $req->UserId;
            $s->RoleFunction = $RoleFunction;
            $saver = $s->save();
            if($saver){

            }
            else{
                return response()->json(["message"=>"Couldnot assign ".$RoleFunction." to this user"],400);
            }
        }

        return response()->json(["message"=>"User Assigned To Role Successfully"],200);


    }





}
