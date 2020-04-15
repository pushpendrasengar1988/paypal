<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use File;
use Exception;
use Illuminate\Support\Facades\Route;
use App\TaxType;
use App\OrganizationAccount;
use App\Events\InvestorProfileUpdated;
use Illuminate\Support\Facades\Cache;
use App\Log;

class PaypalHelper {
     /**
     * Consume a GET API
     * This method should be a replacement to ALL of the GET API consuming methods created for this Software
     * @param type $getURL
     * @return type
     */
    public static function consumeGetAPI($getURL = '') {
        try {
          
             $headers = [
                'Authorization' => 'Bearer ' .'sad',
                'Accept' => 'application/json',
            ];
            $client = new \GuzzleHttp\Client(['base_uri' => env('API_BASE_URL')]);
         
           
            $response = $client->request('GET', $getURL, [
                'headers' => $headers
            ]);
    
            //dd($response->getBody()->getContents());
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $ex) {
         
          
        }
    }

    /**
     * Consume a Post API
     * This method should be a replacement to ALL of the POST API consuming methods created for this Software
     * @param type $postURL
     * @return type
     */
    public static function consumePostAPI($postURL = '', $data, $token = NULL) {
        try {

            $headers = [
                'Authorization' => 'Bearer ' . $token,
                'Accept' => 'application/json',
            ];
            $client = new \GuzzleHttp\Client(['base_uri' => env('API_BASE_URL')]);
            $response = $client->request('POST', $postURL, [
                'headers' => $headers,
                'json' => $data
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $ex) {
            \Log::write('guzzle', 'ERROR', 'consumePostAPI: ' . env('API_BASE_URL') . '|' . $postURL);
            \Log::write('guzzle', 'ERROR', $ex);
        
        }
    }

}
