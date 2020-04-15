<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;


use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use App\SubscriptionPlan;








class SubscriptionPlanController extends BaseController
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

    public function createPlan() {
        try {

            $subscriptionPlan = new SubscriptionPlan();
            $subscriptionPlanInfo = $subscriptionPlan->create($this->apiContext, $this->createRequest());
            $subscriptionPlan->getSubscriptionLink();
      
            dd($subscriptionPlanInfo);
        } catch (Exception $ex) {
            die($ex);
        }
    }

    public function createRequest() {

        return '{
	"product_id": "PROD-15N24063CP068264E",
	"name": "Video Streaming Service Plan",
	"description": "Video Streaming Service basic plan",
	"status": "ACTIVE",
	"billing_cycles": [{
			"frequency": {
				"interval_unit": "MONTH",
				"interval_count": 1
			},
			"tenure_type": "TRIAL",
			"sequence": 1,
			"total_cycles": 1,
			"pricing_scheme": {
				"fixed_price": {
					"value": "10",
					"currency_code": "USD"
				}
			}
		},
		{
			"frequency": {
				"interval_unit": "MONTH",
				"interval_count": 1
			},
			"tenure_type": "REGULAR",
			"sequence": 2,
			"total_cycles": 12,
			"pricing_scheme": {
				"fixed_price": {
					"value": "100",
					"currency_code": "USD"
				}
			}
		}
	],
	"payment_preferences": {
		"auto_bill_outstanding": true,
		"setup_fee": {
			"value": "10",
			"currency_code": "USD"
		},
		"setup_fee_failure_action": "CONTINUE",
		"payment_failure_threshold": 3
	},
	"taxes": {
		"percentage": "10",
		"inclusive": false
	}
     }';
        
        
    }
    
    

}




