<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditTrialController;
use App\Http\Controllers\Roles;
use App\Models\VideoCall;
use App\Models\StaffMembers;
use Carbon\Carbon;

class LMS extends Controller
{
    protected $audit;
    protected $Role;


    public function __construct(AuditTrialController $auditTrialController,Roles $Role)
    {
        $this->audit = $auditTrialController;
        $this->Role = $Role;
    }


    function CreateVideoCall(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "CreateVideoCall");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $t = StaffMembers::where("StaffId", $req->StaffId)->where("CompanyId", $req->CompanyId)->first();
        if($t==null){
            return response()->json(["message"=>"StaffMembers not found"],400);
        }

        $v = new VideoCall();

        if($req->filled("TeacherId")){
            $v->TeacherId = $t->StaffId;
        }

        $v->TeacherName = $t->FirstName." ".$t->OtherName." ".$t->LastName;

        if($req->filled("Level")){
            $v->Level = $req->Level;
        }

        if($req->filled("Subject")){
            $v->Subject = $req->Subject;
        }

        if($req->filled("AcademicYear")){
            $v->AcademicYear = $req->AcademicYear;
        }

        if($req->filled("AcademicTerm")){
            $v->AcademicTerm = $req->AcademicTerm;
        }

        if($req->filled("VideoCallUrl")){
            $v->VideoCallUrl = $req->VideoCallUrl;
        }

        if($req->filled("StartDate")){
            $v->StartDate = $req->StartDate;
        }

        if($req->filled("CompanyId")){
            $v->CompanyId = $req->CompanyId;
        }

        $saver = $v->save();
        if($saver){
            $message = "Scheduled ".$v->Subject." video call for ".$v->Level." ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Video Call sheduled successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not schedule a video call"],400);
        }




    }


    function UpdateVideoCall(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "UpdateVideoCall");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $t = StaffMembers::where("StaffId", $req->StaffId)->where("CompanyId", $req->CompanyId)->first();
        if($t==null){
            return response()->json(["message"=>"StaffMembers not found"],400);
        }

        $v = VideoCall::where("id",$req->Id)->where("CompanyId",$req->CompanyId)->first();
       
        if($v==null){
            return response()->json(["message"=>"Video schedule not found"],400);
        }

        if($req->filled("TeacherId")){
            $v->TeacherId = $t->StaffId;
        }

        $v->TeacherName = $t->FirstName." ".$t->OtherName." ".$t->LastName;

        if($req->filled("Level")){
            $v->Level = $req->Level;
        }

        if($req->filled("Subject")){
            $v->Subject = $req->Subject;
        }

        if($req->filled("AcademicYear")){
            $v->AcademicYear = $req->AcademicYear;
        }

        if($req->filled("AcademicTerm")){
            $v->AcademicTerm = $req->AcademicTerm;
        }

        if($req->filled("VideoCallUrl")){
            $v->VideoCallUrl = $req->VideoCallUrl;
        }

        if($req->filled("StartDate")){
            $v->StartDate = $req->StartDate;
        }

        if($req->filled("CompanyId")){
            $v->CompanyId = $req->CompanyId;
        }

        $saver = $v->save();
        if($saver){
            $message = "Scheduled ".$v->Subject." video call for ".$v->Level." ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Video Call sheduled successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not schedule a video call"],400);
        }




    }

    function ViewAllVideoCall(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }



        $v = VideoCall::where("CompanyId",$req->CompanyId)
                        ->where("Level",$req->Level)
                        ->where("Subject",$req->Subject)
                        ->where("StartDate",">","=",Carbon::now())
                        ->orderBy("StartDate")
                        ->get();

       
        
        
            $message = "Viewed All ".$v->Subject." video calls for ".$v->Level." ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>$v],200);
       



    }

    function DeleteVideoCall(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteVideoCall");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }


        $v = VideoCall::where("id",$req->Id)->where("CompanyId",$req->CompanyId)->first();
       
        if($v==null){
            return response()->json(["message"=>"Video schedule not found"],400);
        }

       
        $saver = $v->delete();
        if($saver){
            $message = "Deleted ".$v->Subject." video call for ".$v->Level." ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Video Call deleted successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not delete a video call"],400);
        }      



    }
















}
