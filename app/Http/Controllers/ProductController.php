<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use App\Product;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;



class ProductController extends BaseController
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

    public function createProduct() {
        try
        {
        $product = new Product();
        $product->setName('diamond')
                ->setDescription('diamond plan description.')
                ->setType('SERVICE')
                ->setCategory('SOFTWARE');
                
       $productInfo= $product->create($this->apiContext);
      
        dd($productInfo);

        
        } catch (Exception $ex) {
            die($ex);
        }
    }
    

    

}




