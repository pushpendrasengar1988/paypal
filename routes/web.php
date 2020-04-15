<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

  

Route::get('/', function () {

    return view('welcome');
});



Route::get('/login', function () {
  
    return view('welcome');
});

Route::get('/paypal', function () {
  
    return view('paypal');
});




Route::post('/create_plan', 'PlanController@createPlan');

Route::get('/processagreement', 'PlanController@processAgreement');

Route::get('/agreement/create', 'PlanController@createAgreement');

Route::get('/cancel', 'PlanController@cancel');



Route::get('/subscription/create-product', 'ProductController@createProduct');


Route::get('/subscription/create-plan', 'SubscriptionPlanController@createPlan');

Route::get('/subscription/create/{planId?}', 'SubscriptionController@create');

//Route::get('/subscription/revise/{subscriptionId?}', 'SubscriptionController@revise');



//Route::get('/create_paypal_plan', 'PaypalController@create_plan');
Route::get('/all_plan', 'PaypalController@getAllPlan');
Route::get('/all_info', 'PlanController@getPlanInfo');




