<?php

namespace App;

use PayPal\Common\PayPalResourceModel;
use PayPal\Core\PayPalConstants;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;


class Subscription extends PayPalResourceModel
{
    
    
     public function setPlanId($planid)
    {
         $this->plan_Id=$planid;
         return $this;
    }
    
    
    
    
  /**
     * Create a new billing agreement by passing the details for the agreement, including the name, description, start date, payer, and billing plan in the request JSON.
     *
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Agreement
   *  https://www.sandbox.paypal.com/webapps/billing/plans/subscribe?plan_id=P-0US11820VJ054353NL2LNILA
     */
    public function create($apiContext = null, $payLoad =null , $restCall = null)
    {
        $payLoad = !empty($payLoad) ? $payLoad: $this->toJSON();
        $json = self::executeCall(
            "/v1/billing/subscriptions/",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $this->fromJson($json);
        return $this;
    }


    public function revise($id, $apiContext = null, $payLoad=null, $restCall = null)
    {
     
        $payLoad = !empty($payLoad) ? $payLoad: $this->toJSON();
     
        self::executeCall(
            "/v1/billing/subscriptions/{$id}/revise/",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        return true;
    }
}
