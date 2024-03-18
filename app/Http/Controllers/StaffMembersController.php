<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffMembers;
use Carbon\Carbon;
use App\Models\Authentic;
use App\Http\Controllers\AuditTrialController;
use App\Http\Controllers\Roles;
use App\Jobs\BulkStaffMemberssRegistration;
use App\Jobs\UploadingAutoGeneratedStaffMembersList;
use App\Exports\StaffMemberssExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Setup;
use PDF;

class StaffMembersController extends Controller
{
  
    protected $audit;
    protected $Role;

    public function __construct(AuditTrialController $auditTrialController,Roles $Role )
    {
        $this->audit = $auditTrialController;
        $this->Role = $Role;
    }

    function RegisterStaffMemberss(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "RegisterStaffMembers");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }



        $t = new StaffMembers();

        if($req->filled("CompanyId")){
            $t->CompanyId = $req->CompanyId;
        }

        $checker  = StaffMembers::where("PhoneNumber", $req->PhoneNumber)->where("CompanyId", $req->CompanyId)->first();
        if ($checker){
            return response()->json(["message"=>"StaffMembers Already Exist"],400);
        }

        $t->save();


        $t->StaffId = strval(10000 + $t->id);


        if($req->hasFile("ProfilePic")){
            $t->ProfilePic = $req->file("ProfilePic")->store("","public");
        }

        if($req->hasFile("Cert1")){
            $t->Cert1 = $req->file("Cert1")->store("","public");
        }

        

        if($req->hasFile("IdCards")){
            $t->IdCards = $req->file("IdCards")->store("","public");
        }

        $t->IsSuspended= false;
       
        
        

        if($req->filled("FirstName")){
            $t->FirstName  = $req ->FirstName  ;
        }

        if($req->filled("OtherName")){
            $t->OtherName  = $req ->OtherName  ;
        }

        if($req->filled("LastName")){
            $t->LastName  = $req ->LastName  ;
        }

        if($req->filled("DateOfBirth")){
            $t->DateOfBirth  = $req ->DateOfBirth  ;
        }

        if($req->filled("Gender")){
            $t->Gender  = $req ->Gender  ;
        }


        if($req->filled("Location")){
            $t->Location  = $req ->Location  ;
        }

        if($req->filled("Country")){
            $t->Country  = $req ->Country  ;
        }

        if($req->filled("MaritalStatus")){
            $t->MaritalStatus  = $req ->MaritalStatus  ;
        }

        if($req->filled("PhoneNumber")){
            $t->PhoneNumber  = $req ->PhoneNumber  ;
        }

        if($req->filled("Email")){
            $t->Email  = $req ->Email  ;
        }

        if($req->filled("Title")){
            $t->Title  = $req ->Title  ;
        }

        if($req->filled("HighestEducationalLevel")){
            $t->HighestEducationalLevel  = $req ->HighestEducationalLevel  ;
        }

        if($req->filled("TeachingExperience")){
            $t->TeachingExperience  = $req ->TeachingExperience  ;
        }

        if($req->filled("TaxNumber")){
            $t->TaxNumber  = $req ->TaxNumber  ;
        }

        if($req->filled("SocialSecurity")){
            $t->SocialSecurity  = $req ->SocialSecurity  ;
        }
        if($req->filled("HealthStatus")){
            $t->HealthStatus  = $req ->HealthStatus  ;
        }
        if($req->filled("EmergencyPerson")){
            $t->EmergencyPerson  = $req ->EmergencyPerson  ;
        }
        if($req->filled("EmergencyPhone1")){
            $t->EmergencyPhone1  = $req ->EmergencyPhone1  ;
        }
        if($req->filled("EmergencyPhone2")){
            $t->EmergencyPhone2  = $req ->EmergencyPhone2  ;
        }

        if($req->filled("PrimaryRole")){
            $t->PrimaryRole  = $req ->PrimaryRole;
        }

        $t->AccountType = "StaffMember";


        $this->Role->CreateUserSummaryRoleOnRegistration($req->CompanyId, $req->SenderId, $t->StaffId, $t->PrimaryRole );
        
       

        $saver = $t->save();
        if($saver){



          
            $UserName = $t->FirstName." ".$t->OtherName." ".$t->LastName;
            $Password =  $this->audit->IdGenerator();
            $TheUserId =   $t->StaffId;
          
            $this->audit->Authenticator(
                $t->StaffId,
                $t->ProfilePic,
                $t->PrimaryRole,
                $UserName,
                $Password,
                $t->CompanyId,
                "StaffMember",
            );

            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,"Register Staff Members");
            $cmp = Setup::where("CompanyId", $t->CompanyId)->first();
            if($cmp==null){
                return response()->json(["message"=>"No company found"],400);
            }
    
            $snd = StaffMembers::where("StaffId", $req->SenderId)->first();
            if($snd==null){
                return response()->json(["message"=>"Sender does not exist"],400);
            }
    
            $CompanyName = $cmp->CompanyName;
            $CompanyLogo = $cmp->CompanyLogo ;
            $Location = $cmp->Location ;
    
            $SenderPosition = $snd->PrimaryRole;
    
        
    
            $TheSenderName = $snd->FirstName." ".$snd->OtherName." ".$snd->LastName;
    
            
    
    
    
            $ProfilePic = $t->ProfilePic; // Get the URL for the profile picture
            $currentDate = date("F j, Y", strtotime("now"));
    
            $pdf = PDF::loadView('AppointmentLetter', compact('Password','TheUserId','SenderPosition','TheSenderName','Location','CompanyLogo','CompanyName','UserName', 'Password', 'ProfilePic','currentDate'));
    
            return $pdf->download( $UserName.'.pdf');
     


              }
        else{
            return response()->json(["message"=>"An error has occured"],400);
        }



    }

    function UpdateStaffMemberss(Request $req){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }


        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "UpdateStaffMembers");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }


        $t = StaffMembers::where("StaffId", $req->StaffId)->where("CompanyId", $req->CompanyId)->first();
        if($t==null){
            return response()->json(["message"=>"Staff Member not found"],400);
        }


        if($req->hasFile("ProfilePic")){
            $t->ProfilePic = $req->file("ProfilePic")->store("","public");
        }

        if($req->hasFile("Cert1")){
            $t->Cert1 = $req->file("Cert1")->store("","public");
        }

        if($req->hasFile("Cert2")){
            $t->Cert2 = $req->file("Cert2")->store("","public");
        }

        if($req->hasFile("IdCards")){
            $t->IdCards = $req->file("IdCards")->store("","public");
        }
       
        
        if($req->filled("Title")){
            $t->Title  = $req ->Title  ;
        }

        if($req->filled("FirstName")){
            $t->FirstName  = $req ->FirstName  ;
        }

        if($req->filled("OtherName")){
            $t->OtherName  = $req ->OtherName  ;
        }

        if($req->filled("LastName")){
            $t->LastName  = $req ->LastName  ;
        }

        if($req->filled("DateOfBirth")){
            $t->DateOfBirth  = $req ->DateOfBirth  ;
        }

        if($req->filled("Gender")){
            $t->Gender  = $req ->Gender  ;
        }

        if($req->filled("HomeTown")){
            $t->HomeTown  = $req ->HomeTown  ;
        }

        if($req->filled("Location")){
            $t->Location  = $req ->Location  ;
        }

        if($req->filled("Country")){
            $t->Country  = $req ->Country  ;
        }

        if($req->filled("MaritalStatus")){
            $t->MaritalStatus  = $req ->MaritalStatus  ;
        }

        if($req->filled("PhoneNumber")){
            $t->PhoneNumber  = $req ->PhoneNumber  ;
        }

        if($req->filled("Email")){
            $t->Email  = $req ->Email  ;
        }

        if($req->filled("HighestEducationalLevel")){
            $t->HighestEducationalLevel  = $req ->HighestEducationalLevel  ;
        }

        if($req->filled("TeachingExperience")){
            $t->TeachingExperience  = $req ->TeachingExperience  ;
        }

        if($req->filled("TaxNumber")){
            $t->TaxNumber  = $req ->TaxNumber  ;
        }

        if($req->filled("SocialSecurity")){
            $t->SocialSecurity  = $req ->SocialSecurity  ;
        }
        if($req->filled("HealthStatus")){
            $t->HealthStatus  = $req ->HealthStatus  ;
        }
        if($req->filled("EmergencyPerson")){
            $t->EmergencyPerson  = $req ->EmergencyPerson  ;
        }
        if($req->filled("EmergencyPhone1")){
            $t->EmergencyPhone1  = $req ->EmergencyPhone1  ;
        }
        if($req->filled("EmergencyPhone2")){
            $t->EmergencyPhone2  = $req ->EmergencyPhone2  ;
        }
        

        $saver = $t->save();
        if($saver){
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,"Update Staff Members");

            return response()->json(["message"=>$t->Title.", ".$t->FirstName." ".$t->OtherName." ".$t->LastName."information has been Updated"],200);
        }
        else{
            return response()->json(["message"=>"An error has occured"],400);
        }

        

    }

    function ViewStaffMembers($StaffId,$CompanyId,$SenderId){
        $response = $this->audit->PrepaidMeter($CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($SenderId, "ViewStaffMembers");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $t = StaffMembers::where("StaffId", $StaffId)->where("CompanyId", $CompanyId)->first();
        if($t==null){
            return response()->json(["message"=>"StaffMembers not found"],400);
        }
        $this->audit->StaffMemberAudit($SenderId,$CompanyId,"View Staff Members");

        return $t;
    }

    function DeleteStaffMembers($StaffId,$CompanyId,$SenderId){
        $response = $this->audit->PrepaidMeter($CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($SenderId, "DeleteStaffMembers");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }

        $t = StaffMembers::where("StaffId", $StaffId)->where("CompanyId", $CompanyId)->first();
        if($t==null){
            return response()->json(["message"=>"StaffMembers not found"],400);
        }

     

        $saver = $t->delete();
        if($saver){
            $this->Role->DeleteDetailedRoleForUser($CompanyId, $SenderId, $StaffId);
            
            $a = Authentic::where("UserId", $StaffId)->where("CompanyId", $CompanyId)->first();
         if($a==null){
            return response()->json(["message"=>"StaffMembers not found"],400);
            }

            $a->delete();

            $this->audit->StaffMemberAudit($SenderId,$CompanyId,"Delete Staff Members");

            
            
            return response()->json(["message"=>"StaffMembers Deleted Successfully"],200);
        }
        else{
            return response()->json(["message"=>"An error has occured"],400);
        }


       
    }


    public function GetStaffMemberssInASchool(Request $req)
    {
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "ViewStaffMembersInSchool");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
    
        $StaffMemberss = StaffMembers::where("CompanyId", $req->CompanyId)
        ->where("PrimaryRole", "!=", "SuperAdmin")
        ->orwhereNull("PrimaryRole")
        ->orderBy('LastName')
        ->get()
        ->toArray();
    

        $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,"View Staff Members In School");

        return $StaffMemberss;
    }

    public function BulkRegisterStaffMembers(Request $req)
    {
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "BulkRegisterStaffMember");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
    
    
        if ($req->hasFile('excel_file')) {
            $file = $req->file('excel_file');
            $filePath = $file->getPathname();
    
            
               
            // Dispatch the job to process the bulk StaffMembers registration
            BulkStaffMemberssRegistration::dispatch($filePath, $req->CompanyId);
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,"Bulk Register Staff Member");

            return response()->json(["message" => "File uploaded successfully. StaffMemberss will be processed in the background."], 200);

        }
    
        return response()->json(["message" => "No file uploaded."], 400);
    }




    public function GetStaffMembersInSchoolFile(Request $req)
    {
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "AutoGenerateStaffMembers");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
    
        $StaffMemberss = StaffMembers::where("CompanyId", $req->CompanyId)
            ->orderBy('LastName')
            ->get();

            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,"Auto Generate Staff Members");

        return Excel::download(new StaffMemberssExport($StaffMemberss), 'EntireStaffMembersList.xlsx');
    }

    public function UploadAutoGeneratedRegisterStaffMembers(Request $req)
    {
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $UserRole = $this->audit->RoleAuthenticator($req->SenderId, "UploadAutoGenerateStaffMembers");
        if ($UserRole !== null && $UserRole->getStatusCode() !== 200) {
            return $UserRole;
        }
    
        if ($req->hasFile('excel_file')) {
            $file = $req->file('excel_file');
            $filePath = $file->getPathname();
    
            
               
            // Dispatch the job to process the bulk student registration
            UploadingAutoGeneratedStaffMembersList::dispatch($filePath, $req->CompanyId);
            $this->audit->StaffMemberAudit($req->SenderId,$req->CompanyId,"Upload Auto Generate Staff Members");


            return response()->json(["message" => "File uploaded successfully. Students will be processed in the background."], 200);

        }
    
        return response()->json(["message" => "No file uploaded."], 400);
    }








}
