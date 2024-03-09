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
        // Get user agent information
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
        // Parse the user agent string to determine device and OS
        $device = $this->detectDevice($userAgent);
        $os =  $this->detectOperatingSystem($userAgent);
    
        $stu = StaffMembers::where('StaffId', $StaffId)->where("CompanyId", $CompanyId)->first();
        if($stu==null){
            return response()->json(["message"=>"Staff does not exist"],400);
        }
      
    
        // URL path
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
    function Authenticator($UserId, $ProfilePic, $Role, $UserName, $Password,$CompanyId){

        $s = new Authentic();

        $s->UserId = $UserId;
        $s-> ProfilePic = $ProfilePic;
        $s->Role = $Role;
        $s->UserName = $UserName;
         $s->IsPasswordReset = true;
        $s->CompanyId = $CompanyId;
        $s->Password = bcrypt($Password);

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








    function generatePDF() {
        // Initialize Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
    
        // Generate URL for student image
       // $studentImageUrl = Storage::url("BHtMigLo4j2OXauaK5YiLRmEOKd7IIkrMjLDFBhj.png");
    
        // Generate HTML content for PDF
        $htmlelement = <<<HTML
        <!DOCTYPE html>
        <html>
        <head>
        <style>
            body {
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
    
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            animation: fade-in 0.5s ease-out;
            
        }
    
        .container img {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
    
        .header {
            font-size: 1.6rem;
            font-weight: bold;
            margin-top: 20px;
        }
    
        .center{
            text-align: center;
        }
    
        .locbold {
            font-size: 15px;
            float: left;
            text-align: center;
        }
    
        .school-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 20px;
        }
    
        .content {
            text-align: justify;
        }
    
        .contentp {
            font-size: 18px;
            margin-bottom: 20px;
            line-height: 1.5;
            font-weight: 900;
        }
    
        .spacer{
            margin-left: 200px;
        }
        .bold{
            font-size: 15px;
            float: left; /* Float the left div to the left */
            text-align: left; /* Align text to the left within the left div */
        }
        .lightbold{
            font-size: 15px;
        }
        .school{
            font-size: 30px;
        }
    
        /* Add individual styles for .left, .center, and .right */
        .left {
            float: left; /* Float the left div to the left */
            width: 30%; /* Adjust the width as needed */
            padding: 10px; /* Add padding for spacing */
            box-sizing: border-box; /* Include padding and border in the width */
            text-align: left; /* Align text to the left within the left div */
        }
    
        .right {
            float: right; /* Float the right div to the left */
            padding: 10px; /* Add padding for spacing */
            box-sizing: border-box; /* Include padding and border in the width */
            text-align: right; /* Align text to the right within the right div */
            margin-left:120px;
            margin-top: -2300rem;
        }
        </style>
        </head>
        <body>
            <div class='container'>
                <div class='center'> 
                
                </div>
                <br/>
                <div>
                    <span class='bold'>Dear ,</span>
                    <span class='spacer lightbold'></span>
                </div>
                <br/>
                <div class='center'> 
                    <div class='school'></div>
                    <div class='locbold'></div>
                </div>
                <hr/>
                <div class='content'>
                    <p class='contentp'>1. We are delighted to extend an offer of admission to <b></b> for this academic year. It brings us great pleasure to welcome you to our school community, and we are excited about the prospect of having you as a student in our school.</p>
                    <p class='contentp'>2. Your application to  was thoroughly reviewed by our admissions committee, and we were impressed by your academic potential, your eagerness to learn, and the positive qualities you exhibited during the admission process. Your enthusiasm for education and your readiness to engage with our school curriculum make you an excellent fit for our school.</p>
                    <p class='contentp'>3. Your student identification number is. This is the number you will use throughout your studies at our school. Your password is ; please be sure not to misplace this information.</p>
                    <div class='right'>
                        Yours Faithfully,<br/>
                        .........................<br/>
                    
                    </div>
                </div>
                <br/><br/>
                <div class='center'> 
                    
                </div>
            </div>
        </body>
        </html>
        HTML;
        
            // Load HTML content into Dompdf
            $dompdf->loadHtml($htmlelement);
        
            // Render PDF
            $dompdf->render();
        
            // Get PDF content
            $pdfData = $dompdf->output();
        
            return $pdfData;
        }
        




    

    
    }
