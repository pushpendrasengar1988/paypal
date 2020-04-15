<?php

namespace App;

use PayPal\Common\PayPalResourceModel;
use PayPal\Core\PayPalConstants;
use PayPal\Rest\ApiContext;
use PayPal\Transport\PayPalRestCall;
use PayPal\Validation\ArgumentValidator;


class Product extends PayPalResourceModel
{
   
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
       public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
    
    
       public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    
    
       public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }
 
    
    
    
    
    
  /**
     * Create a new billing agreement by passing the details for the agreement, including the name, description, start date, payer, and billing plan in the request JSON.
     *
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Agreement
     */
    public function create($apiContext = null, $restCall = null)
    {
        $payLoad = $this->toJSON();
     
        $json = self::executeCall(
            "/v1/catalogs/products/",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $this->fromJson($json);
        return $this;
    }


    /**
     * Execute a billing agreement after buyer approval by passing the payment token to the request URI.
     *
     * @param  $paymentToken
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Agreement
     */
    public function execute($paymentToken, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($paymentToken, 'paymentToken');
        $payLoad = "";
        $json = self::executeCall(
            "/v1/payments/billing-agreements/$paymentToken/agreement-execute",
            "POST",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $this->fromJson($json);
        return $this;
    }

    /**
     * Retrieve details for a particular billing agreement by passing the ID of the agreement to the request URI.
     *
     * @param string $agreementId
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return Agreement
     */
    public static function get($agreementId, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($agreementId, 'agreementId');
        $payLoad = "";
        $json = self::executeCall(
            "/v1/payments/billing-agreements/$agreementId",
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new Agreement();
        $ret->fromJson($json);
        return $ret;
    }
    
    
    
       /**
     * List billing plans according to optional query string parameters specified.
     *
     * @param array $params
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return PlanList
     */
    public static function all($params, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($params, 'params');
        $payLoad = "";
        $allowedParams = array(
            'page_size' => 1,
            'status' => 1,
            'page' => 1,
            'total_required' => 1
        );
        $json = self::executeCall(
            "/v1/payments/billing-plans/" . "?" . http_build_query(array_intersect_key($params, $allowedParams)),
            "GET",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        $ret = new PlanList();
        $ret->fromJson($json);
        return $ret;
    }
    
    
    
       /**
     * Replace specific fields within a billing plan by passing the ID of the billing plan to the request URI. In addition, pass a patch object in the request JSON that specifies the operation to perform, field to update, and new value for each update.
     *
     * @param PatchRequest $patchRequest
     * @param ApiContext $apiContext is the APIContext for this call. It can be used to pass dynamic configuration and credentials.
     * @param PayPalRestCall $restCall is the Rest Call Service that is used to make rest calls
     * @return bool
     */
    public function update($patchRequest, $apiContext = null, $restCall = null)
    {
        ArgumentValidator::validate($this->getId(), "Id");
        ArgumentValidator::validate($patchRequest, 'patchRequest');
        $payLoad = $patchRequest->toJSON();
        self::executeCall(
            "/v1/payments/billing-plans/{$this->getId()}",
            "PATCH",
            $payLoad,
            null,
            $apiContext,
            $restCall
        );
        return true;
    }
}
