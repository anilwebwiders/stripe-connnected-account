<?php
function createConnectedAccountLink(){
    
    require_once('application/third_party/stripe-php-master/init.php');

    $stripe = new \Stripe\StripeClient("sk_test_51JrLpPKDhlG8lEzFne8AP3FI9uNb6rBcmvu2o......D9sgSAu.......");

    $resp2 = $stripe->accounts->create(
        [
            'country' => 'US',
            'type' => 'express',
            'capabilities' => [
                'card_payments' => ['requested' => true],
                'transfers' => ['requested' => true],
             ]
         ]
    );

    $account_id = $resp2->id;
    $output['account_id']= $account_id; 
    $resp = $stripe->accountLinks->create(
        [
            'account' => $account_id,
            'refresh_url' => BASE_URL.'account-disconnected?r=unsuccess&account_id='.$account_id,
            'return_url' => BASE_URL.'account-connected?r=success&account_id='.$account_id,
            'type' => 'account_onboarding',
            ]
    );
    
    return $resp;
}

function checkAccountConnectedOrNot()
{
    require_once('application/third_party/stripe-php-master/init.php');
    $stripe = new \Stripe\StripeClient("sk_test_51JrLpPKDhlG8lEzFne8AP3FI9uNb6rBcmvu2o......D9sgSAu.......");
    $account_id = 'acct_1LzKCHGbnF3z0Ywl';
    $data = $stripe->accounts->retrieve($account_id,[]); // this function will show status details_submitted = true
}

function createPaymentIntent()
{
    require_once('application/third_party/stripe-php-master/init.php');
    $stripe_account_id = 'acct_1LzKCHGbnF3z0Ywl';

    
    
    \Stripe\Stripe::setApiKey("sk_test_51JrLpPKDhlG8lEzFne8AP3FI9uNb6rBcmvu2o......D9sgSAu.......");

    $payment_intent = \Stripe\PaymentIntent::create([
        'amount' => 100,
        'currency' => 'usd',
        'application_fee_amount' => 12,
        'setup_future_usage' => 'off_session',
        'transfer_data' => [
            'destination' => $stripe_account_id,
        ],
    ]);
    
	
    //$payment_intent will return client secret that will use for carge card
    
}

function chargeCardlater()
{
    require_once('application/third_party/stripe-php-master/init.php');
    $stripe_account_id = 'acct_1LzKCHGbnF3z0Ywl';
    $payment_method = 'pm_1MHSB9KDhlG8lEzFwgcYRVyg';

    \Stripe\Stripe::setApiKey("sk_test_51JrLpPKDhlG8lEzFne8AP3FI9uNb6rBcmvu2o......D9sgSAu.......");
    

    $stripe = new \Stripe\StripeClient("sk_test_51JrLpPKDhlG8lEzFne8AP3FI9uNb6rBcmvu2o......D9sgSAu.......");

    $customer_id = null;
    $email = 'anil@gmail.com';

    if(!$customer_id){
        // create customer and attached to payment method
        // only one customer will attach to a payment
        $customer = \Stripe\Customer::create([
            'email' => $email,
        ]);
        $stripe->paymentMethods->attach(
            $payment_method,
            ['customer' => $customer->id]
        );
        
        $customer_id = $customer->id;
    }
			
    
    $response = \Stripe\PaymentIntent::create([
		'amount' => 10000,
		'currency' => 'usd',
		'application_fee_amount'=>1000,
		'payment_method_types' => ['card'],
		'payment_method' => $payment_method,
		'customer' => $customer_id,
		'off_session' => true,
		'confirm' => true,
		//'setup_future_usage' => 'off_session',
		'transfer_data' => [
			'destination' => $stripe_account_id,
		],
    ]);
    
	
    //$response
    
}

?>
