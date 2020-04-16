

# Laravel PayPal Subscription



    
<a name="introduction"></a>
## Introduction


PayPal Subscriptions, you can bill customers for physical and digital goods or services at regular intervals.

**Currently only PayPal Express Checkout API Is Supported.**



<a name="installation"></a>
## Installation

* Download Composer if not already installed.
* Go to your project directory. If you do not have one, just create a directory and cd in:

```bash
$ mkdir project
$ cd project
```

* Use following command to install.


```bash
$ composer require paypal/rest-api-sdk-php:*

# output:
./composer.json has been created
Loading composer repositories with package information
Updating dependencies (including require-dev)
- Installing paypal/rest-api-sdk-php (v0.16.1)
Loading from cache

Writing lock file
Generating autoload files
```

PHP version >= 7.2 is required.



<a name="configuration"></a>
## Configuration

* After installation, you will need to add your paypal settings. Following is the code you will find in **config/paypal.php**, which you should update accordingly.

```php
return [
    'mode'    => 'sandbox', // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
    'sandbox' => [
        'username'    => env('PAYPAL_SANDBOX_API_USERNAME', ''),
        'password'    => env('PAYPAL_SANDBOX_API_PASSWORD', ''),
        'secret'      => env('PAYPAL_SANDBOX_API_SECRET', ''),
        'certificate' => env('PAYPAL_SANDBOX_API_CERTIFICATE', ''),
        'app_id'      => 'APP-80W284485P519543T', // Used for testing Adaptive Payments API in sandbox mode
    ],
    'live' => [
        'username'    => env('PAYPAL_LIVE_API_USERNAME', ''),
        'password'    => env('PAYPAL_LIVE_API_PASSWORD', ''),
        'secret'      => env('PAYPAL_LIVE_API_SECRET', ''),
        'certificate' => env('PAYPAL_LIVE_API_CERTIFICATE', ''),
        'app_id'      => '', // Used for Adaptive Payments API
    ],

    'payment_action' => 'Sale', // Can only be 'Sale', 'Authorization' or 'Order'
    'currency'       => 'USD',
    'notify_url'     => '', // Change this accordingly for your application.
    'locale'         => '', // force gateway language  i.e. it_IT, es_ES, en_US ... (for express checkout only)
    'validate_ssl'   => true, // Validate SSL when creating api client.
];
```

* Add this to `.env.example` and `.env`

```
#PayPal Setting & API Credentials - sandbox
PAYPAL_SANDBOX_API_USERNAME=
PAYPAL_SANDBOX_API_PASSWORD=
PAYPAL_SANDBOX_API_SECRET=
PAYPAL_SANDBOX_API_CERTIFICATE=

#PayPal Setting & API Credentials - live
PAYPAL_LIVE_API_USERNAME=
PAYPAL_LIVE_API_PASSWORD=
PAYPAL_LIVE_API_SECRET=
PAYPAL_LIVE_API_CERTIFICATE=
```

<a name="usage"></a>
## Usage


With the help of this repository. User are able to do following functionality:

       Create Product
	   Create Plan
	   Create Subscription
	   Create Billing Plan
	   Create Recurring Transaction 

User can also update subscription deatils like switching paln , change shipping amount etc. for more detail go throw below link :-
https://developer.paypal.com/docs/api/subscriptions/v1/#subscriptions_revise
     







### Create Product

```php
  $product = new Product();
        $product->setName('diamond')
                ->setDescription('diamond plan description.')
                ->setType('SERVICE')
                ->setCategory('SOFTWARE');
                
       $productInfo= $product->create($this->apiContext);
```


### Create Plan

```php
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
```






####Paypal SDK Sample code link

http://paypal.github.io/PayPal-PHP-SDK/sample/


####Paypal SDK Rest API Refrence  link

https://developer.paypal.com/docs/api/overview/#make-rest-api-calls


 
