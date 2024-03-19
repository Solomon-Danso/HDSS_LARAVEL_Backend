<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Roles;
use App\Http\Controllers\AuditTrialController;
use App\Models\Student;
use App\Models\StaffMembers;
use App\Models\AddFees;


class FinancialController extends Controller
{
    

    protected $audit;
    protected $Role;


    public function __construct(AuditTrialController $auditTrialController,Roles $Role)
    {
        $this->audit = $auditTrialController;
        $this->Role = $Role;
    }


    function AddFees(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AddFees");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
        $s = new AddFees();

        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

        if($req->filled("Level")){
            $s->Level = $req->Level;
        }

        if($req->filled("Description")){
            $s->Description = $req->Description;
        }

        if($req->filled("Fee")){
            $s->Fee = $req->Fee;
        }

        $sn = StaffMembers::where("CompanyId", $s->CompanyId)->where("StaffId", $req->SenderId)->first();
        
        if($sn==null){
            return response()->json(["message"=>"Staff member not found"],400);
        }

        $s->SenderId = $sn->StaffId;
        $s->SenderName = $sn->FirstName." ".$sn->OtherName." ".$sn->LastName;
        $s->ProfilePic = $sn->ProfilePic;

        $saver = $s->save();
        if($saver){
            $message = "Added ".$s->Fee." as ".$s->Description." for ".$s->Level;
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Fee added successfully"],200);

        }else{
            return response()->json(["message"=>"Fee addition Failed"],400);
        }




    }

    function EditFees(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "EditFees");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
        $s = AddFees::where("CompanyId",$req->CompanyId)->where("id",$req->id)->first();
        if($s==null){
            return response()->json(["message"=>"Fee does not exist"],400);
        }
        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

        if($req->filled("Level")){
            $s->Level = $req->Level;
        }

        if($req->filled("Description")){
            $s->Description = $req->Description;
        }

        if($req->filled("Fee")){
            $s->Fee = $req->Fee;
        }

        $sn = StaffMembers::where("CompanyId", $s->CompanyId)->where("StaffId", $req->SenderId)->first();
        
        if($sn==null){
            return response()->json(["message"=>"Staff member not found"],400);
        }

        $s->SenderId = $sn->StaffId;
        $s->SenderName = $sn->FirstName." ".$sn->OtherName." ".$sn->LastName;
        $s->ProfilePic = $sn->ProfilePic;

        $saver = $s->save();
        if($saver){
            $message = "Editted ".$s->Fee." and ".$s->Description." for ".$s->Level;
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Fee added successfully"],200);

        }else{
            return response()->json(["message"=>"Fee addition Failed"],400);
        }




    }

    function ViewOneFees(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "ViewOneFees");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
        $s = AddFees::where("CompanyId",$req->CompanyId)->where("id",$req->id)->first();
        if($s==null){
            return response()->json(["message"=>"Fee does not exist"],400);
        }

        $message = "Viewed ".$s->Fee." as ".$s->Description." for ".$s->Level;
        $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

        return $s;
        


    }

    function ViewAllFees(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "ViewAllFees");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
        $s = AddFees::where("CompanyId",$req->CompanyId)->get();
        if($s==null){
            return response()->json(["message"=>"Fee does not exist"],400);
        }

        $message = "Viewed all fees list";
        $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

        return $s;
        


    }


    function DeleteFees(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteFees");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
        $s = AddFees::where("CompanyId",$req->CompanyId)->where("id",$req->id)->first();
        if($s==null){
            return response()->json(["message"=>"Fee does not exist"],400);
        }


        $saver = $s->delete();
        if($saver){
            $message = "Deleted ".$s->Fee." as ".$s->Description." for ".$s->Level;
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Fee added successfully"],200);

        }else{
            return response()->json(["message"=>"Fee addition Failed"],400);
        }




    }

    function RunEndOfTerm(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "RunEndOfTerm");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $studentList = Student::where("CompanyId",$req->CompanyId)->where("Level",$req->Level)->get();

        $FeeList = AddFees::where("CompanyId",$req->CompanyId)
                    ->where("AcademicYear",$req->AcademicYear)
                    ->where("AcademicTerm",$req->AcademicTerm)
                    ->where("Level",$req->Level)->get();
        $Amount = AddFees::where("CompanyId",$req->CompanyId)
        ->where("AcademicYear",$req->AcademicYear)
        ->where("AcademicTerm",$req->AcademicTerm)
        ->where("Level",$req->Level)->sum("Fee");

        foreach($studentList as $student){
            $student->TheAcademicTerm = $req->AcademicTerm;
            $student->TheAcademicYear = $req->AcademicYear;
            $student->amountOwing = $student->amountOwing+$Amount;
            $student->save();

        }

                    

        


        

    }



}
