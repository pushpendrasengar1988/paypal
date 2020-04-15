<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;

use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use App\PaypalHelper;

use PayPal\Api\ChargeModel;




use PayPal\Api\Agreement;
use PayPal\Api\Payer;

use PayPal\Api\ShippingAddress;


class PlanController extends BaseController
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

        $plan = new Plan();
        $plan->setName('T-Shirt of the Month Club Plan')
                ->setDescription('Template creation.')
                ->setType('fixed');

// Set billing plan definitions
        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName('Regular Payments')
                ->setType('REGULAR')
                ->setFrequency('Month')
                ->setFrequencyInterval('1')
                ->setCycles('12')
                ->setAmount(new Currency(array('value' => 100, 'currency' => 'USD')));

// Set charge models
        $chargeModel = new ChargeModel();
        $chargeModel->setType('SHIPPING')
                ->setAmount(new Currency(array('value' => 10, 'currency' => 'USD')));
        $paymentDefinition->setChargeModels(array($chargeModel));

// Set merchant preferences
        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl('http://localhost:8000/processagreement')
                ->setCancelUrl('http://localhost:8000/cancel')
                ->setAutoBillAmount('yes')
                ->setInitialFailAmountAction('CONTINUE')
                ->setMaxFailAttempts('0')
                ->setSetupFee(new Currency(array('value' => 1, 'currency' => 'USD')));

        $plan->setPaymentDefinitions(array($paymentDefinition));
        $plan->setMerchantPreferences($merchantPreferences);


        
        //create the plan
        try {
            $createdPlan = $plan->create($this->apiContext);

            try {
                $patch = new Patch();
                $value = new PayPalModel('{"state":"ACTIVE"}');
                $patch->setOp('replace')
                        ->setPath('/')
                        ->setValue($value);
                $patchRequest = new PatchRequest();
                $patchRequest->addPatch($patch);
                $createdPlan->update($patchRequest, $this->apiContext);
                $plan = Plan::get($createdPlan->getId(), $this->apiContext);
       
                
                // Output plan id
              
                
               // return $this->createAgreement($createdPlan->getId());
                
                dd($createdPlan);
                
            } catch (PayPal\Exception\PayPalConnectionException $ex) {
                echo $ex->getCode();
                echo $ex->getData();
                die($ex);
            } catch (Exception $ex) {
                die($ex);
            }
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();
            echo $ex->getData();
            die($ex);
        } catch (Exception $ex) {
            die($ex);
        }
    }
    

    public function processAgreement() {

        try {

            $token = $_GET['token'];
            $agreement = new \PayPal\Api\Agreement();

            $agreement->execute($token, $this->apiContext);

            return view('success');
        } catch (Exception $ex) {
           echo $ex->getData();
           die($ex);
        }

        return "something went wrong";
    }

    public function getPlanInfo() {

        try {

            $plan = Plan::get('P-2V596878VC184634VPS7BU2Y', $this->apiContext);
        } catch (Exception $ex) {
            echo $ex->getCode();
        }
        return $plan;
    }

    
    public function cancel() {

        try {
        
            return redirect('/');
            
        } catch (Exception $ex) {
            
        }
    }
    
    

    public function createAgreement($planId='P-9F863887MX527793LPPQKNAQ') {

        try {

            $agreement = new Agreement();
            $agreement->setName('Base Agreement')
                    ->setDescription('Basic Agreement')
                    ->setStartDate('2020-06-17T9:45:04Z');

            // Set plan id
            $plan = new Plan();
            $plan->setId($planId);
            $agreement->setPlan($plan);

            // Add payer type
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $agreement->setPayer($payer);

            // Adding shipping details
            $shippingAddress = new ShippingAddress();
            $shippingAddress->setLine1('111 First Street')
                    ->setCity('Saratoga')
                    ->setState('CA')
                    ->setPostalCode('95070')
                    ->setCountryCode('US');
            $agreement->setShippingAddress($shippingAddress);


            $agreement = $agreement->create($this->apiContext);

            // Extract approval URL to redirect user
            $approvalUrl = $agreement->getApprovalLink();
            return redirect($approvalUrl);
        } catch (Exception $ex) {
            
        }
    }

}




