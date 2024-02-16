<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Authentic;

class Authenticates extends Controller
{

    protected $audit;

    public function __construct(AuditTrialController $auditTrialController)
    {
        $this->audit = $auditTrialController;
    }
    

    function LogIn(Request $req)
    {

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        // Use your custom Authentication model to authenticate
        $user = Authentic::where('UserId', $req->UserId)->first();
    
        if ($user && Hash::check($req->Password, $user->Password)) {

            if($user->IsBlocked==true){
                return response()->json(["message"=>"Your account has been blocked, please contact your school administration "],400);
            }

            $user->IpAddress = $_SERVER['REMOTE_ADDR'];
            $user->LastLogin = Carbon::now();

            try{
                $ipDetails = json_decode(file_get_contents("https://ipinfo.io/{$user->IpAddress}/json"));
        
            $user->Country = $ipDetails->country ?? 'Unknown';
            $user->City = $ipDetails->city ?? 'Unknown';
            
            }catch(\Exception $e){
                $user->Country = $user->City =  null;
            }

            $userAgent = $_SERVER['HTTP_USER_AGENT'];

            $user->DeviceType = $this->detectDevice($userAgent);
            $user->OS =  $this->detectOperatingSystem($userAgent);

            $user->LoginAttempt = 0;
            $user->IsBlocked = false;

            $c = [
                "FullName" => $user->FullName,
                "UserId" => $user->UserId,
                "profilePic" => $user->profilePic,
                "IsPasswordReset"=>$user->IsPasswordReset,
                "CompanyId"=>$user->CompanyId,

                
            ];

            $saver = $user->save();
            if($saver){
                return response()->json(["message" => $c], 200); 
            }
            else{
                return response()->json(["message" => "An error has occured "], 400); 
            }
        


            
        }
        else{
            $user->LoginAttempt +=1;
            $user->save();

            if($user->LoginAttempt>2){
                $user->IsBlocked = true;
                $user->save();
                

                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                $country;
                $city;
                try{
                $ipDetails = json_decode(file_get_contents("https://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
            
                $country = $ipDetails->country ?? 'Unknown';
                $city = $ipDetails->city ?? 'Unknown';
                
                }catch(\Exception $e){
                    $country = $city =  null;
                }
                $TheDateandTime = Carbon::now(); 
                $content = "Attempted Account Access Details:\n" .
                "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n" .
                "Device Type: " . $this->detectDevice($userAgent) . "\n" .
                "Operating System: " . $this->detectOperatingSystem($userAgent) . "\n" .
                "Country: " . $country . "\n" .
                "City: " . $city . "\n" .
                "Date: " . $TheDateandTime;
     
    
                $this->audit->Notify(
                    $user->UserId,
                    "Security Team",
                    "Unauthorised Login",
                    "Security",
                    $content,
                    $user->CompanyId
                );
    
                return response()->json(["message"=>"Your account has been blocked, please contact your school administration "],400);
   


            }



            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $country;
            $city;
            try{
            $ipDetails = json_decode(file_get_contents("https://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
        
            $country = $ipDetails->country ?? 'Unknown';
            $city = $ipDetails->city ?? 'Unknown';
            
            }catch(\Exception $e){
                $country = $city =  null;
            }
            $TheDateandTime = Carbon::now(); 
            $content = "Attempted Account Access Details:\n" .
            "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n" .
            "Device Type: " . $this->detectDevice($userAgent) . "\n" .
            "Operating System: " . $this->detectOperatingSystem($userAgent) . "\n" .
            "Country: " . $country . "\n" .
            "City: " . $city . "\n" .
            "Date: " . $TheDateandTime;
 

            $this->audit->Notify(
                $user->UserId,
                "Security Team",
                "Unauthorised Login",
                "Security",
                $content,
                $user->CompanyId
            );





            return response()->json(['message' => 'Invalid credentials'], 401);

        }

       
    }

    function ChangeDefaultPassword(Request $req){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $user = Authentic::where('UserId', $req->UserId)->first();
        if ($user && Hash::check($req->Password, $user->Password)) {
            $user->Password = bcrypt($req->NewPassword);
           
            $saver = $user->save();
            if($saver){
                $user->IsBlocked = false;
                $user->IsPasswordReset = true;
                $user->save();
                return response()->json(["message"=>"Your password has successfully been changed"],200);
            }
            else{

                return response()->json(["message"=>"An error has occured"],400);

            }


        }else{
           
            $userAgent = $_SERVER['HTTP_USER_AGENT'];
            $country;
            $city;
            try{
            $ipDetails = json_decode(file_get_contents("https://ipinfo.io/{$_SERVER['REMOTE_ADDR']}/json"));
        
            $country = $ipDetails->country ?? 'Unknown';
            $city = $ipDetails->city ?? 'Unknown';
            
            }catch(\Exception $e){
                $country = $city =  null;
            }
            $TheDateandTime = Carbon::now(); 
            $content = "Attempted Account Access Details:\n" .
            "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n" .
            "Device Type: " . $this->detectDevice($userAgent) . "\n" .
            "Operating System: " . $this->detectOperatingSystem($userAgent) . "\n" .
            "Country: " . $country . "\n" .
            "City: " . $city . "\n" .
            "Date: " . $TheDateandTime;
 

            $this->audit->Notify(
                $user->UserId,
                "Security Team",
                "Unauthorised Login",
                "Security",
                $content,
                $user->CompanyId
            );

            return response()->json(["message"=>"Password reset failed"],400);

        
        }


    }



    function Unlocker($UserId){

        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }


        $user = Authentic::where('UserId', $UserId)->first(); 
        if($user==null){
            return response()->json(["message"=>"User does not exist"],400);
        }

        $user->LoginAttempt = 0;
        $user -> IsBlocked = false;
        $user-> IsPasswordReset = false;
        $rawPassword = $this->audit->IdGenerator();
        $user->Password = bcrypt($rawPassword);
        $user->RawPassword = $rawPassword;
    
        $saver=  $user -> save();
    
     if ($saver) {
                return response()->json(["Result" => "Success"], 200);
            } else {
                return response()->json(["Result" => "Failed"], 500);
            }
    
    
    }

    function ViewResetPassword($UserId){
        
        $response = $this->audit->PrepaidMeter($req->CompanyId);

        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }

        $user = Authentic::where('UserId', $UserId)->where("IsPasswordReset",false)->first(); 
        if($user==null){
            return response()->json(["message"=>"User does not exist or Password reset was not successful"],400);
        }

       return $user->RawPassword;

    }





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




}
