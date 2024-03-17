<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Authentic;
use App\Jobs\UploadingAutoGeneratedAuthenticators;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDetailedRole;


class Authenticates extends Controller
{

    protected $audit;

    public function __construct(AuditTrialController $auditTrialController)
    {
        $this->audit = $auditTrialController;
    }
    

    function LogIn(Request $req)
    {

       
    
        // Use your custom Authentication model to authenticate
        $user = Authentic::where('UserId', $req->UserId)->first();
    
        if ($user && Hash::check($req->Password, $user->Password)) {

            if($user->IsBlocked==true){
                return response()->json(["message"=>"Your account has been blocked, please contact your school administration "],400);
            }

            $user->IpAddress = $_SERVER['REMOTE_ADDR'];
            $user->LastLogin = Carbon::now();

            $userAgent = $_SERVER['HTTP_USER_AGENT'];

            $user->DeviceType = $this->detectDevice($userAgent);
            $user->OS =  $this->detectOperatingSystem($userAgent);

            $user->LoginAttempt = 0;
            $user->IsBlocked = false;
            $user->SessionID = $this->audit->IdGenerator();
            $user-> Status = "Online";

            $RoleFunctionList = UserDetailedRole::where("UserId",$req->UserId)->get();

           

            $c = [
                "FullName" => $user->UserName,
                "UserId" => $user->UserId,
                "ProfilePic" => $user->ProfilePic,
                "CompanyId"=>$user->CompanyId,
                "SessionID"=>$user->SessionID,
                "PrimaryRole"=>$user->Role,
                "IpAddress" =>$user->IpAddress,
                "LoginTime" =>  $user->LastLogin,
                "Device" =>$user->DeviceType,
                "Operating System"=> $user->OS,
                "PasswordReset"=> $user->IsPasswordReset,
                "AccountType"=>$user->AccountType,


                
            ];

            $saver = $user->save();
            if($saver){
                return response()->json(["message" => $c], 200); 
            }
            else{
                return response()->json(["message" => "An error has occured "], 400); 
            }

            
        }


        return response()->json(["message" => "Invalid credentials"], 400); 
      
       
    }

    function HeartBeat($UserId){
        $user = Authentic::where('UserId', $req->UserId)->first();
        if($user==null){
            return response()->json(["message"=>"User Not Found"],400);
        }

        $user-> Status = "Online";
        $user->expirationTime = Carbon::now()->addSeconds(40);

        $user -> save();

    }

    function DashBoardChecker($SessionID){
       $worked = false;
        $user = Authentic::where('SessionID', $req->SessionID)->first();
        if($user==null){
            return response()->json(["message"=>"User Not Found"],400);
        }

        $worked = true;

        return response()->json(["message"=> $worked],200);

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


        }
        else{
           
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


    public function GetAuthFile(Request $req)
    {
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        $users = Authentic::where("CompanyId", $req->CompanyId)
            ->orderBy('UserName')
            ->get();
    
        return Excel::download(new AuthenticExport($users), 'EntireAuthList.xlsx');
    }

    public function BulkAutoGeneratedAuthenticators(Request $req)
    {
        $response = $this->audit->PrepaidMeter($req->CompanyId);
    
        if ($response !== null && $response->getStatusCode() !== 200) {
            return $response;
        }
    
        if ($req->hasFile('excel_file')) {
            $file = $req->file('excel_file');
            $filePath = $file->getPathname();
    
            
               
            // Dispatch the job to process the bulk student registration
            UploadingAutoGeneratedAuthenticators::dispatch($filePath, $req->CompanyId);
            return response()->json(["message" => "File uploaded successfully. Users will be processed in the background."], 200);

        }
    
        return response()->json(["message" => "No file uploaded."], 400);
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
