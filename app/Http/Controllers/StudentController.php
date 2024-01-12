<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\CompanyToken;
use Carbon\Carbon;
use App\Jobs\ProcessBulkStudentRegistration;


class StudentController extends Controller
{
    function RegisterStudent(Request $req){

        $response = $this->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $s = new Student();

        if($req->hasFile("ProfilePic")){
            $s->ProfilePic = $req->file("ProfilePic")->store("","public");
        }

        $s->save();


$s->StudentId = strval(10000 + $s->id);


        
       
        

        if($req->filled("CompanyId")){
            $s->CompanyId = $req->CompanyId;
        }

        if($req->filled("FirstName")){
            $s->FirstName  = $req ->FirstName  ;
        }

        if($req->filled("OtherName")){
            $s->OtherName  = $req ->OtherName  ;
        }

        if($req->filled("LastName")){
            $s->LastName  = $req ->LastName  ;
        }

        if($req->filled("DateOfBirth")){
            $s->DateOfBirth  = $req ->DateOfBirth  ;
        }

        if($req->filled("Gender")){
            $s->Gender  = $req ->Gender  ;
        }

        if($req->filled("HomeTown")){
            $s->HomeTown  = $req ->HomeTown  ;
        }

        if($req->filled("Location")){
            $s->Location  = $req ->Location  ;
        }

        if($req->filled("Country")){
            $s->Country  = $req ->Country  ;
        }

        if($req->filled("FathersName")){
            $s->FathersName  = $req ->FathersName  ;
        }

        if($req->filled("FatherOccupation")){
            $s->FatherOccupation  = $req ->FatherOccupation  ;
        }

        if($req->filled("MothersName")){
            $s->MothersName  = $req ->MothersName  ;
        }

        if($req->filled("MotherOccupation")){
            $s->MotherOccupation  = $req ->MotherOccupation  ;
        }

        if($req->filled("GuardianName")){
            $s->GuardianName  = $req ->GuardianName  ;
        }

        if($req->filled("GuardianOccupation")){
            $s->GuardianOccupation  = $req ->GuardianOccupation  ;
        }

        if($req->filled("ParentLocation")){
            $s->ParentLocation  = $req ->ParentLocation  ;
        }

        if($req->filled("ParentDigitalAddress")){
            $s->ParentDigitalAddress  = $req ->ParentDigitalAddress  ;
        }

        if($req->filled("ParentReligion")){
            $s->ParentReligion  = $req ->ParentReligion  ;
        }

        if($req->filled("ParentEmail")){
            $s->ParentEmail  = $req ->ParentEmail  ;
        }

        if($req->filled("EmergencyContactName")){
            $s->EmergencyContactName  = $req ->EmergencyContactName  ;
        }

        if($req->filled("EmergencyPhoneNumber")){
            $s->EmergencyPhoneNumber  = $req ->EmergencyPhoneNumber  ;
        }

        if($req->filled("EmergencyAlternatePhoneNumber")){
            $s->EmergencyAlternatePhoneNumber  = $req ->EmergencyAlternatePhoneNumber  ;
        }

        if($req->filled("RelationshipWithChild")){
            $s->RelationshipWithChild = $req->RelationshipWithChild  ;
        }

        if($req->filled("ParentPhoneNumber")){
            $s->ParentPhoneNumber = $req->ParentPhoneNumber  ;
        }

        if($req->filled("Religion")){
            $s->Religion = $req->Religion  ;
        }

        if($req->filled("Email")){
            $s->Email = $req->Email  ;
        }

        if($req->filled("PhoneNumber")){
            $s->PhoneNumber = $req->PhoneNumber  ;
        }

        if($req->filled("AlternatePhoneNumber")){
            $s->AlternatePhoneNumber = $req->AlternatePhoneNumber  ;
        }

        if($req->filled("MedicalIInformation")){
            $s->MedicalIInformation = $req->MedicalIInformation  ;
        }

        if($req->filled("Level")){
            $s-> Level= $req-> Level ;
        }

        if($req->filled("AdmissionDate")){
            $s->AdmissionDate = $req->AdmissionDate  ;
        }

        if($req->filled("TheAcademicYear")){
            $s->TheAcademicYear = $req->TheAcademicYear  ;
        }

        if($req->filled("TheAcademicTerm")){
            $s->TheAcademicTerm = $req->TheAcademicTerm  ;
        }

       
            $s->Role = "Student" ;
        

       $saver = $s->save();

       if($saver){

        return response()->json(["message" => "Student Admitted Successfully"],200);
       }
       else{
        return response()->json(["message" => "Student Admission Failed"],400);

       }
       


    }


    public function BulkRegisterStudent(Request $req)
    {
        $response = $this->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        if ($req->hasFile('excel_file')) {
            $file = $req->file('excel_file');
            $filePath = $file->getPathname();
    
            
               
            // Dispatch the job to process the bulk student registration
            ProcessBulkStudentRegistration::dispatch($filePath, $req->CompanyId);
            return response()->json(["message" => "File uploaded successfully. Students will be processed in the background."], 200);

        }
    
        return response()->json(["message" => "No file uploaded."], 400);
    }
    









    function PrepaidMeter($CompanyId){
        $c = CompanyToken::where('CompanyId', $CompanyId)->latest()->first();

        if(!$c){
            return response()->json(["message"=>"No products subscribed"],400);
        }

        $systemDate = Carbon::now();

        if($systemDate < $c->CurrentDate){
            return response()->json(["message"=>"Verify that your server is configured with the accurate date and time settings."],400);
        }
        else{
            $c->CurrentDate = $systemDate;
            $c->save();
        }

        if($systemDate > $c->ExpireDate){
            return response()->json(["message"=>"Your subscription has expired. To continue enjoying our service, please renew your subscription. For further details, please reach out to business@hydottech.com."],400);

        }







    }

    function IdGenerator(): string {
        $randomID = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        return $randomID;
        }
}
