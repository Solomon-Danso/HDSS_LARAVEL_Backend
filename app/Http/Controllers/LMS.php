<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AuditTrialController;
use App\Http\Controllers\Roles;
use App\Models\VideoCall;
use App\Models\StaffMembers;
use App\Models\Subject;
use Carbon\Carbon;
use App\Models\Classes;
use App\Models\Student;
use App\Models\TeacherInSubject;





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

        $UserRole = $this->audit->LMSRoleAuthenticator($req->SenderId, "ViewAllVideoCall");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }



        $v = VideoCall::where("CompanyId",$req->CompanyId)
                        ->where("Level",$req->Level)
                        ->where("Subject",$req->Subject)
                        ->where("StartDate",">","=",Carbon::now())
                        ->orderBy("StartDate")
                        ->get();

       
        
        
            $message = "Viewed All ".$v->Subject." video calls for ".$v->Level." ";
           $Dual = $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);
     
           if ($Dual !== null && $Dual->getStatusCode() !== 200) {
            $this->audit->StudentAudit($req->SenderId,$req->CompanyId,$message);
            }


            return $v;
       



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

    function AddSubject(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AddSubject");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = new Subject();

        if($req->filled("SubjectName")){
            $s->SubjectName = $req->SubjectName;
        }

       
        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

        $saver = $s->save();
        if($saver){
            $message = "Added ".$s->SubjectName." to subject list ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Subject added successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not add a subject"],400);
        }




    }

    function UpdateSubject(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "UpdateSubject");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = Subject::where("id", $req->Id)->where("CompanyId", $req->CompanyId)->first();

        if($s==null){
            return response()->json(["message"=>"Subject not found"],400);
        }

        if($req->filled("SubjectName")){
            $s->SubjectName = $req->SubjectName;
        }

       
        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

        $saver = $s->save();
        if($saver){
            $message = "Updated ".$s->SubjectName." in subject list ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Subject updated successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not update the subject"],400);
        }




    }

    function ViewSubject(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->LMSRoleAuthenticator($req->SenderId, "ViewSubject");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = Subject::where("CompanyId", $req->CompanyId)->get();

       
       
            $message = "Viewed subject list ";
            $Dual = $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);
     
            if ($Dual !== null && $Dual->getStatusCode() !== 200) {
             $this->audit->StudentAudit($req->SenderId,$req->CompanyId,$message);
             }
       
            return $s;
        




    }

    function DeleteSubject(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteSubject");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = Subject::where("id", $req->Id)->where("CompanyId", $req->CompanyId)->first();

        if($s==null){
            return response()->json(["message"=>"Subject not found"],400);
        }

        
        $saver = $s->delete();
        if($saver){
            $message = "Deleted ".$s->SubjectName." in subject list ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Subject deleted successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not delete the subject"],400);
        }




    }


    function AddLevel(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AddLevel");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = new Classes();

        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

       
        if($req->filled("ClassName")){
            $s->ClassName = $req->ClassName;
        }

        if($req->filled("ClassCode")){
            $s->ClassCode = $req->ClassCode;
        }

        if($req->filled("Campus")){
            $s->Campus = $req->Campus;
        }

        if($req->filled("TeacherId")){
            $s->TeacherId = $req->TeacherId;
        }

        $t = StaffMembers::where("CompanyId",$req->CompanyId)->where("StaffId",$req->TeacherId)->first();
        if($t==null){
            return response()->json(["message"=>"Tutor not found"],400);
        }


       
            $s->ClassTeacher = $t->FirstName." ".$t->OtherName." ".$t->LastName;
        




        $saver = $s->save();
        if($saver){
            $message = "Added ".$s->ClassName." to School Stages ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Level added successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not add Level"],400);
        }




    }

    function UpdateLevel(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "UpdateLevel");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = Classes::where("id",$req->Id)->first();
        if($s==null){
            return response()->json(["message","Stage not found"],400);
        }

        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

       
        if($req->filled("ClassName")){
            $s->ClassName = $req->ClassName;
        }

        if($req->filled("ClassCode")){
            $s->ClassCode = $req->ClassCode;
        }

        if($req->filled("Campus")){
            $s->Campus = $req->Campus;
        }

        if($req->filled("TeacherId")){
            $s->TeacherId = $req->TeacherId;
        }

        $t = StaffMembers::where("CompanyId",$req->CompanyId)->where("StaffId",$req->TeacherId)->first();
        if($t==null){
            return response()->json(["message"=>"Tutor not found"],400);
        }


       
            $s->ClassTeacher = $t->FirstName." ".$t->OtherName." ".$t->LastName;
        




        $saver = $s->save();
        if($saver){
            $message = "Updated ".$s->ClassName." in School Stages ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Level updated successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not add Level"],400);
        }




    }

    function ViewLevel(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "ViewLevel");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = Classes::where("CompanyId",$req->CompanyId)->get();
        $message = "Viewed all School Stages ";
        $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return $s;
       




    }

    function DeleteLevel(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteLevel");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        

        $s = Classes::where("id",$req->Id)->first();
        if($s==null){
            return response()->json(["message","Stage not found"],400);
        }

        
        $saver = $s->delete();
        if($saver){
            $message = "Deleted ".$s->ClassName." in School Stages ";
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>"Level deleted successfully"],200);
        }
        else{
            return response()->json(["message"=>"Could not delete Level"],400);
        }




    }

    function CountStudentInClass(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "CountStudentInClass");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $counter = Student::where("CompanyId", $req->CompanyId)->where("Level",$req->Level)->count();

        $message = "Counted Student in".$req->Level;
        $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

        return $counter;








    }

    function AssignTeacherToClass(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AssignTeacherToClass");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $t = StaffMembers::where("CompanyId",$req->CompanyId)->where("StaffId",$req->StaffId)->first();
        if($t==null){
            return response()->json(["message"=>"Tutor not found"],400);
        }

        $c = Classes::where("CompanyId",$req->CompanyId)->where("ClassName",$req->ClassName)->first();
        if($c==null){
            return response()->json(["message"=>"Tutor not found"],400);
        }

        $s = Subject::where("CompanyId",$req->CompanyId)->where("SubjectName",$req->SubjectName)->first();
        if($s==null){
            return response()->json(["message"=>"Tutor not found"],400);
        }

        $AssignT = new TeacherInSubject();

        $AssignT->CompanyId = $t->CompanyId;
        $AssignT->StaffID = $t->StaffID;
        $AssignT->StaffName = $t->FirstName." ".$t->OtherName." ".$t->LastName;
        $AssignT->ClassName = $c->ClassName;
        $AssignT->SubjectName = $s->SubjectName;

        $saver = $AssignT->save();
        if($saver){
            $message = "Assigned ".$AssignT->SubjectName." for ". $AssignT->ClassName ."to ".$AssignT->StaffName;
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,$message);

            return response()->json(["message"=>$message],200);
        }
        else{
            $message = "Could not Assign ".$AssignT->SubjectName." for ". $AssignT->ClassName ."to ".$AssignT->StaffName;

         return response()->json(["message"=>$message],400);
        }






    }


















}
