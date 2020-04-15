<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;


use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use App\Subscription;


class SubscriptionController extends BaseController
{
    
    
  
    private $apiContext;
    private $mode;
    private $client_id;
    private $secret;
    
    // Create a new instance with our paypal credentials
    public function __construct()
    {    
        // Detect if we are running in live mode or sandbox
       
        if(config('paypal.settings.mode') == 'live'){
            $this->client_id = config('paypal.live_client_id');
            $this->secret = config('paypal.live_secret');
        } else {
            $this->client_id = config('paypal.sandbox_client_id');
            $this->secret = config('paypal.sandbox_secret');
        }
        
        // Set the Paypal API Context/Credentials
        $this->apiContext = new ApiContext(new OAuthTokenCredential($this->client_id, $this->secret));
        $this->apiContext->setConfig(config('paypal.settings'));
    }

    public function create($planId='P-0US11820VJ054353NL2LNILA') {
        try {

            return redirect('https://www.sandbox.paypal.com/webapps/billing/plans/subscribe?plan_id='.$planId);
          
        } catch (Exception $ex) {
            die($ex);
        }
    }

    
        public function revise($subscriptionId='I-XJMRSVM5UT66',$planId='P-01B54237LE8977939L2LLRYQ') {
        try {

            
            $subscription=new Subscription();
            $subscription->setPlanId($planId);
            $subscriptionInfo=  $subscription->revise($subscriptionId, $this->apiContext, '');
            
           dd($subscriptionInfo);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    
    
    
    
   
        
   
        
}




