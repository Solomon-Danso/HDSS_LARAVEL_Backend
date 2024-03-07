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


        //This will define the role names and their functions 
        //RoleName from a drop down list, Role Function from a drop down list 




        
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


    function CreateUserDetailedRole($CompanyId, $SenderId, $UserId, $RoleName){
        $response = $this->audit->PrepaidMeter($CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($SenderId, "AssignRolesToUsers");

        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $PrimaryRoleList = PrimaryRole::where("RoleName",$RoleName)->pluck('RoleFunction');

        if ($PrimaryRoleList->isEmpty()) {
            return response()->json(["message" => "No role function found for this role"], 400);
        }

        foreach($RoleFunction as $PrimaryRoleList){
            $s = new UserDetailedRole();
            $s->UserId = $UserId;
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

        if($s==null){
            return response()->json(["message"=>"User Roles Does not exist"],400);
        }

        if($s->RoleFunction==="SuperAdmin"){
            return response()->json(["message"=>"You cannot delete a SuperAdmin account"],400);
        }

        


        
            $saver = $s->delete();
            if($saver){
                return response()->json(["message"=>"Deleted Successfully"],200);
            }
            else{
                return response()->json(["message"=>"Deletion was not successful"],400);
            }

        
    }

    function CreateUserSummaryRole(Request $req){

        $s = new UserSummarisedRole;

        $response = $this->audit->PrepaidMeter($req->CompanyId);
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "CreateUserSummaryRole");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        if($req->filled("UserId")){
            $s->UserId = $req->UserId;
        }

        if($req->filled("RoleName")){
            $s->RoleName = $req->RoleName;
        }
        $UserS = UserSummarisedRole::where("UserId", $req->UserId)->first();
        if($UserS){
            return response()->json(["message"=>"User already has an assigned role, consider deleting the existing role or edit the user role"],400);
        }

      $UserDetails = CreateUserDetailedRole($req->CompanyId, $req->SenderId, $s->UserId, $s->RoleName);
      if ($UserDetails !== null && $UserDetails->getStatusCode() !== 200) {
       return $UserDetails;
    }

    $saver = $s->save();
    if ($saver){
        return response()->json(["message"=>"User assigned to role successfully"],200);
    }
    else{
        return response()->json(["message"=>"An error occured whiles assigning this role to the user "],400);
    }
        

    }


    function DeleteUserSummaryRole(Request $req){


        $response = $this->audit->PrepaidMeter($req->CompanyId);
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteUserSummaryRole");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $UserS = UserSummarisedRole::where("UserId", $req->UserId)->first();
        if($UserS==null){
            return response()->json(["message" => "User Role Does Not Exist"], 400);  
        }

        $PrimaryRoleList = PrimaryRole::where("RoleName",$UserS->RoleName)->pluck('RoleFunction');

        if ($PrimaryRoleList->isEmpty()) {
            return response()->json(["message" => "No role function found for this role"], 400);
        }

        $UserD = UserDetailedRole::where("UserId",$UserS->UserId)->get();
        if ($UserD->isEmpty()) {
            return response()->json(["message" => "No role function found for this user"], 400);
        }


        foreach ($UserD as $userDetail) {
            if ($PrimaryRoleList->contains($userDetail->RoleFunction)) {
                $userDetail->delete();
            }
        }
    
        // Optionally, you can delete the UserS record as well after deleting related UserD records
        $UserS->delete();
    
        return response()->json(["message" => "User roles deleted successfully"], 200);




    }

    function CreateRoleName(Request $req){
     
        $response = $this->audit->PrepaidMeter($req->CompanyId);
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "CreateRoleName");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $s = new RoleNames();

        if($req->filled("Title")){
            $s->Title = strtoupper($req->Title);
        }

        $checker  = RoleNames::where("Title",strtoupper($req->Title))->first();
        if($checker){
            return response()->json(["message"=>"Role already exist"],400);
        }


        $saver = $s->save();
        if($saver){
            return response()->json(["message"=>"Role name created successfully"],200);
        }
        else{
            return response()->json(["message"=>"An error occured whiles creating Role"],400);
 
        }


    }

    function DeleteRoleName(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteRoleName");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $checker  = RoleNames::where("Title",strtoupper($req->Title))->first();
        if($checker==null){
            return response()->json(["message"=>"Role does not exist"],400);
        }

        $s = $checker->delete();

        if($s){
            return response()->json(["message"=>"Role deleted successfully"],200);
        }
        else{
            return response()->json(["message"=>"An error occured whiles deleting the role"],400);
        }


    }




}
