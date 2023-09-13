
	var poolData = {
	    UserPoolId: user_pool_id,
	    ClientId: client_id
	};

	var userPool = new AmazonCognitoIdentity.CognitoUserPool(poolData);
	var authData = {
	    Username: username,
	    Password: password
	};

	var authDetails = new AmazonCognitoIdentity.AuthenticationDetails(authData);
	var userData = {
	    Username: username,
	    Pool: userPool
	};

	var cognitoUser = new AmazonCognitoIdentity.CognitoUser(userData);

if(localStorage.getItem("noNeedRetrieveAgain") == null)
// if(localStorage.getItem("noNeedRetrieveAgain"))
{
	//Token ExpiresIn second
	cognitoUser.authenticateUser(authDetails, {
	    onSuccess: function(result)
	    {
	        var accessToken = result.getAccessToken().getJwtToken();
	        localStorage.setItem("accessToken", accessToken);

	        //Retrieve user attributes for an authenticated user.
	        cognitoUser.getUserAttributes(function(err, attributes) {
	          if (err) {
	            // Handle error
	            alert(err.message || JSON.stringify(err));
	          } else {
	          	localStorage.setItem("noNeedRetrieveAgain", "true");
	            // Do something with attributes
	            if(localStorage.getItem("kyc_countries") == null)
	            {
		            $.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/countries/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_countries", data);
					    }
					});
		        }
		        if(localStorage.getItem("kyc_identity_document_type") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/individual_records/id_types/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_identity_document_type", data);
					    }
					});
				}

				if(localStorage.getItem("kyc_ssic") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/ssic/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      	localStorage.setItem("kyc_ssic", JSON.stringify(Object.values(data)));   	
					    }
					});
				}

				if(localStorage.getItem("kyc_onboarding_mode") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/onboarding_modes/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_onboarding_mode", data);
					    }
					});
				}

				if(localStorage.getItem("kyc_payment_modes") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/payment_modes/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_payment_modes", data);
					    }
					});
				}

				if(localStorage.getItem("kyc_product_service_complexities") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/customers/product_service_complexities/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_product_service_complexities", data);
					    }
					});
				}

				if(localStorage.getItem("kyc_source_of_funds") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/records/source_of_funds/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_source_of_funds", data);
					    }
					});
				}

				if(localStorage.getItem("kyc_occupation") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/individual_records/ssoc/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_occupation", JSON.stringify(Object.values(data)));
					    }
					});
				}

				if(localStorage.getItem("kyc_entity_types") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/corporate_records/entity_types/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_entity_types", data);
					    }
					});
				}

				if(localStorage.getItem("kyc_ownership_layers") == null)
	            {
					$.ajax({
					    url: 'https://a2-acumenalphaadvisory-prod-be.cynopsis.co/client/corporate_records/ownership_layers/',
					    headers: {
					    	'Content-Type': "application/json",
							'X-ARTEMIS-DOMAIN': "1",
					        'Authorization': 'Bearer '+ localStorage.getItem("accessToken"),
					    },
					    method: 'GET',
					    success: function(data){
					      localStorage.setItem("kyc_ownership_layers", data);
					    }
					});
				}
	          }
	        });
	    },
	    onFailure: function(err)
	    {
	        alert(err.message || JSON.stringify(err));
	        console.log(err);
	    }
	});
}