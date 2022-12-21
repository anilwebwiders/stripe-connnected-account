<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Accept in-person payments</title>
    <meta name="description" content="A demo of an in-person payment on Stripe" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  </head>
  <body>
    <form id="latest-stipe-from">
				<div class="modal-body">
					<div class="latest_stripe_err"></div>
					<div class="row">
						<div class="col-sm-12  text-center">
							<div class="Wallet_1 well wel-main">
								<h3 class="text"> <i class="fa fa-money"></i> Amount <span><i class="fa fa-gbp"></i><span class="latest-strip-deposit-amount"></span></span> </h3>
							</div>
						</div>
					</div>
				
					<h2>Amount will be $1</h2>
					
					<div class="row">
						<hr>
						<div class="col-sm-12">
							<br>
              <div class="form-group" id="card_u_name_div" >
                <label>Name on Card</label>
                <input type="text" name="card_u_name"  id="card_u_name" minlength="2" placeholder="Enter name"  value="" class="form-control" autofocus="" >
              </div>
							<div id="card-element" ><!--Stripe.js injects the Card Element--></div>
							
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							
							<p id="latest-stripe-card-error" class="text-danger" role="alert"></p>
							
						</div>
					</div>
				</div>
				<button class="btn btn-primary" id="latest-stipe-submit">
            <span class="fa fa-spin fa-spinner" style="display:none;" id="latest-stipe-spinner"></span>
            <span id="button-text">Pay</span>
          </button>
			
			</form>
    <script src="https://js.stripe.com/v3/"></script>
  </body>
<script>

(async () => {
  
	
  // Render the form using the clientSecret
})();

//https://prnt.sc/Th0mzgWL6_Ho

const isLive = false;

let stripe_account_id = 'acct_1LzKCHGbnF3z0Ywl'; //demo

//const stripe_account_id = 'acct_1LzKCHGbnF3z0Ywl'; //demo
const email = "anil@gmail.com"
const BaseUrl = 'server.php'; 

async function tst(){
	
	var myHeaders = new Headers();
	

	var formdata = new FormData();
	formdata.append("email", email);
	formdata.append("stripe_account_id", stripe_account_id);

	var requestOptions = {
		method: 'POST',
		headers: myHeaders,
		body: formdata,
		redirect: 'follow'
	};

	const response = await fetch(BaseUrl+"createPaymentIntent", requestOptions); // use createPaymentIntent function for server.php
	console.log(response);
	const response2 = await response.json();
	console.log('response2',response2);
	

	
  const clientSecret = response2.data.client_secret;
	
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	
	var stripe = Stripe('pk_test_51xxxxxxmF1HhSBowv57xgmxxxxxxxxxxgiUzNo3Tt3MSxxxxxx');

	var statement_descriptor = 'Diposit in wallet';
	var elements = stripe.elements();
	var style = {
		base: {
			color: "#32325d",
			fontFamily: 'Arial, sans-serif',
			fontSmoothing: "antialiased",
			fontSize: "16px",
			"::placeholder": {
				color: "#32325d"
			}
		},
		invalid: {
			fontFamily: 'Arial, sans-serif',
			color: "#fa755a",
			iconColor: "#fa755a"
		}
	};

	var cardElement = elements.create("card", { style: style });
	// Stripe injects an iframe into the DOM
	cardElement.mount("#card-element");

	cardElement.on("change", function (event) {
		// Disable the Pay button if there are no card details in the Element
		$("#payBtn").prop('disabled',false);
		
		//$("#latest-stripe-card-error").html(event.error ? event.error.message : "");
	});

	//var cardholderName = document.getElementById('cardholder-name');
	var form = document.getElementById("latest-stipe-from");
	var payBtn = document.getElementById("button-text");

	payBtn.addEventListener("click", function(event) {
		event.preventDefault();

		//stripe.confirmCardSetup( // for setup intent
		stripe.confirmCardPayment(
			clientSecret,
			{
				payment_method: {
					card: cardElement
				},
				setup_future_usage: 'off_session'
			}
		).then(function(result) { 
			console.log('result',result);
			if (result.error) {
				$("#latest-stripe-card-error").html(result.error ? result.error.message : "");
				alert(result.error.message);
			} else {
				
				
				succeededFun(result.paymentIntent.payment_method)
				
				// The setup has succeeded. Display a success message.
			}
		});
	});
		
}

tst();

//"pm_1MH2k8KDhlG8lEzF2oh7ZDS4"

async function succeededFun(method){
	
	console.log('method',method);
	alert('Payment done, \n '+method)
	
    //use below code if you try change after setup save card #32325d

	/*var myHeaders = new Headers();
	

	var formdata = new FormData();
	formdata.append("payment_method", method);
	formdata.append("stripe_account_id", stripe_account_id);
	formdata.append("email", email);
	formdata.append("amount", 1);

	var requestOptions = {
		method: 'POST',
		headers: myHeaders,
		body: formdata,
		redirect: 'follow'
	};

	let res = await fetch(BaseUrl+"chargeCardlater", requestOptions); //use chargeCardlater function of server.php
	res = await res.json();
	console.log('final-output',res);
	
	
	if(res.status==1){
		alert('success');
		
	} else {
		alert('error check final-output in console ');
	}*/
}


/* ------- UI helpers ------- */
// Shows a success message when the payment is complete

// Show a spinner on payment submission
var loading = function(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
		$('#latest-stipe-submit').prop('disabled',true);
		$('#latest-stipe-spinner').show();
		$('#button-text').hide();
		
  } else {
		$('#latest-stipe-submit').prop('disabled',false);
		$('#latest-stipe-spinner').hide();
    $('#button-text').show();
  }
};

</script>
</html>