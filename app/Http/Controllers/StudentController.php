<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    function RegisterStudent(Request $req){

        $s = new Student();

        if($req->hasFile("ProfilePic")){
            $s->ProfilePic = $req->file("ProfilePic")->store("","public");
        }

        if($req->filled("StudentId")){
            $s->StudentId = strval(10,000+$s->id);
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

       


    }









    function IdGenerator(): string {
        $randomID = str_pad(mt_rand(1, 99999999), 8, '0', STR_PAD_LEFT);
        return $randomID;
        }
}
