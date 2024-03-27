<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Admin;
use App\Models\AuditTrail;
use Carbon\Carbon;
use App\Models\CompanyToken;
use App\Models\Authentic;
use App\Models\Notifier;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use App\Models\UserDetailedRole;
use App\Models\StaffMembers;

class AuditTrialController extends Controller
{
   
    function StudentAudit($StudentId,$CompanyId, $Action) {
        $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address
    
        try{
            $ipDetails = json_decode(file_get_contents("https://ipinfo.io/{$ipAddress}/json"));
    
        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $latitude = $ipDetails->loc ?? ''; // Latitude
    
        }catch(\Exception $e){
            $country = $city = $latitude = null;
        }
        
        // Get user agent information
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
        // Parse the user agent string to determine device and OS
        $device = $this->detectDevice($userAgent);
        $os =  $this->detectOperatingSystem($userAgent);
    
        // Current date and time
     
    
        // URL path
        $urlPath = $_SERVER['REQUEST_URI'];

        $stu = Student::where('StudentId', $StudentId)->where("CompanyId", $CompanyId)->first();
        if($stu==null){
            return response()->json(["message"=>"Student does not exist"],400);
        }
    
       
       
        $googleMapsLink = "https://maps.google.com/?q={$latitude}";
    
        // Create a new AuditTrail instance and save the log to the database
        $auditTrail = new AuditTrail();
        $auditTrail->ipAddress = $ipAddress??" ";
        $auditTrail->country = $country??" ";
        $auditTrail->city = $city??" ";
        $auditTrail->device = $device??" ";
        $auditTrail->os = $os??" ";
        $auditTrail->urlPath = $urlPath??" ";
        $auditTrail->action = $Action??" "; 
        $auditTrail->googlemap = $googleMapsLink??" ";
        $auditTrail -> userId = $stu->StudentId??" ";
        $auditTrail -> userName = $stu->FirstName." ".$stu->OtherName." ".$stu->LastName??" ";
        $auditTrail -> userPic = $stu->ProfilePic??" ";
        $auditTrail -> companyId = $stu->CompanyId??" ";

        
        $auditTrail->save();
    }

    function StaffMemberAudit($StaffId,$CompanyId,$Action) {
        $ipAddress = $_SERVER['REMOTE_ADDR']; // Get user's IP address
    
        try{
            $ipDetails = json_decode(file_get_contents("https://ipinfo.io/{$ipAddress}/json"));
    
        $country = $ipDetails->country ?? 'Unknown';
        $city = $ipDetails->city ?? 'Unknown';
        $latitude = $ipDetails->loc ?? ''; // Latitude
    
        }catch(\Exception $e){
            $country = $city = $latitude = null;
        }
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $device = $this->detectDevice($userAgent);
        $os =  $this->detectOperatingSystem($userAgent);
        
        $stu = StaffMembers::where('StaffId', $StaffId)->where("CompanyId", $CompanyId)->first();
        if($stu==null){
            return response()->json(["message"=>"Staff does not exist"],400);
        }


        $urlPath = $_SERVER['REQUEST_URI'];
    
       
       
        $googleMapsLink = "https://maps.google.com/?q={$latitude}";
    
        // Create a new AuditTrail instance and save the log to the database
        $auditTrail = new AuditTrail();
        $auditTrail->ipAddress = $ipAddress??" ";
        $auditTrail->country = $country??" ";
        $auditTrail->city = $city??" ";
        $auditTrail->device = $device??" ";
        $auditTrail->os = $os??" ";
        $auditTrail->urlPath = $urlPath??" ";
        $auditTrail->action = $Action??" "; 
        $auditTrail->googlemap = $googleMapsLink??" ";
        $auditTrail -> userId = $stu->StaffId??" ";
        $auditTrail -> userName = $stu->FirstName." ".$stu->OtherName." ".$stu->LastName??" ";
        $auditTrail -> userPic = $stu->ProfilePic??" ";
        $auditTrail -> companyId = $stu->CompanyId??" ";
        
        $auditTrail->save();
    }

 

    function RoleAuthenticator($SenderId, $RoleFunction){

        $RoleFunctionList = UserDetailedRole::where("UserId",$SenderId)->pluck('RoleFunction');
    
        // Check if the RoleFunctionList is empty
        if($RoleFunctionList->isEmpty()) {
            return response()->json(["message"=>"User does not have any roles assigned"],400);
        }
    
        // Flag to track if SuperAdmin role is found
        $isSuperAdmin = false;
    
        foreach($RoleFunctionList as $Role){
            if($Role === "SuperAdmin"){
                // If the user is SuperAdmin, set the flag to true and break the loop
                $isSuperAdmin = true;
                break;
            }
        }
    
        // If the user is not SuperAdmin and the specified role does not match any of the user's roles
        if (!$isSuperAdmin && !$RoleFunctionList->contains($RoleFunction)) {
            return response()->json(["message"=>"User not authorised to perform this task"],400);
        }
    
        }
    
    



    
    

    function LMSRoleAuthenticator($SenderId, $RoleFunction){

        $RoleFunctionList = UserDetailedRole::where("UserId",$SenderId)->pluck('RoleFunction');
    
        // Check if the RoleFunctionList is empty
        $isStudent = false;

        if($RoleFunctionList->isEmpty()) {
            $student = Student::where("StudentId",$SenderId)->first();
            if($student!==null){
                $isStudent = true;   
            }
            else{
                return response()->json(["message"=>"User does not have any roles assigned"],400);
            }
        }
    
        // Flag to track if SuperAdmin role is found
        $isSuperAdmin = false;
    
        foreach($RoleFunctionList as $Role){
            if($Role === "SuperAdmin"){
                // If the user is SuperAdmin, set the flag to true and break the loop
                $isSuperAdmin = true;
                break;
            }
        }
    
        // If the user is not SuperAdmin and the specified role does not match any of the user's roles
        if (!$isStudent || (!$isSuperAdmin && !$RoleFunctionList->contains($RoleFunction)) ) {
            return response()->json(["message"=>"User not authorised to perform this task"],400);
        }
    
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




    
    // Function to detect device type from User-Agent string
    function detectDevice($userAgent) {
        $isMobile = false;
        $mobileKeywords = ['Android', 'webOS', 'iPhone', 'iPad', 'iPod', 'BlackBerry', 'Windows Phone'];
    
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $isMobile = true;
                break;
            }
        }
    
        return $isMobile ? 'Mobile' : 'Desktop';
    }
    
    // Function to detect operating system from User-Agent string
    function detectOperatingSystem($userAgent) {
        $os = 'Unknown';
    
        $osKeywords = ['Windows', 'Linux', 'Macintosh', 'iOS', 'Android'];
    
        foreach ($osKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                $os = $keyword;
                break;
            }
        }
    
        return $os;
    }

//For forgotten password, the user will have to contact admin. the admin will run the function again to regenerate a new password for the users 
    function Authenticator($UserId, $ProfilePic, $Role, $UserName, $Password,$CompanyId,$AccountType){

        $s = new Authentic();

        $s->UserId = $UserId;
        $s-> ProfilePic = $ProfilePic;
        $s->Role = $Role;
        $s->UserName = $UserName;
         $s->IsPasswordReset = true;
        $s->CompanyId = $CompanyId;
        $s->Password = bcrypt($Password);
        $s->AccountType = $AccountType;

        $s->save();


    }

    function Notify($To, $From, $Subject, $Section, $Content, $CompanyId){
        $s = new Notifier();

        $s->To = $To;
        $s->From = $From;
        $s->Subject = $Subject;
        $s->Section = $Section;
        $s->Content = $Content;
        $s->CompanyId = $CompanyId;

        $s->save();

    }








 }