<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setup;
use App\Models\CompanyToken;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SetupController extends Controller
{






    function Setup(Request $req) {
        $s = Setup::firstOrNew();

        $response = Http::post('https://api.hydottech.com/api/CompanyTokenSetUp', [
            'token' => $req->token
        ]);

        if ($response->successful()) {
            $h = $response->json(); // Extract JSON data from the response


            if (isset($h['CompanyLogo'])) {
                $logoUrl = "https://api.hydottech.com/storage/public/" . $h['CompanyLogo'];

                // Define the path to store the logo in the public directory
                $storagePath = 'images/company_logo'; // Path relative to the 'public' folder

                // Create the directory if it doesn't exist
                if (!file_exists(public_path($storagePath))) {
                    mkdir(public_path($storagePath), 0755, true);
                }

                $filename = 'logo.jpg'; // Define the filename or generate one dynamically
                $downloadedFilePath = public_path($storagePath . '/' . $filename);

                // Use Guzzle HTTP client to download the image and store it in the public folder
                $httpClient = new \GuzzleHttp\Client();
                $response = $httpClient->get($logoUrl);

                if ($response->getStatusCode() === 200) {
                    file_put_contents($downloadedFilePath, $response->getBody());

                    // Update the CompanyLogo field in the Setup model with the path relative to the 'public' folder
                    $s->CompanyLogo = $storagePath . '/' . $filename;
                } else {
                    // Handle unsuccessful image download
                    return response()->json(['message' => 'Failed to download the image'], 400);
                }

            }



            $s->CompanyId = $h['CompanyId'];
            $s->CompanyName = $h['CompanyName'];

            $s->Location = $h['Location'];
            $s->ContactPerson = $h['ContactPerson'];

            $s->CompanyPhone = $h['CompanyPhone'];
            $s->CompanyEmail = $h['CompanyEmail'];

            $s->ContactPersonPhone = $h['ContactPersonPhone'];
            $s->ContactPersonEmail = $h['ContactPersonEmail'];

            $s->CompanyStatus = $h['CompanyStatus'];
            $s->ProductId = $h['ProductId'];

            $s->ProductName = $h['ProductName'];
            $s->ProductSection = $h['ProductSection'];
            $s->Token = $h['Token'];

            $saver = $s->save();
            if($saver){
                return response()->json(["message" => $s->CompanyName." Setup Completed"],200);
            }

            return response()->json(['message' => 'An error occoured, please ensure you are connected to the internet'],400);
        } else {
            // Handle API request failure
            return response()->json(['message' => 'Please ensure your internet connection is active. The provided Token is incorrect or has expired.'], 400);
        }
    }

    function CompanyToken(Request $req) {
        $s = CompanyToken::firstOrNew();
        $c = Setup::where('id', '>', 0)->first();

        if(!$c){
            return response()->json(["message"=>"Complete your Setup process first"],400);
        }

        $response = Http::post('https://api.hydottech.com/api/CompanyToken', [
            'token' => $req->token
        ]);

        if ($response->successful()) {
            $h = $response->json(); // Extract JSON data from the response


            if($c->CompanyId !==$h["CompanyId"]) {
                return response()->json(["message"=>"You are not allowed to utilize a subscription from a different institution"],400);

            }

            if($c->ProductId !==$h["ProductId"]) {
                return response()->json(["message"=>"You are not allowed to utilize a subscription from a different product"],400);

            }


            if (isset($h['CompanyLogo'])) {
                $logoUrl = "https://api.hydottech.com/storage/public/" . $h['CompanyLogo'];

                // Define the path to store the logo in the public directory
                $storagePath = 'images/company_logo'; // Path relative to the 'public' folder

                // Create the directory if it doesn't exist
                if (!file_exists(public_path($storagePath))) {
                    mkdir(public_path($storagePath), 0755, true);
                }

                $filename = 'logo.jpg'; // Define the filename or generate one dynamically
                $downloadedFilePath = public_path($storagePath . '/' . $filename);

                // Use Guzzle HTTP client to download the image and store it in the public folder
                $httpClient = new \GuzzleHttp\Client();
                $response = $httpClient->get($logoUrl);

                if ($response->getStatusCode() === 200) {
                    file_put_contents($downloadedFilePath, $response->getBody());

                    // Update the CompanyLogo field in the Setup model with the path relative to the 'public' folder
                    $s->CompanyLogo = $storagePath . '/' . $filename;
                } else {
                    // Handle unsuccessful image download
                    return response()->json(['message' => 'Failed to download the image'], 400);
                }

            }



            $s->CompanyId = $h['CompanyId'];
            $s->CompanyName = $h['CompanyName'];

            $s->Location = $h['Location'];
            $s->ContactPerson = $h['ContactPerson'];

            $s->CompanyPhone = $h['CompanyPhone'];
            $s->CompanyEmail = $h['CompanyEmail'];

            $s->ContactPersonPhone = $h['ContactPersonPhone'];
            $s->ContactPersonEmail = $h['ContactPersonEmail'];

            $s->CompanyStatus = $h['CompanyStatus'];
            $s->ProductId = $h['ProductId'];

            $s->Token = $h['Token'];
            $s->Subcriptions = $h['Subcriptions'];


            $startDate = Carbon::parse($h['StartDate']);
            $systemDate = Carbon::parse($h['SystemDate']);
            $currentDate = Carbon::parse($h['CurrentDate']); // Assuming the format is '2024-01-08T17:39:46.895862Z'
            $expireDate = Carbon::parse($h['ExpireDate']);

            // Assign Carbon instances to corresponding properties
            $s->StartDate = $startDate;
            $s->SystemDate = $systemDate;
            $s->CurrentDate = $currentDate;
            $s->ExpireDate = $expireDate;



            $s->TokenStatus = $h['TokenStatus'];


            $saver = $s->save();
            if($saver){
                return response()->json(["message" => $s->CompanyName." Setup Completed"],200);
            }

            return response()->json(['message' => 'An error occoured, please ensure you are connected to the internet'],400);
        } else {
            // Handle API request failure
            return response()->json(['message' => "Token has expired or incorrect"], 400);
        }
    }


    function PrepaidMeter(){
        $c = CompanyToken::where('id', '>', 0)->first();

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






}
