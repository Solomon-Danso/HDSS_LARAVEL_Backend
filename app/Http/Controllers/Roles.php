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

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "CreateRoles");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
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

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteRoles");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
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

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AssignRolesToUsers");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $PrimaryRoleList = PrimaryRole::where("RoleName",$req->RoleName)->pluck('RoleFunction');

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


    function CreateAnotherUserDetailedRole(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AddNewRolesToUsers");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

       
        
        if($req->RoleFunction==="SuperAdmin"){
            $checker = UserDetailedRole::where("RoleFunction",$req->RoleFunction)->first();
            if($checker){
                return response()->json(["message"=>"SuperAdmin already assigned to a user"],400);
            }
                
        }
        
        $s = new UserDetailedRole();
        if($req->filled("UserId")){
            $s->UserId = $req->UserId;
        }

        if($req->filled("RoleFunction")){
            $s->RoleFunction = $req->RoleFunction;
        }

        $saver = $s->save();
        if ($saver){
            return response()->json(["message"=>$s->RoleFunction." assigned to this user"],200);
        }
        else{
            return response()->json(["message"=>"Could not assign ".$s->RoleFunction." to this user"],200);

        }



    }





    function ViewUserDetailedRole(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "ViewUserRoles");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }


        $RoleFunctionList = UserDetailedRole::where("UserId",$req->UserId)->get();

        return response()->json(["message"=>$RoleFunctionList],200);
    }

    function DeleteUserDetailedRole(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteUserRole");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }


        $s = UserDetailedRole::where("id",$req->id)->first();

        if($s->RoleFunction==="SuperAdmin"){
            return response()->json(["message"=>"You cannot delete a SuperAdmin account"],400);
        }


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





}
