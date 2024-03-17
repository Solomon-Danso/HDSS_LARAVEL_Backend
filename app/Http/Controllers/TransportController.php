<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Roles;
use App\Http\Controllers\AuditTrialController;
use App\Models\Transport;
use App\Models\Student;
use App\Models\TransportAct;
use Carbon\Carbon;
use App\Models\StaffMembers;

class TransportController extends Controller
{
    protected $audit;
    protected $Role;


    public function __construct(AuditTrialController $auditTrialController,Roles $Role)
    {
        $this->audit = $auditTrialController;
        $this->Role = $Role;
    }

    function AddStudentToTransportList(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AddStudentToTransportList");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
        $t = new Transport();

        $s = Student::where("StudentId", $req->StudentId)->where("CompanyId",$req->CompanyId)->first();
        
        if($s==null){
            return response()->json(["message"=>"Student does not exist"],400);
        }

        $t->StudentId = $s->StudentId;
        $t->CompanyId = $s->CompanyId;
        $t->ParentEmail = $s->ParentEmail;
        $t->ProfilePic = $s->ProfilePic;
        $t->FirstName = $s->FirstName;
        $t->OtherName = $s->OtherName;
        $t->LastName = $s->LastName;
        $t->Level = $s->Level;
        $t->ParentName = $s->EmergencyContactName;
        $t->ParentContact = $s->EmergencyPhoneNumber;
        $t->ParentAltContact = $s->EmergencyAlternatePhoneNumber;
        $t->Location = $req->Location;
        $t->TransportFare = $req->TransportFare;

        $checker = Transport::where("StudentId",$t->StudentId)->where("CompanyId",$t->CompanyId)->first();
        if($checker){
            return response()->json(["message"=>"Student already added"],400);
        }

        $saver = $t->save();
        if($saver){
            return response()->json(["message"=>$t->FirstName." ".$t->OtherName." ".$t->LastName." successfully added to the transport list"],200);
        }
        else{
            return response()->json(["message"=>"Could not add student to the transport list"],400);
        }

    }


    function EditStudentInTransportList(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "EditStudentInTransportList");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
      

        $t = Transport::where("StudentId", $req->StudentId)->where("CompanyId",$req->CompanyId)->first();
        
        if($t==null){
            return response()->json(["message"=>"Student does not exist"],400);
        }
        if($req->filled("Location")){
            $t->Location = $req->Location;
        }

        if($req->filled("TransportFare")){
            $t->TransportFare = $req->TransportFare;
        }

       
        

        $saver = $t->save();
        if($saver){
            return response()->json(["message"=>$t->FirstName." ".$t->OtherName." ".$t->LastName." successfully updated"],200);
        }
        else{
            return response()->json(["message"=>"Could not add student to the transport list"],400);
        }

    }

    function GetAllTransportUsers(Request $req){
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "GetAllTransportUsers");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $t = Transport::where("CompanyId",$req->CompanyId)->get();

        return $t;
        

    }

    function DeleteStudentInTransportList(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DeleteStudentInTransportList");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
        
      

        $t = Transport::where("StudentId", $req->StudentId)->where("CompanyId",$req->CompanyId)->first();
        
        if($t==null){
            return response()->json(["message"=>"Student does not exist"],400);
        }

       
        $saver = $t->delete();
        if($saver){
            return response()->json(["message"=>$t->FirstName." ".$t->OtherName." ".$t->LastName." successfully deleted"],200);
        }
        else{
            return response()->json(["message"=>"Could not delete student from transport list"],400);
        }

    }

    function StudentToPickupList(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "StudentToPickupList");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
    
        $TransList = Transport::where("CompanyId",$req->CompanyId)->get();
        $TransActList = TransportAct::where("CompanyId",$req->CompanyId)->where("PickupTime",Carbon::today())->get();
    
        if ($TransActList->isEmpty()) {
            return $TransList;
            }

        $studentsToPickup = collect();
    
        foreach ($TransList as $student) {
            if (!$TransActList->contains('StudentId', $student->StudentId)) {
                $studentsToPickup->push($student);
            }
        }
    
        return $studentsToPickup;
    }

    function PickUp(Request $req){
       
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "Pickup");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $p = Transport::where("CompanyId",$req->CompanyId)->where("StudentId",$req->StudentId)->first();
        if($p==null){
            return response()->json(["message"=>"Student not found"],400);
        }

        $c = StaffMembers::where("CompanyId",$req->CompanyId)->where("StaffId",$req->SenderId)->first();
        if($c==null){
            return response()->json(["message"=>"Staff not found"],400);
        }



        $t = new TransportAct();

        $t->CompanyId = $p->CompanyId;
        $t->StudentId = $p->StudentId;
        $t->StudentPic = $p->ProfilePic;
        $t->StudentName = $p->FirstName." ".$p->OtherName." ".$p->LastName;
        $t->StudentLevel = $p->Level;
        $t->ParentName = $p->ParentName;
        $t->ParentContact = $p->ParentContact;
        $t->ParentAltContact = $p->ParentAltContact;
        $t->ParentEmail = $p->ParentEmail;
        $t->TransportFare = $p->TransportFare;
        $t->Pickup = true;
        $t->PickupTime = Carbon::today();
        $t->ExactPickupDate = Carbon::now();
        $t->PickupLatitude = $req->PickupLatitude;
        $t->PickupLongtitude = $req->PickupLongtitude;
        $t->PickupLocationUrl = "https://maps.google.com/maps?q={$t->PickupLatitude},{$t->PickupLongtitude}";
        $t->PickupConductorId = $c->StaffId;
        $t->PickupConductorName = $c->FirstName." ".$c->OtherName." ".$c->LastName;
        $t->PickupConductorPic = $c->ProfilePic;
        
        $saver = $t->save();
        if($saver){
            return response()->json(["message"=>$t->StudentName." onboarding successfull"],200);
        }
        else{
            return response()->json(["message"=>$t->StudentName." onboarding failed"],400);
        }


    }

    function StudentToDepartureList(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "StudentToDepartureList");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $studentToDeList = TransportAct::where("Pickup",true)
        ->where("PickupTime",Carbon::today())
        ->where("CompanyId",$req->CompanyId)
        ->where("Departure",false)->get();

        return $studentToDeList;

    }

    function Departure(Request $req){
       
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "Departure");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }


        $c = StaffMembers::where("CompanyId",$req->CompanyId)->where("StaffId",$req->SenderId)->first();
        if($c==null){
            return response()->json(["message"=>"Staff not found"],400);
        }



        $t = TransportAct::where("StudentId",$req->StudentId)
        ->where("CompanyId",$req->CompanyId)
        ->where("Pickup",true)
        ->where("PickupTime",Carbon::today())
        ->first();
        if($t==null){
            return response()->json(["message"=>"Student not found"],400);
        }

        $t->Departure = true;
        $t->DepartureTime = Carbon::today();
        $t->DepartureLatitude = $req->DepartureLatitude;
        $t->DepartureLongtitude = $req->DepartureLongtitude;
        $t->DepartureLocationUrl = "https://maps.google.com/?q={$t->DepartureLatitude},{$t->DepartureLongtitude}";
        $t->DepartureConductorId = $c->StaffId;
        $t->DepartureConductorName = $c->FirstName." ".$c->OtherName." ".$c->LastName;
        $t->DepartureConductorPic = $c->ProfilePic;
        $t->ExactDepartureDate = Carbon::now();
        
        $saver = $t->save();
        if($saver){
            return response()->json(["message"=>$t->StudentName." onboarding successfull"],200);
        }
        else{
            return response()->json(["message"=>$t->StudentName." onboarding failed"],400);
        }


    }

    function StudentToDestinationArrival(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "StudentToDestinationArrival");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $studentToDeList = TransportAct::where("Departure",true)
        ->where("DepartureTime",Carbon::today())
        ->where("DestinationArrival",false)
        ->where("CompanyId",$req->CompanyId)
        ->get();

        return $studentToDeList;

    }

    function DestinationArrival(Request $req){
       
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "DestinationArrival");
        
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

      
        $c = StaffMembers::where("CompanyId",$req->CompanyId)->where("StaffId",$req->SenderId)->first();
        if($c==null){
            return response()->json(["message"=>"Staff not found"],400);
        }


        $t = TransportAct::where("StudentId",$req->StudentId)
        ->where("CompanyId",$req->CompanyId)
        ->where("Pickup",true)
        ->where("Departure",true)
        ->where("DestinationArrival",false)
        ->where("DepartureTime",Carbon::today())
        ->first();
        if($t==null){
            return response()->json(["message"=>"Student not found"],400);
        }

        $t->DestinationArrival = true;
        $t->DestinationArrivalTime = Carbon::today();
        $t->DestinationArrivalLatitude = $req->DestinationArrivalLatitude;
        $t->DestinationArrivalLongtitude = $req->DestinationArrivalLongtitude;
        $t->DestinationArrivalLocationUrl = "https://maps.google.com/?q={$t->DestinationArrivalLatitude},{$t->DestinationArrivalLongtitude}";
        $t->DestinationArrivalConductorId = $c->StaffId;
        $t->DestinationArrivalConductorName = $c->FirstName." ".$c->OtherName." ".$c->LastName;
        $t->DestinationArrivalConductorPic = $c->ProfilePic;
        $t->ExactDestinationDate = Carbon::now();
        
        $saver = $t->save();
        if($saver){
            return response()->json(["message"=>$t->StudentName." onboarding successfull"],200);
        }
        else{
            return response()->json(["message"=>$t->StudentName." onboarding failed"],400);
        }


    }




}
