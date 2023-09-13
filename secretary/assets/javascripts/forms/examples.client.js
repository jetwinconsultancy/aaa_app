(function( $ ) {

	'use strict';

	/*
	Wizard #Allotment
	*/
	var allotment_object = "",
		$wAllotmentfinish = $('#wAllotment').find('ul.pager li.finish'),
		$wAllotmentprevious = $('#wAllotment').find('ul.pager li.previous'),
		$wAllotmentvalidator = $("#wAllotment form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

/*	$wAllotmentfinish.on('click', function( ev ) {
		ev.preventDefault();
		return validateTab(3);*/
		/*var validated = $('#wAllotment form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
*/	//});
	/*$('#wAllotment').find('[name="name[]"]')
            .change(function(e) {
                 //Revalidate the color when it is changed 
                $('#wAllotment').formValidation('revalidateField', 'name');
            });*/
    /*$('#wAllotment').find('[name="name[]"]').change(function(e) {
            $('#wAllotment').formValidation('revalidateField', 'name[]');
        });*/
    $('#transaction_datepicker').on('changeDate', function(e) {
            $('#allotment_form').formValidation('revalidateField', 'date');
        });
/*
    $('#class').change(function(e) {
            $('#allotment_form').formValidation('revalidateField', 'class');
        });*/
    $('#allotment_form').find('[name="sharetype"]').change(function(e) {
                /* Revalidate the color when it is changed */
                //console.log("change");
                $('#allotment_form').formValidation('revalidateField', 'sharetype');
            });
   
	
	$('#wAllotment').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		/*finishSelector: 'ul.pager li.finish',*/
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var numTabs    = $('#allotment_form').find('.tab-pane').length,
                isValidTab = validateAllotmentTab(index - 1),
                finalSubmit = false,
                check_cert = true, check_edit_cert = true, check_cert_live = true;

            var rv = true; // <=== Default return value
            //console.log("onNext index"+index);
            if (!isValidTab) {
            	$(".name .help-block").css('display','none');
                return rv = false;
            }

            if (index === numTabs) {

	            $('#allotment_form').find('.merge_certificate_no').each(function() {
				   var elem = $(this);
				   //console.log(elem.val());
				   if (elem.val() != "") {
					   $.ajax({
			                type: "POST",
			                url: "masterclient/check_cert_no",
			                data: {"certificate_no":elem.val(), "client_member_share_capital_id": $("#class").val(), "cert_id": elem.parent().parent().find(".cert_id").val()}, // <--- THIS IS THE CHANGE
			                async: false,
			                dataType: "json",
			                success: function(response){
			                    
			                    //console.log(check_cert);
			                    if(!response)
			                    {
			                    	var errorsMergeCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
				        			elem.parent().parent().find( '.validate_edit_allot_from_cert' ).html( errorsMergeCert );
				        			check_cert = response;
			                    }
			                    else
			                    {
			                    	elem.parent().parent().find( '.validate_edit_allot_from_cert' ).html(" ");
			                    }
			                    
			                }               
			            });
					}
					
				   
				});
				//console.log(check_cert);

				$('#allotment_form').find('.edit_certificate_no').each(function() {
				   var elemt = $(this);
				   
				   if (elemt.val() != "") {
					   $.ajax({
			                type: "POST",
			                url: "masterclient/check_cert_no",
			                data: {"certificate_no":elemt.val(), "client_member_share_capital_id": $("#class").val(), "cert_id": elemt.parent().parent().find(".cert_id").val()}, // <--- THIS IS THE CHANGE
			                async: false,
			                dataType: "json",
			                success: function(response){
			                    
			                    if(!response)
			                    {
				                    var errorsEditCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
					        		elemt.parent().parent().find( '.validate_edit_allot_from_cert' ).html( errorsEditCert );
					        		check_edit_cert = response;
					        	}
					        	else
			                    {
			                    	elemt.parent().parent().find( '.validate_edit_allot_from_cert' ).html(" ");
			                    }
			                    
			                }               
			            });
					}
					
				   
				});

				var arr = [];
				$(".check_cert_in_live").each(function(){
					var elemt = $(this);
				    var value = $(this).val();
				    if(value != "")
				    {
				    	if (arr.indexOf(value) == -1)
					    {
					    	arr.push(value);
					    	elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
					    	check_cert_live = true;
					    	//console.log(check_cert_live);
					    }				       
					    else
					    {
					    	elemt.parent().parent().find( '.validate_edit_allot_from_cert' ).html(" ");
					    	
					    	elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
					    	var errorsEditCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
						    elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html( errorsEditCert );
						    check_cert_live = false;
						    //console.log(check_cert_live);
					    }
				    }
				    
				    
				        
				});
	            
	            // console.log(check_cert);
	            // console.log(check_edit_cert);
	            // console.log(check_cert_live);
	            $( document ).ready(function() {
		            if(check_cert && check_edit_cert && check_cert_live) {
		                // We are at the last tab

		                // Uncomment the following line to submit the form using the defaultSubmit() method
		                // $('#installationForm').formValidation('defaultSubmit');
		                //console.log("success");
		                bootbox.confirm("Are you confirm save this share allotment?", function (result) {
				            if (result) 
				            {
				            	document.getElementById('allotment_form').submit();
							}
						});
		                
		                //$("#allotment_form").submit();
		                //$(".fv-hidden-submit").removeAttr('disabled');
		                //$("#allotment_form").submit();
		                // For testing purpose
		                //$('#completeModal').modal();
		            }
		        });
            }

            $(".tr .td #number_of_share").each(function(){
            	/*console.log($(this).val());
		        console.log($(this).parent().parent().parent().find("#no_of_share_paid").val());
		        console.log(parseFloat($(this).parent().parent().parent().find("#no_of_share_paid").val()) > parseFloat($(this).val()));*/
	        	
		        if($(this).parent().parent().parent().find("#no_of_share_paid").val() != "" && $(this).val() != "")
				{
			        if(parseFloat($(this).parent().parent().parent().find("#no_of_share_paid").val()) > parseFloat($(this).val()))
			        {
			            return rv = false;
			        }
			        else
			        {
			        	return rv = true;
			        }
			    }
		        
		        	
		        
		    });

            return rv;
			/*console.log("valid====="+JSON.stringify($('#wAllotment form').valid()));
			var validated = $('#wAllotment form').valid();
			if( validated ) {
				$wAllotmentvalidator.focusInvalid();
				return false;
			}*/
		},
		/*onFinish: function ( tab, navigation, index ) {
			console.log("onFinish index"+index);
			return true;
		},*/
		onTabClick: function( tab, navigation, index, newindex ) {
			/*if ( newindex == index + 1 ) {
				return this.onNext( tab, navigation, index, newindex);
			} else if ( newindex > index + 1 ) {
				return false;
			} else {
				return true;
			}*/
			return validateAllotmentTab(index);
		},
		onPrevious: function(tab, navigation, index) {
			return true;
           // return validateAllotmentTab(index + 1);
        },
		onTabChange: function( tab, navigation, index, newindex ) {
			//console.log($('#allotment_tab .active > a').attr('href'));
			if($('#allotment_tab .active > a').attr('href')=="#allotment_confirm")
			{
				//allotment_object = "";
				//console.log(JSON.stringify($('#allotment_form').data('serialize',$('#allotment_form').serialize())));
				//console.log($('#allotment_form').serializeArray());
				/*var data = {};
				var unindexed_array = $('#allotment_form').serializeArray().map(function(x){data[x['name']] = x['value'];});

			    var indexed_array = {};*/

			    /*$.map(unindexed_array, function(n, i){
			        indexed_array[n['name']] = n['value'];
			    });*/
			    if(access_right_member_module == "read" || client_status != "1" )
				{
				    $("select#class").attr("disabled", false);
					$("#transaction_date").attr("disabled", false);
					$(".id").attr("disabled", false);
					$(".number_of_share").attr("disabled", false);
					$(".amount_share").attr("disabled", false);
					$(".amount_paid").attr("disabled", false);
				}
			    allotment_object = $('form#allotment_form').serializeObject();
			    //allotment_object = allotment_object.replace(/&?[^=]+=&|&[^=]+=$/g,'');
			    $.ajax({
			        type: "POST",
			        url: "masterclient/get_allotment_certificate",
			        data: {"client_member_share_capital_id":allotment_object["client_member_share_capital_id"], "company_code":allotment_object["company_code"], "officer_id": allotment_object["officer_id"][i], "field_type": allotment_object["field_type"][i], "transaction_type": allotment_object["transaction_type"], "date": allotment_object["date"], "member_share_id": allotment_object["member_share_id"][i]}, // <--- THIS IS THE CHANGE
			        dataType: "json",
			        async: false,
			        success: function(response){
			        	console.log(response[0]["last_cert_no"][0]["last_cert_no"]);
			        	if(response[0]["last_cert_no"][0]["last_cert_no"] == null)
			        	{
			        		var last_cert_number = 0;
			        	}
			        	else
			        	{
			        		var last_cert_number = response[0]["last_cert_no"][0]["last_cert_no"];
			        	}
			        	confirmFunction(allotment_object, parseInt(last_cert_number));
			        }
			    });
			   	

			    
			    //console.log(indexed_array);
			    /*var titles = $('input[name^=id]').map(function(idx, elem) {
				    return $(elem).val();
				  }).get();

			    console.log(titles);*/
			}
			var totalTabs = navigation.find('li').size() - 1;
			/*console.log("totalTabs=="+totalTabs);
			console.log("newindex==="+newindex);*/
			//$wAllotmentfinish[ newindex != totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
			$wAllotmentprevious[ 0 >= newindex ? 'addClass' : 'removeClass' ]( 'hidden' );
			//$('#wAllotment').find(this.nextSelector)[ newindex == totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
			//return validateTab(index + 1);

			
		},
		onTabShow: function(tab, navigation, index) {
            // Update the label of Next button when we are at the last tab

            var getUrl = window.location;
			var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
            var numTabs = $('#allotment_form').find('.tab-pane').length;
            if(access_right_member_module == "read" || client_status != "1" )
			{	            
	            if(index === numTabs - 1)
	            {
	            	$(".next").hide();
	            	$(".other_next").show();
	            }
	            else
	            {
	            	$(".next").show();
	            	$(".other_next").hide();
	            }
	            
	        }
	        else
	        {
	        	$(".other_next").hide();

	        	$('#allotment_form')
	                .find('ul.pager li.next')
	                	.attr('type', 'submit')
	                    .removeClass('disabled')    // Enable the Next button
	                    .find('a')
	                    .html(index === numTabs - 1 ? 'Save This Allotment' : 'Go To Next Page');
	        }
        }
	});
	
	function validateAllotmentTab(index) {
		//console.log("index==============="+index);
        var fv   = $('#allotment_form').data('formValidation'), // FormValidation instance
            // The current tab
            $tab = $('#allotment_form').find('.tab-pane').eq(index);

        // Validate the container
        fv.validateContainer($tab);

        //console.log("tab==="+JSON.stringify($tab));
        //console.log("validateContainer==="+JSON.stringify(fv.validateContainer($tab)));

        var isValidStep = fv.isValidContainer($tab);
        //console.log(isValidStep);
        if (isValidStep === false || isValidStep === null) {
            // Do not jump to the target tab
            return false;
        }

        return true;
    }

    /*
	Wizard #Transfer
	*/
	var transfer_object = "",
		last_class_value = "",
		last_company_code = "",
		$wTransferfinish = $('#wTransfer').find('ul.pager li.finish'),
		$wTransferprevious = $('#wTransfer').find('ul.pager li.previous'),
		$wTransfervalidator = $("#wTransfer form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

	/*$wTransferfinish.on('click', function( ev ) {
		ev.preventDefault();
		var validated = $('#wTransfer form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
	});*/

	$('#date_datepicker').on('changeDate', function(e) {
            $('#transfer_form').formValidation('revalidateField', 'date');
        });

	$('#wTransfer').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		/*finishSelector: 'ul.pager li.finish',*/
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var numTabs    = $('#transfer_form').find('.tab-pane').length,
                isValidTab = validateTransferTab(index - 1),
                finalSubmit = true,
                check_cert = true, check_edit_cert = true, check_cert_live = true;
            //console.log("onNext index"+index);
            if (!isValidTab && index != 1) {
                return false;
            }
            //console.log(index);
   //          console.log($('#transfer_form').find('.edit_certificate_no'));
	  //       $(".edit_certificate_no").each(function() {
			//    var element = $(this);
			//    console.log("edit_certificate_no");
			//    if (element.val() == "") {
			//        	return false;
			//    }
			// });
			/*if(isValid)
			{*/
			if (index === numTabs) {
                //console.log(isValidTab);
                //console.log($('#transfer_form').find('.merge_from_certificate_no'));

                $('#transfer_form').find('.merge_from_certificate_no').each(function() {
				   var element = $(this);
				   //console.log("edit_certificate_no");
				   if (element.val() == "") {
				   		//console.log($( '.validate_merge_from_cert' ));
				   		var errorsRegistrationNo = '<span class="help-block">*Please merge the certificate.</span>';
				        $( '.validate_merge_from_cert' ).html( errorsRegistrationNo );

				        finalSubmit = false;
				       	return false;
				   }
				   
				   
				});

				$('#transfer_form').find('.merge_from_certificate_no').each(function() {
				   var elem = $(this);
				   
				   if (elem.val() != "") {
					   $.ajax({
			                type: "POST",
			                url: "masterclient/check_cert_no",
			                data: {"certificate_no":elem.val(), "client_member_share_capital_id": $("#class").val(), "cert_id": elem.parent().parent().find(".cert_id").val()}, // <--- THIS IS THE CHANGE
			                async: false,
			                dataType: "json",
			                success: function(response){
			                    

			                    if(!response)
			                    {
			                    	var errorsMergeCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
				        			elem.parent().parent().find( '.validate_merge_from_cert_no' ).html( errorsMergeCert );
				        			check_cert = response;
			                    }
			                    else
			                    {
			                    	elem.parent().parent().find( '.validate_merge_from_cert_no' ).html(" ");
			                    }
			                    
			                }               
			            });
					}
				   
				});

				$('#transfer_form').find('.edit_certificate_no').each(function() {
				   var elemt = $(this);
				   
				   if (elemt.val() != "") {
					   $.ajax({
			                type: "POST",
			                url: "masterclient/check_cert_no",
			                data: {"certificate_no":elemt.val(), "client_member_share_capital_id": $("#class").val(), "cert_id": elemt.parent().parent().find(".cert_id").val()}, // <--- THIS IS THE CHANGE
			                async: false,
			                dataType: "json",
			                success: function(response){
			                    
			                    if(!response)
			                    {
				                    var errorsEditCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
					        		elemt.parent().parent().find( '.validate_edit_from_cert' ).html( errorsEditCert );
					        		check_edit_cert = response;
					        	}
					        	else
			                    {
			                    	elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
			                    }
			                }               
			            });
					}
				   
				});

				var arr = [];
				$(".check_cert_in_live").each(function(){
					var elemt = $(this);
				    var value = $(this).val();
				    if(value != "")
				    {
				    	if (arr.indexOf(value) == -1)
					    {
					    	arr.push(value);
					    	elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
					    	check_cert_live = true;
					    	//console.log(check_cert_live);
					    }				       
					    else
					    {
					    	elemt.parent().parent().find( '.validate_merge_from_cert_no' ).html(" ");
					    	elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
					    	elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html(" ");
					    	var errorsEditCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
						    elemt.parent().parent().find( '.validate_edit_allot_from_cert_live' ).html( errorsEditCert );
						    check_cert_live = false;
						    //console.log(check_cert_live);
					    }
				    }
				});

				
				// if(finalSubmit && check_cert && check_edit_cert && check_cert_live)
				// {
				// 	$("#transfer_form").submit();
				// }
				if($('#transfer_tab .active > a').attr('href')=="#transfer_confirm")
				{
					$(document).off('click', "#submitShareTransfer");
					$(document).on('click', "#submitShareTransfer",function(e){
					    e.preventDefault();
					    var notEmpty = true;

					    $('.transferor_table_add td:nth-child(1):visible').each(function(){
					        var transferor_row = $(this);
					        var transferor_name = $(this).text();
					        var new_number_of_share_transfer = 0, difference_share_transfer = 0;
					        var total_number_of_share_to_transfer = parseInt($(this).parent().find(".transfered_share").text().replace(/\,/g,''));
					        $('.transferor_table_add td:nth-child(1)').each(function(){
					            var transferor_row = $(this);
					            var check_transferor_name = $(this).text();
					            
					            if(check_transferor_name == transferor_name)
					            {
					                var number_of_share_to_transfer = parseInt(transferor_row.parent().find(".assign_number_of_share").val().replace(/\,/g,''));
					                //console.log(number_of_share_to_transfer);

					                if(isNaN(number_of_share_to_transfer))
					                {
					                    number_of_share_to_transfer = 0;
					                }

					                new_number_of_share_transfer = new_number_of_share_transfer + number_of_share_to_transfer;
					            }
					        });
					        difference_share_transfer = (-(total_number_of_share_to_transfer)) - new_number_of_share_transfer;
					        // console.log(total_number_of_share_to_transfer);
					        // console.log(difference_share_transfer);
					        // console.log(new_number_of_share_transfer);
					        if(difference_share_transfer != 0)
					        {
					            notEmpty = false;
					            return false; 
					        }
					        
					    });

					    $('.transferor_table_add td:nth-child(6)').each(function() 
					    {
					        var transferor_row = $(this);
					        var number_of_share_transfer = transferor_row.find(".assign_number_of_share").val();
					        var new_number_of_shares = transferor_row.parent().find(".new_number_of_share").val();
					        var latest_certificate = transferor_row.parent().find(".latest_certificate").val();
					        if(number_of_share_transfer != "")
					        {
					            if(new_number_of_shares == "" || latest_certificate == "")
					            {
					                notEmpty = false;
					                return false;
					            }
					        }
					    });

					    $('.certificate_table_add td:nth-child(4)').each(function() 
					    {
					        var transferee_row = $(this);
					        var new_certificate = transferee_row.find('input[name="certificate[]"]').val();
					        if(new_certificate == "")
					        {
					            notEmpty = false;
					            return false;
					        }
					    });
					    //console.log(notEmpty);

					    if(notEmpty)
					    {
					    	//console.log($('form#transfer_form').serialize());
					        //$('#loadingmessage').show();
					        bootbox.confirm("Are you confirm save this share transfer?", function (result) {
					            if (result) 
					            {
							        $.ajax({ //Upload common input
							            url: "masterclient/save_transfer",
							            type: "POST",
							            data: $('form#transfer_form').serialize() + '&shareTransferInfoArray=' + localStorage.getItem("shareTransferInfoArray"),
							            dataType: 'json',
							            success: function (response,data) {
							            //$('#loadingmessage').hide();
							            	//console.log(response);
							            	
							                if (response.Status === 1) 
							                {
							                    localStorage.removeItem("shareTransferArray");
								            	localStorage.removeItem("shareTransferInfoArray");
								            	toastr.success("Information Updated", "Updated");
								            	window.location.href = response.link;
							                }
							            }
							        })
							    }
							});
					    }
					    else
					    {
					        toastr.error("Please complete all the input field and make sure all the value is correct.", "Error");
					    }
					});
				}
            }
			//}
            // /+ '&transaction_code=' + $('#transaction_code').val() + '&transaction_task_id=' + $('#transaction_task').val() + '&registration_no=' + $('#uen').val() + '&company_code=' + transaction_company_code + '&transaction_master_id=' + $('#transaction_master_id').val()

   //          console.log($("#total_from").text());
			// console.log($("#total_to").text());
			if($("#total_from").text() != "" && $("#total_to").text() != "")
			{
				if($("#total_from").text() != $("#total_to").text())
				{
					toastr.error("The number of transfer from and transfer to is not the same.", "Error");
					return false;

                	//$("#validateTotal").text("The total is not same as above Total.");
				}
			}

			//var bool_check_no_of_share = false;
			// if($('#transfer_tab .active > a').attr('href')=="#transfer_member")
			// {
				// $("#transfer_to_add .td #number_of_share_to").each(function(){
				// 	var number_of_share = parseInt($(this).val());
				// 	var cert_no = $(this).parent().parent().parent().find("#previous_cert").val();
				// 	var company_code = $(this).parent().parent().parent().find("input[name='company_code']").val();
				// 	var to_officer_id = $(this).parent().parent().parent().find("#to_officer_id").val();
				// 	var to_field_type = $(this).parent().parent().parent().find("#to_field_type").val();
				// 	var to_id = $(this).parent().parent().parent().find("#to_id").val();
		            
		  //           $.ajax({
		  //               type: "POST",
		  //               url: "masterclient/check_negative_number_of_share",
		  //               data: {"id": to_id,"client_member_share_capital_id": $("#class").val(), "number_of_share":number_of_share, "certificate_no": cert_no, "company_code": company_code, 'to_officer_id': to_officer_id, 'to_field_type': to_field_type}, // <--- THIS IS THE CHANGE
		  //               async: false,
		  //               dataType: "json",
		  //               success: function(response){
		  //                   if(response.popup == 1)
		  //                   {
		  //                   	bootbox.alert("This action will result in negative balance of number of share for <a href='masterclient/check_share/"+company_code+"/"+$("#class").val()+"/"+to_officer_id+"/"+to_field_type+"/"+cert_no+"/Transfer/each_transfer"+"' target='_blank' class='click_some_member'>some members</a>.", function (result) {

				// 		        });

		  //                   	bool_check_no_of_share = false;
		  //                   	return false;
		  //                   }
		  //                   else if(response.popup == 2)
		  //                   {
		  	
		                    	// bool_check_no_of_share = true;
		                    	// return true;
		        //             }
		                    
		                    
		        //         }               
		        //     });
		        // });
				
			//}
			//console.log($('#transfer_tab .active > a').attr('href'));
			// if($('#transfer_tab .active > a').attr('href')!="#transfer_member")
			// {
   //          	return true;
   //          }
   //          else
   //          {
   //          	if(bool_check_no_of_share)
   //          	{
   //          		return true;
   //          	}
   //          	else
   //          	{
   //          		return false;
   //          	}
   //          }

		},
		onTabClick: function( tab, navigation, index, newindex ) {
			return validateTransferTab(index);
		},
		onPrevious: function(tab, navigation, index) {
			return true;
            /*return validateTransferTab(index + 1);*/
        },
		onTabChange: function( tab, navigation, index, newindex ) {

			if($('#transfer_tab .active > a').attr('href')=="#transfer_member")
			{
				/*console.log($('select[name="class"]').val());
				console.log($('input[name="company_code"]').val());*/
				// console.log($("#total_from").text());
				// console.log($("#total_to").text());

				var client_member_share_capital_id = $('select[name="class"]').val();
				var company_code = $('input[name="company_code"]').val();
				var transaction_id = $('input[name="edit_transaction_id"]').val();
				var transaction_date = $('input[name="edit_transaction_date"]').val();
				var link;
				//console.log(transaction_id == '');
				/*console.log(last_company_code);*/
				// if(transaction_id == '')
				// {
					link = "masterclient/get_allotment_people";
				// }
				// else
				// {
				// 	link = "masterclient/get_transfer_people";
				// }
				if(client_member_share_capital_id != last_class_value || company_code != last_company_code)
				{
					var alloment_people;
					$.ajax({
		                type: "POST",
		                url: link,
		                data: {"client_member_share_capital_id":client_member_share_capital_id, "company_code":company_code, "transaction_id":transaction_id, "transaction_date":transaction_date}, // <--- THIS IS THE CHANGE
		                dataType: "json",
		                success: function(response){
		                    //console.log(response);
		                    alloment_people = response;
		                    
							assignAllomentPeople(alloment_people);

							

							last_class_value = client_member_share_capital_id;
							last_company_code = company_code;
		                }               
		            });
				}
			}

			if($('#transfer_tab .active > a').attr('href')=="#transfer_confirm")
			{
				var company_code = $('input[name="company_code"]').val();
				var client_member_share_capital_id = $('select[name="class"]').val();

				$.ajax({
	                type: "POST",
	                url: "masterclient/getShareTransferInfo",
	                data: {"company_code": company_code, "client_member_share_capital_id": client_member_share_capital_id}, // <--- THIS IS THE CHANGE
	                dataType: "json",
	                async: false,
	                success: function(response){
	                	//console.log(response);
	                	$("#transaction_share_transfer").remove();
						if(response[0]["share_number_for_cert_record"])
	                 	{
	                 		var transaction_share_number_for_cert_record = response[0]["share_number_for_cert_record"];
	                 		var transaction_last_cert_no = response[0]["last_cert_no"][0]["last_cert_no"], latest_certificate_no;

						    $(".confirm_share_number_for_cert_record").remove();
						    //console.log(transaction_share_number_for_cert_record);
						    if(transaction_share_number_for_cert_record)
						    {
						        for(var i = 0; i < transaction_share_number_for_cert_record.length; i++)
						        {
						        	if(transaction_share_number_for_cert_record[i]["number_of_share"] != 0)
						        	{
							            if(transaction_share_number_for_cert_record[i]["sharetype"] == "Others")
							            {
							                var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")";
							            }
							            else
							            {
							                var sharetype = "";
							            }

							            if(transaction_share_number_for_cert_record[i]["certificate_no"] != "")
							            {
							            	latest_certificate_no = transaction_share_number_for_cert_record[i]["certificate_no"];
							            	transaction_last_cert_no = transaction_share_number_for_cert_record[i]["certificate_no"];
							            }
							            else
							            {
							            	latest_certificate_no = parseInt(transaction_last_cert_no) + 1;
							            	transaction_last_cert_no = parseInt(transaction_last_cert_no) + 1;
							            	$(".cert_remind_tag").text("* Below certificate number are suggested by the system, please save it before you proceed to the next page.");
							            }

							            var a = ""; 
							            a += '<tr class="confirm_share_number_for_cert_record">';
							            a += '<td class="transferor_name"><input type="hidden" class="officer_id" value="'+transaction_share_number_for_cert_record[i]["officer_id"]+'"><input type="hidden" class="field_type" value="'+transaction_share_number_for_cert_record[i]["field_type"]+'">'+((transaction_share_number_for_cert_record[i]["identification_no"] != null)?transaction_share_number_for_cert_record[i]["identification_no"] : (transaction_share_number_for_cert_record[i]["register_no"] != null?transaction_share_number_for_cert_record[i]["register_no"]:transaction_share_number_for_cert_record[i]["registration_no"]))+' - '+((transaction_share_number_for_cert_record[i]["name"] != null)?transaction_share_number_for_cert_record[i]["name"] : (transaction_share_number_for_cert_record[i]["company_name"] != null?transaction_share_number_for_cert_record[i]["company_name"]:transaction_share_number_for_cert_record[i]["client_company_name"]))+'</td>';
							            a += '<td>'+transaction_share_number_for_cert_record[i]["currency"]+'/'+ transaction_share_number_for_cert_record[i]["sharetype"] + " " + sharetype+'</td>';
							            a += '<td style="text-align:right" class="old_nfs">'+addCommas(transaction_share_number_for_cert_record[i]["number_of_share"])+'</td>';
							            a += '<td style="text-align:center">'+transaction_share_number_for_cert_record[i]["new_certificate_no"]+'</td>';
							            // a += '<td style="text-align:center"><input type="checkbox" name="cancel_shares_cert[]" class="cancel_shares_cert" value="'+transaction_share_number_for_cert_record[i]["id"]+'" disabled="true"/></td>';
							            a += '<td style="text-align:right" class="transfered_share">0</td>';
							            a += '<td><input class="form-control numberdes assign_number_of_share" type="text" name="assign_number_of_share[]" value="" readonly="true"></td>';
							            a += '<td><input class="form-control new_number_of_share" type="text" name="new_number_of_share[]" value="" readonly="true"></td>';
							            a += '<td><input type="hidden" name="cert_officer_id[]" value="'+transaction_share_number_for_cert_record[i]["officer_id"]+'"><input type="hidden" name="cert_field_type[]" value="'+transaction_share_number_for_cert_record[i]["field_type"]+'"><input type="hidden" name="certificate_id[]" value="'+transaction_share_number_for_cert_record[i]["id"]+'"><input type="hidden" name="sharetype[]" value="'+transaction_share_number_for_cert_record[i]["sharetype"] + " " + sharetype+'"><input class="form-control latest_certificate" type="text" name="certificate[]" value="" readonly="true"></td>';//'+latest_certificate_no+'
							            a += '</tr>';

							            $(".transferor_table_add").append(a);
							        }
						        }
						    }
	      				}

						$('.transferor_table_add').each(function () {
					        var values = $(this).find("tr>td:first-of-type");
					        var number_of_share_transfer = $(this).find("tr>td:nth-of-type(5)");
					        var run = 1
					        for (var i=values.length-1;i>-1;i--){
					            if ( values.eq(i).text()=== values.eq(i-1).text() && i != 0){
					                values.eq(i).hide();
					                number_of_share_transfer.eq(i).hide();
					                run++;
					            }else{
					                values.eq(i).attr("rowspan",run);
					                number_of_share_transfer.eq(i).attr("rowspan",run);
					                run = 1;
					            }
					        }
					    });

					    var latestShareTransferInfoArrayList = JSON.parse(localStorage.getItem("shareTransferArray")),
					    latestShareTransferInfoArray = JSON.parse(localStorage.getItem("shareTransferArray")),
					    result = [];
					    //console.log(latestShareTransferInfoArrayList);

					    var total_share_transfer_output = latestShareTransferInfoArrayList.reduce(function(accumulator, cur) {
						  	var name = cur["to_person_name[0]"], found = accumulator.find(function(elem) {
						      	return elem["to_person_name[0]"] == name
						  	});
						  	if (found)
						  	{
						  		found["share_transfer[0]"] = parseInt(found["share_transfer[0]"].toString().replace(/,/g, "")) + parseInt(cur["share_transfer[0]"].toString().replace(/,/g, ""));
						  		found["number_of_share_to[0]"] = parseInt(found["number_of_share_to[0]"].toString().replace(/,/g, "")) + parseInt(cur["number_of_share_to[0]"].toString().replace(/,/g, ""));
						  	}
						  	else
						  	{ 
								accumulator.push(cur);
							}
						  	return accumulator;
						}, []);
					    //console.log(total_share_transfer_output);
					    var update_total_share_transfer_output = JSON.parse(localStorage.getItem("shareTransferArray"));
					    for(var $h = 0; $h < update_total_share_transfer_output.length; $h++)
					    {
					    	update_total_share_transfer_output[$h]["cert_id"] = update_total_share_transfer_output[$h]["cert_id[]"];
					    	update_total_share_transfer_output[$h]["transfer_id"] = update_total_share_transfer_output[$h]["transfer_id[]"];
					    	update_total_share_transfer_output[$h]["officer_id"] = update_total_share_transfer_output[$h]["officer_id[0]"];
					    	update_total_share_transfer_output[$h]["field_type"] = update_total_share_transfer_output[$h]["field_type[0]"];
					    	update_total_share_transfer_output[$h]["certID"] = update_total_share_transfer_output[$h]["certID[0]"];
					    	update_total_share_transfer_output[$h]["identification_no"] = update_total_share_transfer_output[$h]["identification_no[0]"];
					    	update_total_share_transfer_output[$h]["person_name"] = update_total_share_transfer_output[$h]["person_name[0]"];
					    	update_total_share_transfer_output[$h]["id"] = update_total_share_transfer_output[$h]["id[0]"];
					    	update_total_share_transfer_output[$h]["current_share"] = update_total_share_transfer_output[$h]["current_share[0]"];
					    	update_total_share_transfer_output[$h]["amount_share"] = update_total_share_transfer_output[$h]["amount_share[0]"];
					    	update_total_share_transfer_output[$h]["no_of_share_paid"] = update_total_share_transfer_output[$h]["no_of_share_paid[0]"];
					    	update_total_share_transfer_output[$h]["amount_paid"] = update_total_share_transfer_output[$h]["amount_paid[0]"];
					    	update_total_share_transfer_output[$h]["share_transfer"] = update_total_share_transfer_output[$h]["share_transfer[0]"];
					    	update_total_share_transfer_output[$h]["consideration"] = update_total_share_transfer_output[$h]["consideration[0]"];
					  		update_total_share_transfer_output[$h]["to_cert_id"] = update_total_share_transfer_output[$h]["to_cert_id[]"];
					  		update_total_share_transfer_output[$h]["to_id"] = update_total_share_transfer_output[$h]["to_id[]"];
					  		update_total_share_transfer_output[$h]["to_officer_id"] = update_total_share_transfer_output[$h]["to_officer_id[0]"];
					  		update_total_share_transfer_output[$h]["to_field_type"] = update_total_share_transfer_output[$h]["to_field_type[0]"];
					  		update_total_share_transfer_output[$h]["previous_new_cert"] = update_total_share_transfer_output[$h]["previous_new_cert[0]"];
					  		update_total_share_transfer_output[$h]["previous_cert"] = update_total_share_transfer_output[$h]["previous_cert[0]"];
					  		update_total_share_transfer_output[$h]["to_person_name"] = update_total_share_transfer_output[$h]["to_person_name[0]"];
					  		update_total_share_transfer_output[$h]["number_of_share_to"] = update_total_share_transfer_output[$h]["number_of_share_to[0]"];
					    }
					    localStorage.shareTransferInfoArray = JSON.stringify(update_total_share_transfer_output);
						//Transferee Record
		     			if(total_share_transfer_output)
	                 	{
	                 		var latest_share_number_for_cert = total_share_transfer_output;
	                 		var last_cert_no = response[0]["last_cert_no"][0]["last_cert_no"], latest_certificate_no;

						    $(".confirm_latest_share_number_for_cert").remove();

						    if(latest_share_number_for_cert)
						    {
						        for(var i = 0; i < latest_share_number_for_cert.length; i++)
						        {
						        	// if(parseInt(latest_share_number_for_cert[i]["number_of_share_to[0]"].toString().replace(',', '')) > 0)
						        	// {
							            if(latest_share_number_for_cert[i]["sharetype"] == "Others")
							            {
							                var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")";
							            }
							            else
							            {
							                var sharetype = "";
							            }

						            	latest_certificate_no = parseInt(last_cert_no) + 1;
						            	last_cert_no = parseInt(last_cert_no) + 1;
							            
							            var a = ""; 
							            a += '<tr class="confirm_latest_share_number_for_cert">';
							            a += '<td>'+ latest_share_number_for_cert[i]["id_to[0]"] + " - " + latest_share_number_for_cert[i]["to_person_name[0]"] +'</td>';
							            a += '<td>'+ latest_share_number_for_cert[i]["currency"]+'/'+ latest_share_number_for_cert[i]["sharetype"] + " " + sharetype+'</td>';
							            a += '<td style="text-align:right"><input type="hidden" name="transferee_new_number_of_share[]" value="'+latest_share_number_for_cert[i]["number_of_share_to[0]"].toString().replace(/,/g, "")+'"/>'+addCommas(latest_share_number_for_cert[i]["number_of_share_to[0]"].toString().replace(/,/g, ""))+'</td>';
							            //a += '<td style="text-align:center"> - </td>';
							            //a += '<td><input class="form-control" type="text" name="new_number_of_share[]" value=""></td>';
							            a += '<td><input type="hidden" name="transferee_officer_id[]" value="'+latest_share_number_for_cert[i]["to_officer_id[0]"]+'"/><input type="hidden" name="transferee_field_type[]" value="'+latest_share_number_for_cert[i]["to_field_type[0]"]+'"/><input type="hidden" name="transferee_sharetype[]" value="'+latest_share_number_for_cert[i]["sharetype"] + " " + sharetype+'"><input class="form-control" type="text" name="transferee_certificate[]" value="'+latest_certificate_no+'"/></td>';
							            a += '</tr>';

							            $(".certificate_table_add").append(a);
							        // }
							        // else
							        // {
							        	
							        //}
						        }
						    }

						    $.each(latestShareTransferInfoArray, function(index, value) {
						    	var negative_transferee_officer_id = value["officer_id[0]"];
					        	var negative_transferee_field_type = value["field_type[0]"];
					        	$('.transferor_table_add td:first-child').each(function() 
								{
									var transferor_row = $(this);
									var transferor_officer_id = transferor_row.find(".officer_id").val();
									var transferor_field_type = transferor_row.find(".field_type").val();
									var transferor_share_transfer = parseInt(transferor_row.parent().find(".transfered_share").text().toString().replace(/,/g, ""));
									//console.log(transferor_row.parent().find(".transfered_share"));
									if(negative_transferee_officer_id == transferor_officer_id && negative_transferee_field_type == transferor_field_type)
									{
										console.log(value["number_of_share_to[0]"]);

										//latest_certificate_no = parseInt(transaction_last_cert_no) + 1;
										if(transferor_share_transfer != "0")
										{
											transferor_share_transfer = -(transferor_share_transfer);
										}
										transferor_share_transfer = parseInt(value["number_of_share_to[0]"].toString().replace(/,/g, "")) + (transferor_share_transfer);
										console.log(addCommas(-(transferor_share_transfer)));
										$(this).parent().find(".transfered_share").text(addCommas(-(transferor_share_transfer)));
										//$(this).parent().find(".latest_certificate").val(latest_certificate_no);
									}
									//console.log($(this).parent());
									//console.log(transferee_officer_id);
									//console.log(transferee_field_type);
								});
						    });

						    var new_number_of_share = 0;
						    var transferor_first_name = $(".transferor_table_add td").eq(0).text();
						    var finish_minus_calculation = false;
						    var number_of_loop = 0;

                 			$("#transfer_confirm .cert_remind_tag").text("* Below certificate number are suggested by the system.");
                 			
						    $('.transferor_table_add td:nth-child(3)').each(function() 
							{
								var transferor_row = $(this);
								var number_of_share_to_transfer = parseInt(transferor_row.parent().find(".transfered_share").text().replace(/,/g, ""));
								var amount_of_left, old_number_of_share = parseInt(transferor_row.text().replace(/,/g, ""));
								var transferor_next_name = transferor_row.parent().find(".transferor_name").text(), numberOfShareTransfer = 0;
								//console.log(number_of_share_to_transfer);
								if(0 > number_of_share_to_transfer)
								{	
									//console.log(finish_minus_calculation);
									console.log(new_number_of_share+" test");
									if(new_number_of_share == 0 && transferor_next_name == transferor_first_name)
									{
										if(!finish_minus_calculation)
										{
											new_number_of_share = old_number_of_share - (-(number_of_share_to_transfer));
											if(new_number_of_share > 0)
											{
												numberOfShareTransfer = number_of_share_to_transfer;
												//finish_minus_calculation = true;
											}
										}

										if(number_of_loop > 0 && new_number_of_share == 0)
										{
											finish_minus_calculation = true;
										}
									}
									else if(0 > new_number_of_share && transferor_next_name == transferor_first_name)
									{
										numberOfShareTransfer = new_number_of_share;
										new_number_of_share = old_number_of_share - (-(new_number_of_share));
									}
									else
									{
										transferor_first_name = transferor_next_name;
										new_number_of_share = old_number_of_share - (-(number_of_share_to_transfer));
										numberOfShareTransfer = number_of_share_to_transfer;
										finish_minus_calculation = false;
									}
									
									if(finish_minus_calculation)
									{
										transferor_row.parent().find(".new_number_of_share").val("");
										transferor_row.parent().find(".latest_certificate").val("");
										transferor_row.parent().find(".assign_number_of_share").removeAttr("readonly");
										transferor_row.parent().find(".assign_number_of_share").val("");
										transferor_row.parent().find(".latest_certificate").removeAttr("readonly");
										//transferor_row.parent().find(".cancel_shares_cert").removeAttr("disabled");
										//transferor_row.parent().find(":checkbox.cancel_shares_cert").prop('checked', true);
									}
									else
									{
										if(0 >= new_number_of_share)
										{
											transferor_row.parent().find(".new_number_of_share").val(0);
											transferor_row.parent().find(".latest_certificate").val("NA");
											transferor_row.parent().find(".assign_number_of_share").removeAttr("readonly");
											transferor_row.parent().find(".assign_number_of_share").val(addCommas(old_number_of_share));

											//transferor_row.parent().find(".cancel_shares_cert").removeAttr("disabled");
											//transferor_row.parent().find(":checkbox.cancel_shares_cert").prop('checked', true);
											
											// transferor_row.parent().find(".new_number_of_share").removeAttr("readonly");
											transferor_row.parent().find(".latest_certificate").removeAttr("readonly");
										}
										else if (new_number_of_share > 0)
										{
											latest_certificate_no = latest_certificate_no + 1;
											transferor_row.parent().find(".new_number_of_share").val(addCommas(new_number_of_share));
											transferor_row.parent().find(".latest_certificate").val(latest_certificate_no);
											transferor_row.parent().find(".assign_number_of_share").removeAttr("readonly");
											transferor_row.parent().find(".assign_number_of_share").val(addCommas(-(numberOfShareTransfer)));
											// transferor_row.parent().find(".cancel_shares_cert").removeAttr("disabled");
											// transferor_row.parent().find(":checkbox.cancel_shares_cert").prop('checked', true);

											// transferor_row.parent().find(".new_number_of_share").removeAttr("readonly");
											transferor_row.parent().find(".latest_certificate").removeAttr("readonly");
											new_number_of_share = 0;

											finish_minus_calculation = true;
											
										}
									}
								}
								number_of_loop++;
							});

							$(".hidden_latest_cert_no").val(latest_certificate_no);
		      			}

						// latestShareTransferInfoArray.forEach(function (a) {
						// 	console.log(a["to_person_name[0]"]);
						//     if (!this[a["to_person_name[0]"]]) {
						//         this[a["to_person_name[0]"]] = a;
						//         //this[a["to_person_name[0]"]]["number_of_share_to[0]"] = 0;
						//         result.push(this[a["to_person_name[0]"]]);
						//     }
						//     console.log(this[a["to_person_name[0]"]]["number_of_share_to[0]"]);
						//     console.log(a["number_of_share_to[0]"]);
						//     var numberOfShare = this[a["to_person_name[0]"]]["number_of_share_to[0]"];
						//     this[a["to_person_name[0]"]]["number_of_share_to[0]"] = parseInt(numberOfShare.toString().replace(/,/g, "")) + parseInt((a["number_of_share_to[0]"]).toString().replace(/,/g, ""));
						// }, Object.create(null));

						
	                }
	            });

				$('.assign_number_of_share').change(function() {
					var numberOfShareToTransferRow = $(this);
					var $tr = $(this).closest('tr');
			    	var myRow = $("#transferee_table tr").index($tr);
					var transferor_name = numberOfShareToTransferRow.parent().parent().find(".transferor_name").text();
					var current_old_nfs = parseInt(numberOfShareToTransferRow.parent().parent().find(".old_nfs").text().replace(/\,/g,''));
					var transfered_share = -(parseInt(numberOfShareToTransferRow.parent().parent().find(".transfered_share").text().replace(/\,/g,'')));
					var latest_certificate_number = parseInt($(".hidden_latest_cert_no").val());
					var cert_value = numberOfShareToTransferRow.parent().parent().find(".latest_certificate").val();

					var new_number_of_share = 0;
					var new_number_of_share_transfer = 0;

					if(numberOfShareToTransferRow.val() == 0 || numberOfShareToTransferRow.val() == "")
					{
						numberOfShareToTransferRow.val(-1);
						numberOfShareToTransferRow.parent().parent().find(".new_number_of_share").val("");
						numberOfShareToTransferRow.parent().parent().find(".latest_certificate").val("");
					}

					$('.transferor_table_add td:nth-child(1)').each(function() 
					{
						var transferor_row = $(this);
						var check_transferor_name = $(this).text();
						
						if(check_transferor_name == transferor_name)
						{
							var number_of_share_to_transfer = parseInt(transferor_row.parent().find(".assign_number_of_share").val().replace(/\,/g,''));

							if(isNaN(number_of_share_to_transfer))
							{
								number_of_share_to_transfer = 0;
							}

							new_number_of_share_transfer = new_number_of_share_transfer + number_of_share_to_transfer;
						}
					});

					var number_of_share_left = 0;
					
					if(new_number_of_share_transfer > transfered_share)
					{
						$(this).val("");
						$(this).parent().parent().find(".new_number_of_share").val("");
						$(this).parent().parent().find(".latest_certificate").val("");
						toastr.warning("The total value cannot bigger that total number of shares to transfer.", "Warning");
					}
					else if(transfered_share > new_number_of_share_transfer)
					{
						var total_number_of_share_left = transfered_share;
						var transferor_row = $(this);
						var $trNumberST = $(this).closest('tr');
						var check_transferor_name = $(this).text();
						var check_old_number_of_share = parseInt(transferor_row.parent().parent().find(".old_nfs").text().replace(/\,/g,''));

						var number_of_share_to_transfer = parseInt(transferor_row.parent().parent().find(".assign_number_of_share").val().replace(/\,/g,''));

						if(check_old_number_of_share > number_of_share_to_transfer)
						{
							if(isNaN(number_of_share_to_transfer) || number_of_share_to_transfer > 0)//|| number_of_share_to_transfer > 0
							{
								if(total_number_of_share_left >= check_old_number_of_share)
								{
									var latest_number_of_share = check_old_number_of_share - number_of_share_to_transfer;
									transferor_row.parent().parent().find(".new_number_of_share").val(addCommas(parseInt(latest_number_of_share)));
									total_number_of_share_left = total_number_of_share_left - number_of_share_to_transfer;
									transferor_row.parent().parent().find(".latest_certificate").val("");
								}
								else if(check_old_number_of_share > total_number_of_share_left)
								{
									if(total_number_of_share_left > 0)
									{
										if(number_of_share_to_transfer > 0)
										{
											var latest_number_of_share = check_old_number_of_share - number_of_share_to_transfer;
											transferor_row.parent().parent().find(".new_number_of_share").val(addCommas(parseInt(latest_number_of_share)));
											total_number_of_share_left = total_number_of_share_left - number_of_share_to_transfer;
										}
										else
										{
											var latest_number_of_share = check_old_number_of_share - total_number_of_share_left;
											transferor_row.parent().parent().find(".assign_number_of_share").val(addCommas(parseInt(total_number_of_share_left)));
											total_number_of_share_left = total_number_of_share_left - total_number_of_share_left;
											transferor_row.parent().parent().find(".new_number_of_share").val(addCommas(parseInt(latest_number_of_share)));
										}
									}
									else
									{
										transferor_row.parent().parent().find(".assign_number_of_share").val("");
										transferor_row.parent().find(".new_number_of_share").val("");
										transferor_row.parent().find(".latest_certificate").val("");
									}
								}
								else
								{
									transferor_row.parent().parent().find(".assign_number_of_share").val("");
									transferor_row.parent().parent().find(".new_number_of_share").val("");
									transferor_row.parent().parent().find(".latest_certificate").val("");
								}
							}
							else if(number_of_share_to_transfer == 0)
							{
								numberOfShareToTransferRow.val(-1);
								numberOfShareToTransferRow.parent().parent().find(".new_number_of_share").val("");
								numberOfShareToTransferRow.parent().parent().find(".latest_certificate").val("");
							}
						}
						else
						{
							transferor_row.parent().parent().find(".assign_number_of_share").val("");
							transferor_row.parent().parent().find(".new_number_of_share").val("");
							transferor_row.parent().parent().find(".latest_certificate").val("");
							toastr.warning("The number of shares to transfer value cannot bigger that old number of shares.", "Warning");
						}
					}
					else
					{
						var new_number_of_share = current_old_nfs - parseInt($(this).parent().find(".assign_number_of_share").val().replace(/\,/g,''));
						$(this).parent().parent().find(".new_number_of_share").val(addCommas(new_number_of_share));
						if(new_number_of_share == 0)
						{
							$(this).parent().parent().find(".latest_certificate").val("NA");
						}
					}

					if(0 > numberOfShareToTransferRow.val())
					{
						numberOfShareToTransferRow.val("");
					}   
			    });
				// if(access_right_member_module == "read" || client_status != "1" )
		  //       {
		  //           $(".person_id").attr("disabled", false);
		  //           $(".share_transfer").attr("disabled", false);
		  //           $(".get_person_id").attr("disabled", false);
		  //           $(".number_of_share_to").attr("disabled", false);
		  //       }

			 //    transfer_object = $('form#transfer_form').serializeObject();

			 //   	confirmTransfer(transfer_object);
			}
			var totalTabs = navigation.find('li').size() - 1;

			$wTransferprevious[ 0 >= newindex ? 'addClass' : 'removeClass' ]( 'hidden' );
			/*var totalTabs = navigation.find('li').size() - 1;
			$wTransferfinish[ newindex != totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
			$('#wTransfer').find(this.nextSelector)[ newindex == totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );*/
		},
		onTabShow: function(tab, navigation, index) {
			//console.log(index);
			//if($('#transfer_tab > a').attr('href')=="#transfer_member")
			//{
				/*console.log($("#total").text());
				console.log($("#total_to").text());
				if($("#total").text() != "" && $("#total_to").text() != "")
				{
					if($("#total").text() != $("#total_to").text())
					{
						$('#transfer_form')
	                		.find('ul.pager li.next')
	                		.addClass('disabled');

	                	//$("#validateTotal").text("The total is not same as above Total.");
					}
					else if($("#total").text() == $("#total_to").text())
					{
						$('#transfer_form')
	                		.find('ul.pager li.next')
	                		.removeClass('disabled');

	                	//$("#validateTotal").text("The total is not same as above Total.");
					}
				}*/
				
			//}


            // Update the label of Next button when we are at the last tab
            var numTabs = $('#transfer_form').find('.tab-pane').length;

            if(access_right_member_module == "read" || client_status != "1" )
			{	            
	            if(index === numTabs - 1)
	            {
	            	$(".next").hide();
	            	$(".other_next").show();
	            }
	            else
	            {
	            	$(".next").show();
	            	$(".other_next").hide();
	            }
	            
	        }
	        else
	        {
	        	$(".other_next").hide();
	        	
	        	$('#transfer_form')
                .find('ul.pager li.next')
                	.attr('type', 'button')
                	.attr('id', 'submitShareTransfer')
                    .removeClass('disabled')    // Enable the Next button
                    .find('a')
                    .html(index === numTabs - 1 ? 'Save This Transfer' : 'Go To Next Page');
	        }
            
        }
	});

	function validateTransferTab(index) {

		if(index != 1)
		{
	        var fv   = $('#transfer_form').data('formValidation'), // FormValidation instance
	            
	        $transfer_tab = $('#transfer_form').find('.tab-pane').eq(index);// The current tab

	        // Validate the container
	        fv.validateContainer($transfer_tab);

	        //console.log("tab==="+JSON.stringify($tab));
	        //console.log("validateContainer==="+JSON.stringify(fv.validateContainer($tab)));

	        var isValidTransferStep = fv.isValidContainer($transfer_tab);
	        //console.log(isValidTransferStep);
	        if (isValidTransferStep === false || isValidTransferStep === null) {
	            // Do not jump to the target tab
	            //console.log($('#transfer_form').find('.merge_from_certificate_no'));
	            return false;
	        }
	        return true;
	    }
        return true;

        
    }

    function removeCommas(str) {
	    while (str.search(",") >= 0) {
	        str = (str + "").replace(/,/g, "");
	    }
	    return str;
	};

	/*
	Wizard #Buyback
	*/
	var buyback_object = "",
		last_buyback_class_value = "",
		last_buyback_company_code = "",
		last_buyback_share = "",
		last_transaction_date = "",
		previous_buyback_share = "",
		change_the_percentage = true,
		$wBuybackfinish = $('#wBuyback').find('ul.pager li.finish'),
		$wBuybackprevious = $('#wBuyback').find('ul.pager li.previous'),
		$wBuybackvalidator = $("#wBuyback form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

	/*$wBuybackfinish.on('click', function( ev ) {
		ev.preventDefault();
		var validated = $('#wBuyback form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
	});*/

	
	$('#buyback_datepicker').on('changeDate', function(e) {
            $('#buyback_form').formValidation('revalidateField', 'date');
        });

	$('#wBuyback').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var numTabs    = $('#buyback_form').find('.tab-pane').length,
                isValidTab = validateBuybackTab(index - 1),
                check_edit_cert = true, check_cert_live = true;

            

            //console.log("isValidTab"+isValidTab);
            if (!isValidTab) {
                return false;
            }
            //console.log(numTabs);
            if(index === numTabs)
            {
            	$('#buyback_form').find('.edit_certificate_no').each(function() {
				   var elemt = $(this);
				   
				   if (elemt.val() != "") {
					   $.ajax({
			                type: "POST",
			                url: "masterclient/check_cert_no",
			                data: {"certificate_no":elemt.val(), "client_member_share_capital_id": $("#buyback_class").val(), "cert_id": elemt.parent().parent().parent().find(".cert_id").val()}, // <--- THIS IS THE CHANGE
			                async: false,
			                dataType: "json",
			                success: function(response){
			                    
			                    if(!response)
			                    {
				                    var errorsEditCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
					        		elemt.parent().parent().find( '.validate_edit_from_cert' ).html( errorsEditCert );
					        		check_edit_cert = response;
					        		
					        	}
					        	else
			                    {
			                    	elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
			                    	
			                    }
			                    
			                }               
			            });
					}
				   
				});

				var arr = [];
				$(".check_cert_in_live").each(function(){
					var elemt = $(this);
				    var value = $(this).val();
				    if(value != "")
				    {
				    	if (arr.indexOf(value) == -1)
					    {
					    	arr.push(value);
					    	elemt.parent().parent().find( '.validate_edit_from_cert_live' ).html(" ");
					    	//check_cert_live = true;
					    	//console.log(check_cert_live);
					    }				       
					    else
					    {
					    	elemt.parent().parent().find( '.validate_edit_from_cert' ).html(" ");
					    	
					    	elemt.parent().parent().find( '.validate_edit_from_cert_live' ).html(" ");
					    	var errorsEditCert = '<span style="display: block;font-size: 12px;margin-top: 5px;color:red" >*The certificate no. cannot duplicate in same class.</span>';
						    elemt.parent().parent().find( '.validate_edit_from_cert_live' ).html( errorsEditCert );
						    check_cert_live = false;
						    //console.log(check_cert_live);
					    }
				    }
				    
				    
				        
				});

				
            }
            

            

            // console.log(check_edit_cert);
            // console.log(check_cert_live);
	        if(!check_edit_cert)
	        {
	        	return false;
	        }
	        else if(!check_cert_live)
	        {
	        	return false;
	        }


            if (index === numTabs) {

                //console.log("success");
                $("#buyback_form").submit();

            }

            toastr.options = {
		      "positionClass": "toast-bottom-right"
		    }
            var bool_buyback_check_no_of_share = false;
            if($('#buyback_tab .active > a').attr('href')=="#buyback_number_shares")
			{
				/*console.log($('select[name="class"]').val());
				console.log($('input[name="company_code"]').val());*/
				/*console.log($("#total").text());
				console.log($("#total_to").text());*/
				var previous_total_number_of_shares = 0;
				var previous_shares_buyback = 0;
				var buyback_shares = 0;
				var buyback_share = $('input[name="buyback_share"]').val();
				

				
					if(buyback)
					{
						for(var t = 0; t < buyback.length; t++)
					    {
					        previous_total_number_of_shares += parseInt(buyback[t]["certificate_number_of_share"]); 
					        previous_shares_buyback += -(parseInt(buyback[t]["number_of_share"])); 
					    }
					    buyback_shares = ((previous_shares_buyback/previous_total_number_of_shares) * 100).toFixed(1);

					    if(first_time_calculation)
						{
						    if(buyback_share != buyback_shares)
							{
								changeShareNumber(buyback, buyback_share);
							}
							first_time_calculation = false;
						}
					}

					if(!buyback)
					{
						var client_member_share_capital_id = $('select[name="buyback_class"]').val();
						var company_code = $('input[name="company_code"]').val();
						var transaction_date = $('input[name="date"]').val();
						
						/*console.log(last_class_value);
						console.log(last_company_code);*/
						if(client_member_share_capital_id != last_buyback_class_value || company_code != last_buyback_company_code || buyback_share != last_buyback_share || transaction_date != last_transaction_date)
						{
							var alloment_people;
							$.ajax({
				                type: "POST",
				                url: "masterclient/get_buyback_people",
				                data: {"client_member_share_capital_id":client_member_share_capital_id, "company_code":company_code, "transaction_date":transaction_date}, // <--- THIS IS THE CHANGE
				                dataType: "json",
				                async: false,
				                success: function(response){
				                    //console.log(response);
				                    if(response != null)
				                    {
					                    alloment_people = response;
					                    
										assignAllomentPeople(alloment_people, buyback_share);
										bool_buyback_check_no_of_share = true;
										return true;
									}
									else
									{
										bool_buyback_check_no_of_share = false;
										toastr.error("The number of share cannot found before this date.", "Error");
										return false;
									}

									/*$("#total").text("");
									$("#total_to").text("");
									$('#table_to').hide();
							        $('.to').hide();
							        $('#total_share_to').hide();*/

									last_buyback_class_value = client_member_share_capital_id;
									last_buyback_company_code = company_code;
									last_buyback_share = buyback_share;
				                }               
				            });
						}
					}
					else
					{
						bool_buyback_check_no_of_share = true;
					}
			}

			
			if($('#buyback_tab .active > a').attr('href')=="#buyback_member")
			{
            	return true;
            }
           /* else
            {*/
            	if(bool_buyback_check_no_of_share)
            	{
            		return true;
            	}
            	else
            	{
            		return false;
            	}
            //}
            /*var sum = 0;
		    $(".transfer_group #share_buyback").each(function(){
		        //console.log($(this).val() == '');
		        if($(this).val() == '')
		        {
		            sum += 0;
		        }
		        else
		        {
		            sum += +parseInt(removeCommas($(this).val()));
		        }
		    });

            console.log(sum);
            console.log(removeCommas($("#total_share_buyback").text()));
            if(parseInt(removeCommas($("#total_share_buyback").text())) != null && sum != 0)
            {
            	if(parseInt(removeCommas(removeCommas($("#total_share_buyback").text()))) != sum)
				{
					//if($("#total").text() != $("#total_to").text())
					//{
						return false;

	                	//$("#validateTotal").text("The total is not same as above Total.");
					//}
				}
            }*/
			
            //return true;
            
		},
		onTabClick: function( tab, navigation, index, newindex ) {
			return validateBuybackTab(index);
		},
		onPrevious: function(tab, navigation, index) {
			return true;
            /*return validateTransferTab(index + 1);*/
        },
		onTabChange: function( tab, navigation, index, newindex ) {
			
			if(change_the_percentage)
            {
            	if(buyback)
				{
					var previous_total_number_of_shares = 0;
					var previous_shares_buyback = 0;
					for(var i = 0; i < buyback.length; i++)
				    {
				        previous_total_number_of_shares += parseInt(buyback[i]["certificate_number_of_share"]); 
				        previous_shares_buyback += -(parseInt(buyback[i]["number_of_share"])); 
				    }
				    previous_buyback_share = ((previous_shares_buyback/previous_total_number_of_shares) * 100).toFixed(2);
				}
				else
				{
	            	previous_buyback_share = parseFloat($('input[name="buyback_share"]').val()).toFixed(2);
	            	
	            }
	            change_the_percentage = false;

            }

			if($('#buyback_tab .active > a').attr('href')=="#buyback_confirm")
            {
            	//console.log(previous_buyback_share)
	            if(previous_buyback_share != "")
	            {
	            	if(previous_buyback_share != parseFloat($('input[name="buyback_share"]').val()).toFixed(2))
		            {
		            	bootbox.confirm("Buyback share (%) is changed from "+previous_buyback_share+"% to "+parseFloat($('input[name="buyback_share"]').val()).toFixed(2)+"%, do you want to proceed?", function (result) {
				            if (!result) {
				            	$('#wBuyback').bootstrapWizard('show', 1);
				            }
				            
				        });
		            }
	            }
	            
            }


			if($('#buyback_tab .active > a').attr('href')=="#buyback_confirm")
			{
				//$("#certificate_no").attr('disabled', false);
				if(access_right_member_module == "read" || client_status != "1" )
		        {
		            $(".share_buyback").attr("disabled", false);
		            $(".check_cert_in_live").attr("disabled", false);
		        }
			    buyback_object = $('form#buyback_form').serializeObject();
			   	confirmBuyback(buyback_object);
			}
			var totalTabs = navigation.find('li').size() - 1;

			$wBuybackprevious[ 0 >= newindex ? 'addClass' : 'removeClass' ]( 'hidden' );
		},
		onTabShow: function(tab, navigation, index) {

            // Update the label of Next button when we are at the last tab
            var numTabs = $('#buyback_form').find('.tab-pane').length;

            if(access_right_member_module == "read" || client_status != "1" )
			{	            
	            if(index === numTabs - 1)
	            {
	            	$(".next").hide();
	            	$(".other_next").show();
	            }
	            else
	            {
	            	$(".next").show();
	            	$(".other_next").hide();
	            }
	            
	        }
	        else
	        {
	        	$(".other_next").hide();
	        	
	        	$('#buyback_form')
                .find('ul.pager li.next')
                	.attr('type', 'submit')
                    .removeClass('disabled')    // Enable the Next button
                    .find('a')
                    .html(index === numTabs - 1 ? 'Save This Buyback' : 'Go To Next Page');
	        }

            
        }
	});

	function validateBuybackTab(index) {
		//console.log("index==============="+index);
        var fv   = $('#buyback_form').data('formValidation'), // FormValidation instance
            // The current tab
            $buyback_tab = $('#buyback_form').find('.tab-pane').eq(index);

        // Validate the container
        fv.validateContainer($buyback_tab);

        //console.log("tab==="+JSON.stringify($tab));
        //console.log("validateContainer==="+JSON.stringify(fv.validateContainer($tab)));

        var isValidBuybackStep = fv.isValidContainer($buyback_tab);
        //console.log(isValidBuybackStep);
        if (isValidBuybackStep === false || isValidBuybackStep === null) {
            // Do not jump to the target tab
            return false;
        }

        return true;
    }

    /*
	Wizard Transaction
	*/
	var $wTransactionfinish = $('#wTransaction').find('ul.pager li.finish'),
		$wTransactionprevious = $('#wTransaction').find('ul.pager li.previous');
		// $wTransactionvalidator = $("#modal_transaction form").validate({
		// 	highlight: function(element) {
		// 		$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		// 	},
		// 	success: function(element) {
		// 		$(element).closest('.form-group').removeClass('has-error');
		// 		$(element).remove();
		// 	},
		// 	errorPlacement: function( error, element ) {
		// 		element.parent().append( error );
		// 	}
		// });



	$wTransactionfinish.on('click', function( ev ) {
		ev.preventDefault();
		// var validated = $('#wTransaction form').valid();
		// if ( validated ) {
		// 	new PNotify({
		// 		title: 'Congratulations',
		// 		text: 'You completed the wizard form.',
		// 		type: 'custom',
		// 		addclass: 'notification-success',
		// 		icon: 'fa fa-check'
		// 	});
		// }
	});

	// var saveObjLocalStorage = function (key, obj) {
	//     if (window && window.localStorage) {
	//         try {
	//             window.localStorage.setItem(key, JSON.stringify(obj));
	//         } catch (ignore) {
	//         }
	//     }
	// };

	// var getLocalStorageObj = function (key, defaultObj) {
	//     if (window && window.localStorage) {
	//         try {
	//             var storedFields = window.localStorage.getItem(key),
	//                 obj;
	//             if (storedFields) {
	//                 obj = JSON.parse(storedFields);
	//                 return obj;
	//             }
	//         } catch (ignore) {
	//         }
	//     }
	//     return defaultObj;
	// };
	// // store the entries in this object
	// var entries = getLocalStorageObj('myWizard', {
	//   currentStep: 0
	// });
	toastr.options = {
      "positionClass": "toast-bottom-right"
    }
	$('#wTransaction').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			if($('#transaction_form').find(".transaction_task option:selected").val() == 1 || $('#transaction_form').find(".transaction_task option:selected").val() == 4 || $('#transaction_form').find(".transaction_task option:selected").val() == 5 || $('#transaction_form').find(".transaction_task option:selected").val() == 6 || $('#transaction_form').find(".transaction_task option:selected").val() == 7 || $('#transaction_form').find(".transaction_task option:selected").val() == 12 || $('#transaction_form').find(".transaction_task option:selected").val() == 15 || $('#transaction_form').find(".transaction_task option:selected").val() == 26 || $('#transaction_form').find(".transaction_task option:selected").val() == 33 || $('#transaction_form').find(".transaction_task option:selected").val() == 34 || $('#transaction_form').find(".transaction_task option:selected").val() == 31 || $('#transaction_form').find(".transaction_task option:selected").val() == 32 || $('#transaction_form').find(".transaction_task option:selected").val() == 34)
			{
				var numTabs = $('#transaction_form').find('.tab-pane').length;
			}
			else
			{
				var numTabs = $('#transaction_form').find('.tab-pane:hidden').length;
			}
			var isValidTab = validateTransactionTab(index);

            if (!isValidTab) {
                return false;
            }

			if (index === numTabs) 
			{
				if($('.transaction_task option:selected').val() == 1)
				{
					if($("#tran_status").val() == 3)
					{	
						$("#transaction_task").prop("disabled", false);
						$("#transaction_form").submit();
					}
					else
					{
						//officer
						for (var i = 0; i < $('#officer_form select[name="position[]"]').length; i++) 
						{
							if ($('#officer_form select[name="position[]"]')[i].value == 0) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#officer_form select[name="position[]"]')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							 }
						}

						for (var i = 0; i < $('#officer_form input[name="identification_register_no[]"]').length; i++) 
						{
							//console.log($('#officer_form input[name="identification_register_no[]"]')[i].value);
							if (!$('#officer_form input[name="identification_register_no[]"]')[i].value) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#officer_form input[name="identification_register_no[]"]')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						//member
						for (var i = 0; i < $('#member_form .id').length; i++) 
						{
							//console.log($('#member_form .id')[i].value);
							if (!$('#member_form .id')[i].value) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form .id')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						for (var i = 0; i < $('#member_form select[name="class[]"]').length; i++) 
						{
							if ($('#member_form select[name="class[]"]')[i].value == 0) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form select[name="class[]"]')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							 }
						}

						for (var i = 0; i < $('#member_form select[name="currency[]"]').length; i++) 
						{
							if ($('#member_form select[name="currency[]"]')[i].value == 0) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form select[name="currency[]"]')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							 }
						}

						for (var i = 0; i < $('#member_form .number_of_share').length; i++) 
						{
							//console.log($('#member_form .number_of_share')[i].value);
							if (!$('#member_form .number_of_share')[i].value) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form .number_of_share')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						for (var i = 0; i < $('#member_form .amount_share').length; i++) 
						{
							//console.log($('#member_form .amount_share')[i].value);
							if (!$('#member_form .amount_share')[i].value) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form .amount_share')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						for (var i = 0; i < $('#member_form .amount_paid').length; i++) 
						{
							//console.log($('#member_form .amount_paid')[i].value);
							if (!$('#member_form .amount_paid')[i].value) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form .amount_paid')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						for (var i = 0; i < $('#member_form .certificate').length; i++) 
						{
							//console.log($('#member_form .certificate')[i].value);
							if (!$('#member_form .certificate')[i].value) 
							{
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#member_form .certificate')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						//controller
						// for (var i = 0; i < $('#controller_form input[name="identification_register_no[]"]').length; i++) 
						// {
						// 	console.log($('#controller_form input[name="identification_register_no[]"]')[i].value);
						// 	if (!$('#controller_form input[name="identification_register_no[]"]')[i].value) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#controller_form input[name="identification_register_no[]"]')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	}
						// }

						//Billing
						// for (var i = 0; i < $('#billing_form .billing_service').length; i++) 
						// {
						// 	if ($('#billing_form .billing_service')[i].value == 0) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#billing_form .billing_service')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	 }
						// }

						// for (var i = 0; i < $('#billing_form .frequency').length; i++) 
						// {
						// 	if ($('#billing_form .frequency')[i].value == 0) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#billing_form .frequency')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	 }
						// }

						// for (var i = 0; i < $('#billing_form .invoice_description').length; i++) 
						// {
						// 	if (!$('#billing_form .invoice_description')[i].value) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#billing_form .invoice_description')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	 }
						// }

						// for (var i = 0; i < $('#billing_form .amount').length; i++) 
						// {
						// 	if (!$('#billing_form .amount')[i].value) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#billing_form .amount')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	 }
						// }

						// for (var i = 0; i < $('#billing_form .from_billing_cycle_datepicker').length; i++) 
						// {
						// 	if (!$('#billing_form .from_billing_cycle_datepicker')[i].value) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#billing_form .from_billing_cycle_datepicker')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	 }
						// }

						// for (var i = 0; i < $('#billing_form .to_billing_cycle_datepicker').length; i++) 
						// {
						// 	if (!$('#billing_form .to_billing_cycle_datepicker')[i].value) 
						// 	{
		    // 					$('[href="#transaction_data"]').tab('show');
		    // 					$('#billing_form .to_billing_cycle_datepicker')[i].focus();
		    // 					toastr.error("Please complete all required field", "Error");
						// 	 	return false;
						// 	 }
						// }

						//setup
						for (var i = 1; i < $('#setup_form .hidden_contact_phone').length - 1; i++) 
						{
							//console.log($('#setup_form .hidden_contact_phone').length);
							if (!$('#setup_form .hidden_contact_phone')[i].value && $('#setup_form .hidden_contact_phone').length > 1) 
							{
								//console.log($('#setup_form .hidden_contact_phone')[i].value);
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#setup_form .second_contact_phone')[i - 1].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						

						for (var i = 1; i < $('#setup_form .check_empty_contact_email ').length - 1; i++) 
						{
							//console.log($('#setup_form .check_empty_contact_email').length);
							if (!$('#setup_form .check_empty_contact_email')[i].value && $('#setup_form .check_empty_contact_email').length > 1) 
							{
								//console.log($('#setup_form .check_empty_contact_email')[i].value);
		    					$('[href="#transaction_data"]').tab('show');
		    					$('#setup_form .check_empty_contact_email')[i].focus();
		    					toastr.error("Please complete all required field", "Error");
							 	return false;
							}
						}

						if(!$('#upload_company_info #edit_company_name').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#upload_company_info #edit_company_name').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if($('#upload_company_info #company_type').val() == 0 && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#upload_company_info #company_type').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if(!$('#upload_company_info #activity1').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#upload_company_info #activity1').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if(!$('#upload_company_info #postal_code').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#upload_company_info #postal_code').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if(!$('#upload_company_info #street_name').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#upload_company_info #street_name').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if(!$('#filing_form #year_end_date').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#filing_form #year_end_date').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if($('#setup_form #chairman').val() == 0 && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#setup_form #chairman').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if($('#setup_form #director_signature_1').val() == 0 && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_data"]').tab('show');
							$('#setup_form #director_signature_1').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						// else if($('#setup_form #director_signature_2').val() == 0 && !$('#setup_form #director_signature_2').prop('disabled')) {
						// 	//alert('You must enter your activity1');
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#setup_form #director_signature_2').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						// else if(!$('#setup_form #contact_name').val()) {
						// 	//alert('You must enter your activity1');
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#setup_form #contact_name').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						// else if($('#setup_form .fieldGroup_contact_phone').length == 1 && !$('#setup_form #contact_phone').intlTelInput("getNumber", intlTelInputUtils.numberFormat.E164))
						// {
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#setup_form #contact_phone').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						// else if($('#setup_form .fieldGroup_contact_email').length == 1 && !$('#setup_form #contact_email').val())
						// {
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#setup_form #contact_email').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						// else if($("#pending_doc_body .update_button").length >= 1)
						// {
						// 	$('[href="#transaction_confirm"]').tab('show');
						// 	toastr.error("Please update the document.", "Error");
						// 	return false;
						// }
						else if(!$('#transaction_form #registration_no').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_completion"]').tab('show');
							$('#transaction_form #registration_no').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						// else if(!$('#previous_secretarial_form #previous_secretarial_company_name').val() && $('.transaction_task option:selected').val() == 28) {
						// 	//alert('You must enter your activity1');
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#previous_secretarial_form #previous_secretarial_company_name').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						// else if(!$('#previous_secretarial_form #previous_secretarial_postal_code').val() && $('.transaction_task option:selected').val() == 28) {
						// 	//alert('You must enter your activity1');
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#previous_secretarial_form #previous_secretarial_postal_code').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						// else if(!$('#previous_secretarial_form #previous_secretarial_street_name').val() && $('.transaction_task option:selected').val() == 28) {
						// 	//alert('You must enter your activity1');
						// 	$('[href="#transaction_data"]').tab('show');
						// 	$('#previous_secretarial_form #previous_secretarial_street_name').focus();
						// 	toastr.error("Please complete all required field", "Error");
						// 	return false;
						// }
						else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
						{
							$('[href="#transaction_completion"]').tab('show');
							$('.each_all_documents #add_all_document_file').focus();
							toastr.error("Please upload the document", "Error");
							return false;
						}
						else if(!$('#transaction_form #client_code').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_completion"]').tab('show');
							$('#transaction_form #client_code').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
							//alert('You must enter your activity1');
							$('[href="#transaction_completion"]').tab('show');
							$('#transaction_form .lodgement_date').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else if($('#transaction_form .tran_status').val() == 0) {
							//alert('You must enter your activity1');
							$('[href="#transaction_completion"]').tab('show');
							$('#transaction_form .tran_status').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						}
						else
						{
							submit_complete_transaction();
						}
					}
				}
				else if($('.transaction_task option:selected').val() == 2)
				{
					for (var i = 0; i < $('#apointment_of_director_form input[name="identification_register_no[]"]').length; i++) 
					{
						//console.log($('#apointment_of_director_form input[name="identification_register_no[]"]')[i].value);
						if (!$('#apointment_of_director_form input[name="identification_register_no[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_director_form input[name="identification_register_no[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					// if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					// else
					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					} 
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						$.ajax({
			                type: "POST",
			                url: "transaction/check_valid_officer",
			                data: $('#transaction_form').serialize(), // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    if(response.Status == 1)
			                    {
			      //               	$("#transaction_task").prop("disabled", false);
									// $("#transaction_form").submit();
									submit_complete_transaction();
			                    }
			                    else
			                    {
			                    	toastr.error(response.message, response.title);
			                    }
			                }
			            });
					}
				}
				else if($('.transaction_task option:selected').val() == 3)
				{
					for (var i = 0; i < $('#resign_of_director_form input[name="resign_date_of_cessation[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form input[name="resign_date_of_cessation[]"]')[i].value && !$('#resign_of_director_form input[name="resign_date_of_cessation[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form input[name="resign_date_of_cessation[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#resign_of_director_form textarea[name="resign_director_reason[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form textarea[name="resign_director_reason[]"]')[i].value && !$('#resign_of_director_form textarea[name="resign_director_reason[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form textarea[name="resign_director_reason[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#resign_of_director_form input[name="identification_register_no[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form input[name="identification_register_no[]"]')[i].value && $('#appoint_new_director').css('display') != 'none') 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form input[name="identification_register_no[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					// if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					// else 
					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 4)
				{
					if(!$("#change_of_reg_ofis_form #postal_code").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_reg_ofis_form #postal_code').focus();
						toastr.error("Please update the document.", "Error");
						return false;
					}
					else if(!$("#change_of_reg_ofis_form #street_name").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_reg_ofis_form #street_name').focus();
						toastr.error("Please update the document.", "Error");
						return false;
					}
					// else if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					else if(!$('#change_of_reg_ofis_form input[name="effective_date"]').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_reg_ofis_form input[name="effective_date"]').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 5)
				{
					if(!$("#change_of_biz_activity_form #new_activity1").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_biz_activity_form #new_activity1').focus();
						toastr.error("Please update the document.", "Error");
						return false;
					}
					// else if(!$("#change_of_biz_activity_form #new_activity2").val())
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#change_of_biz_activity_form #new_activity2').focus();
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					// else if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					else if(!$('#change_of_biz_activity_form input[name="effective_date"]').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_biz_activity_form input[name="effective_date"]').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 6)
				{
					if(!$("#change_of_FYE_form .new_FYE").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_FYE_form .new_FYE').focus();
						toastr.error("Please update the document.", "Error");
						return false;
					}
					// else if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					else if(!$('#change_of_FYE_form input[name="effective_date"]').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_FYE_form input[name="effective_date"]').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 7)
				{
					// if($('#body_appoint_new_auditor').find(".row_appoint_resign_auditor").length != 0)
					// {
					// 	if(!$('#apointment_of_auditor_form .notice_date').val()) {
					// 		//alert('You must enter your activity1');
					// 		$('[href="#transaction_data"]').tab('show');
					// 		$('#transaction_form .notice_date').focus();
					// 		toastr.error("Please complete all required field", "Error");
					// 		return false;
					// 	}
					// }

					for (var i = 0; i < $('#apointment_of_auditor_form input[name="identification_register_no[]"]').length; i++) 
					{
						if (!$('#apointment_of_auditor_form input[name="identification_register_no[]"]')[i].value && $('#appoint_new_director').css('display') != 'none') 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_auditor_form input[name="identification_register_no[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#apointment_of_auditor_form input[name="date_of_appointment[]"]').length; i++) 
					{
						if (!$('#apointment_of_auditor_form input[name="date_of_appointment[]"]')[i].value && !$('#apointment_of_auditor_form input[name="date_of_appointment[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_auditor_form input[name="date_of_appointment[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#apointment_of_auditor_form input[name="resign_date_of_cessation[]"]').length; i++) 
					{
						if (!$('#apointment_of_auditor_form input[name="resign_date_of_cessation[]"]')[i].value && !$('#apointment_of_auditor_form input[name="resign_date_of_cessation[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_auditor_form input[name="resign_date_of_cessation[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}


					for (var i = 0; i < $('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]').length; i++) 
					{
						if (!$('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]')[i].value && !$('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]').eq(i).is(':disabled')) 
						{
							$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
						else if($('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]')[i].value == 'OTHERS' && !$('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]')[i].value)
						{
							$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					// for (var i = 0; i < $('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]').length; i++) 
					// {
					// 	if (!$('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]')[i].value && !$('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]').eq(i).is(':disabled')) 
					// 	{
	    // 					$('[href="#transaction_data"]').tab('show');
	    // 					$('#apointment_of_auditor_form select[name="resign_auditor_reason_selection[]"]')[i].focus();
	    // 					toastr.error("Please complete all required field", "Error");
					// 	 	return false;
					// 	}
					// }

					// for (var i = 0; i < $('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]').length; i++) 
					// {
					// 	if (!$('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]')[i].value && !$('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]').eq(i).is(':disabled') && !$('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]').eq(i).is(':hidden')) 
					// 	{
	    // 					$('[href="#transaction_data"]').tab('show');
	    // 					$('#apointment_of_auditor_form textarea[name="resign_auditor_reason[]"]')[i].focus();
	    // 					toastr.error("Please complete all required field", "Error");
					// 	 	return false;
					// 	}
					// }

					if($("#tran_status").val() == 0)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form #tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) 
					{
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						$.ajax({
			                type: "POST",
			                url: "transaction/check_valid_officer",
			                data: $('#transaction_form').serialize(), // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    if(response.Status == 1)
			                    {
			      //               	$("#transaction_task").prop("disabled", false);
									// $("#transaction_form").submit();
									submit_complete_transaction();
			                    }
			                    else
			                    {
			                    	toastr.error(response.message, response.title);
			                    }
			                }
			            });
					}
				}
				else if($('.transaction_task option:selected').val() == 8)
				{	
					if ($('#issue_dividend_form select[name="currency"]').val() == 0 && $("#tran_status").val() == 2) 
					{
    					$('[href="#transaction_data"]').tab('show');
    					$('#issue_dividend_form select[name="currency"]').focus();
    					toastr.error("Please complete all required field", "Error");
					 	return false;
					}
					else if(!$("#issue_dividend_form .declare_of_fye").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#issue_dividend_form .declare_of_fye').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$("#issue_dividend_form .total_dividend_amount").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#issue_dividend_form .total_dividend_amount').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$("#issue_dividend_form .devidend_of_cut_off_date").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#issue_dividend_form .devidend_of_cut_off_date').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$("#issue_dividend_form .devidend_payment_date").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#issue_dividend_form .devidend_payment_date').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if ($('#issue_dividend_form select[name="nature"]').val() == 0 && $("#tran_status").val() == 2) 
					{
    					$('[href="#transaction_data"]').tab('show');
    					$('#issue_dividend_form select[name="nature"]').focus();
    					toastr.error("Please complete all required field", "Error");
					 	return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
					// else if(!$('#transaction_form .lodgement_date').val()) {
					// 	//alert('You must enter your activity1');
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	$('#transaction_form .lodgement_date').focus();
					// 	toastr.error("Please complete all required field", "Error");
					// 	return false;
					// }
					
				}
				else if($('.transaction_task option:selected').val() == 9)
				{	
					// for (var i = 0; i < $('#issue_director_fee_form select[name="currency[]"]').length; i++) 
					// {
					// 	if ($('#issue_director_fee_form select[name="currency[]"]')[i].value == 0) 
					// 	{
	    // 					$('[href="#transaction_data"]').tab('show');
	    // 					$('#issue_director_fee_form select[name="currency[]"]')[i].focus();
	    // 					toastr.error("Please complete all required field", "Error");
					// 	 	return false;
					// 	 }
					// }

					// for (var i = 0; i < $('#issue_director_fee_form input[name="director_fee[]"]').length; i++) 
					// {
					// 	if (!$('#issue_director_fee_form input[name="director_fee[]"]')[i].value) 
					// 	{
	    // 					$('[href="#transaction_data"]').tab('show');
	    // 					$('#issue_director_fee_form input[name="director_fee[]"]')[i].focus();
	    // 					toastr.error("Please complete all required field", "Error");
					// 	 	return false;
					// 	}
					// }
					if(!$("#issue_director_fee_form .declare_of_fye").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#issue_director_fee_form .declare_of_fye').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					// else if(!$("#issue_director_fee_form .resolution_date").val())
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#issue_director_fee_form .resolution_date').focus();
					// 	toastr.error("Please complete all required field.", "Error");
					// 	return false;
					// }
					// else if(!$("#issue_director_fee_form .meeting_date").val())
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#issue_director_fee_form .meeting_date').focus();
					// 	toastr.error("Please complete all required field.", "Error");
					// 	return false;
					// }
					else if(!$("#issue_director_fee_form .notice_date").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#issue_director_fee_form .notice_date').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
					// else if(!$('#transaction_form .lodgement_date').val()) {
					// 	//alert('You must enter your activity1');
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	$('#transaction_form .lodgement_date').focus();
					// 	toastr.error("Please complete all required field", "Error");
					// 	return false;
					// }
					
				}
				else if($('.transaction_task option:selected').val() == 10)
				{
					// if(!$("#share_transfer_form .director_meeting_date").val())
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#share_transfer_form #director_meeting_date').focus();
					// 	toastr.error("Please complete all required field.", "Error");
					// 	return false;
					// }
					// else if(!$('#share_transfer_form .director_meeting_time').val()) {
					// 	//alert('You must enter your activity1');
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#share_transfer_form .director_meeting_time').focus();
					// 	toastr.error("Please complete all required field", "Error");
					// 	return false;
					// }
					// else if(!$("#share_transfer_form .member_meeting_date").val())
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#share_transfer_form #member_meeting_date').focus();
					// 	toastr.error("Please complete all required field.", "Error");
					// 	return false;
					// }
					// else if(!$('#share_transfer_form .member_meeting_time').val()) {
					// 	//alert('You must enter your activity1');
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#share_transfer_form .member_meeting_time').focus();
					// 	toastr.error("Please complete all required field", "Error");
					// 	return false;
					// }
					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						$.ajax({
			                type: "POST",
			                url: "transaction/get_transaction_share_transfer_record",
			                data: {"transaction_master_id":$('#transaction_master_id').val()}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	if(response.status == 1)
			                	{
									// $("#transaction_task").prop("disabled", false);
									// $("#transaction_form").submit();
									submit_complete_transaction();
								}
								else
								{
									toastr.error("Please save the Transferor Record and Transferee Record.", "Error");
								}
							}
						});
					}
				}

				else if($('.transaction_task option:selected').val() == 11)
				{
					if(!$("#share_allotment_form .director_meeting_date").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#share_allotment_form .director_meeting_date').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$('#share_allotment_form .director_meeting_time').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_data"]').tab('show');
						$('#share_allotment_form .director_meeting_time').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if(!$("#share_allotment_form .member_meeting_date").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#share_allotment_form .member_meeting_date').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$('#share_allotment_form .member_meeting_time').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_data"]').tab('show');
						$('#share_allotment_form .member_meeting_time').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}

					//member
					for (var i = 0; i < $('#share_allotment_form .id').length; i++) 
					{
						//console.log($('#share_allotment_form .id')[i].value);
						if (!$('#share_allotment_form .id')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form .id')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#share_allotment_form select[name="class[]"]').length; i++) 
					{
						if ($('#share_allotment_form select[name="class[]"]')[i].value == 0) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form select[name="class[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						 }
					}

					for (var i = 0; i < $('#share_allotment_form select[name="currency[]"]').length; i++) 
					{
						if ($('#share_allotment_form select[name="currency[]"]')[i].value == 0) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form select[name="currency[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						 }
					}

					for (var i = 0; i < $('#share_allotment_form .number_of_share').length; i++) 
					{
						//console.log($('#share_allotment_form .number_of_share')[i].value);
						if (!$('#share_allotment_form .number_of_share')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form .number_of_share')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#share_allotment_form .amount_share').length; i++) 
					{
						//console.log($('#share_allotment_form .amount_share')[i].value);
						if (!$('#share_allotment_form .amount_share')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form .amount_share')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#share_allotment_form .amount_paid').length; i++) 
					{
						//console.log($('#share_allotment_form .amount_paid')[i].value);
						if (!$('#share_allotment_form .amount_paid')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form .amount_paid')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#share_allotment_form .certificate').length; i++) 
					{
						//console.log($('#share_allotment_form .certificate')[i].value);
						if (!$('#share_allotment_form .certificate')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#share_allotment_form .certificate')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					// if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					// else 
					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 12)
				{
					if(!$("#change_of_company_name_form #new_company_name").val() && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_company_name_form #new_company_name').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					// else if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					else if(!$('#change_of_company_name_form input[name="effective_date"]').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_data"]').tab('show');
						$('#change_of_company_name_form input[name="effective_date"]').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 15)
				{
					//console.log($('#notice_form .date_fs_section').css('display') == 'none');
					//$('#agm_ar_form #agm_date').focus();
					if(!$("#notice_form .agm_date_info").val() && !($('#notice_form .agm_date_section').css('display') == 'none') && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#notice_form .agm_date_info').focus();
						toastr.error("Please complete AGM Date field.", "Error");
						return false;
					}
					else if(!$("#notice_form .date_fs_sent_to_member").val() && !($('#notice_form .date_fs_section').css('display') == 'none') && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#notice_form .date_fs_sent_to_member').focus();
						toastr.error("Please complete Date FS sent to members field.", "Error");
						return false;
					}
					// else if(!$("#agm_ar_form .reso_date").val() && $("#tran_status").val() == 2)
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#agm_ar_form .reso_date').focus();
					// 	toastr.error("Please complete Resolution Date field.", "Error");
					// 	return false;
					// }
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 20)
				{
					for (var i = 0; i < $('#incorp_subsidiary_form input[name="subsidiary_name[]"]').length; i++) 
					{
						if (!$('#incorp_subsidiary_form input[name="subsidiary_name[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#incorp_subsidiary_form input[name="subsidiary_name[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#incorp_subsidiary_form input[name="corp_rep_name[]"]').length; i++) 
					{
						if (!$('#incorp_subsidiary_form input[name="corp_rep_name[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#incorp_subsidiary_form input[name="corp_rep_name[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#incorp_subsidiary_form input[name="corp_rep_identity_number[]"]').length; i++) 
					{
						if (!$('#incorp_subsidiary_form input[name="corp_rep_identity_number[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#incorp_subsidiary_form input[name="corp_rep_identity_number[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#incorp_subsidiary_form input[name="corp_rep_date_of_appointment[]"]').length; i++) 
					{
						if (!$('#incorp_subsidiary_form input[name="corp_rep_date_of_appointment[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#incorp_subsidiary_form input[name="corp_rep_date_of_appointment[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val()) {
						//alert('You must enter your activity1');
						$('[href="#transaction_confirm"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 24)
				{
					for (var i = 0; i < $('#apointment_of_secretarial_form input[name="identification_register_no[]"]').length; i++) 
					{
						//console.log($('#apointment_of_secretarial_form input[name="identification_register_no[]"]')[i].value);
						if (!$('#apointment_of_secretarial_form input[name="identification_register_no[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_secretarial_form input[name="identification_register_no[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					// if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					// else 
					if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_confirm"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						$.ajax({
			                type: "POST",
			                url: "transaction/check_valid_officer",
			                data: $('#transaction_form').serialize(), // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    if(response.Status == 1)
			                    {
			      //               	$("#transaction_task").prop("disabled", false);
									// $("#transaction_form").submit();
									submit_complete_transaction();
			                    }
			                    else
			                    {
			                    	toastr.error(response.message, response.title);
			                    }
			                }
			            });
			            
					}
				}
				else if($('.transaction_task option:selected').val() == 26)
				{
					// if ($('#strike_off_form select[name="reason_for_appication"]').val() == 0) 
					// {
    	// 				$('[href="#transaction_data"]').tab('show');
    	// 				$('#strike_off_form select[name="reason_for_appication"]').focus();
    	// 				toastr.error("Please complete all required field", "Error");
					//  	return false;
					// }
					// else if (!$('#strike_off_form input[name="ceased_date"]').val() && !$('.ceased_date').prop('disabled')) 
					// {
    	// 				$('[href="#transaction_data"]').tab('show');
    	// 				$('#strike_off_form input[name="ceased_date"]').focus();
    	// 				toastr.error("Please complete all required field", "Error");
					//  	return false;
					// }
					// else if(!$('#transaction_form .lodgement_date').val()) {
					// 	//alert('You must enter your activity1');
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	$('#transaction_form .lodgement_date').focus();
					// 	toastr.error("Please complete all required field", "Error");
					// 	return false;
					// }
					// else if ($('#transaction_form .status_of_the_company').val() == 0) 
					// {
    	// 				$('[href="#transaction_confirm"]').tab('show');
    	// 				$('#transaction_form .status_of_the_company').focus();
    	// 				toastr.error("Please complete all required field", "Error");
					//  	return false;
					// }
					// else
					// {
					// 	submit_complete_transaction();
					// }



					if(!$("#notice_form .agm_date_info").val() && !($('#notice_form .agm_date_section').css('display') == 'none') && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_data"]').tab('show');
						$('#notice_form .agm_date_info').focus();
						toastr.error("Please complete AGM Date field.", "Error");
						return false;
					}
					// else if(!$("#notice_form .date_fs_sent_to_member").val() && !($('#notice_form .date_fs_section').css('display') == 'none') && $("#tran_status").val() == 2)
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#notice_form .date_fs_sent_to_member').focus();
					// 	toastr.error("Please complete Date FS sent to members field.", "Error");
					// 	return false;
					// }
					if ($('#strike_off_form select[name="reason_for_appication"]').val() == 0) 
					{
    					$('[href="#transaction_data"]').tab('show');
    					$('#strike_off_form select[name="reason_for_appication"]').focus();
    					toastr.error("Please complete all required field", "Error");
					 	return false;
					}
					else if (!$('#strike_off_form input[name="ceased_date"]').val() && !$('.ceased_date').prop('disabled')) 
					{
    					$('[href="#transaction_data"]').tab('show');
    					$('#strike_off_form input[name="ceased_date"]').focus();
    					toastr.error("Please complete all required field", "Error");
					 	return false;
					}
					// else if(!$("#agm_ar_form .reso_date").val() && $("#tran_status").val() == 2)
					// {
					// 	$('[href="#transaction_data"]').tab('show');
					// 	$('#agm_ar_form .reso_date').focus();
					// 	toastr.error("Please complete Resolution Date field.", "Error");
					// 	return false;
					// }
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 29)
				{
					//console.log($(".each_all_documents #add_all_document_file").is(":visible"));
					if(!$("#service_proposal_form .proposal_date").val())
					{
						$('[href="#transaction_data"]').tab('show');
						$('#service_proposal_form .proposal_date').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$("#service_proposal_form #activity1").val())
					{
						$('[href="#transaction_data"]').tab('show');
						$('#service_proposal_form #activity1').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$("#service_proposal_form #postal_code").val())
					{
						$('[href="#transaction_data"]').tab('show');
						$('#service_proposal_form #postal_code').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if(!$("#service_proposal_form #street_name").val())
					{
						$('[href="#transaction_data"]').tab('show');
						$('#service_proposal_form #street_name').focus();
						toastr.error("Please complete all required field.", "Error");
						return false;
					}
					else if($(".each_all_documents #add_all_document_file").is(":visible") && $('#transaction_form .tran_status').val() == 3)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('.accept_date .lodgement_date').val() && $('#transaction_form .tran_status').val() == 3) {
						//alert('You must enter your activity1');
						// if($('#transaction_form .tran_status').val() != 1)
						// {
							// console.log($('#transaction_form .lodgement_date').val());
							// console.log($('#transaction_form .tran_status').val());
							$('[href="#transaction_completion"]').tab('show');
							$('#transaction_form .lodgement_date').focus();
							toastr.error("Please complete all required field", "Error");
							return false;
						//}
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 30)
				{
					if($(".each_all_documents #add_all_document_file").is(":visible") && $('#transaction_form .tran_status').val() == 3)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('.accept_date .lodgement_date').val() && $('#transaction_form .tran_status').val() == 3) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 31)
				{
					// $("#transaction_task").prop("disabled", false);
					// $("#transaction_form").submit();
					// submit_complete_transaction();

					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .date_of_entry_or_update').val() && $("#tran_status").val() == 2) 
					{
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .date_of_entry_or_update').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) 
					{
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 32)
				{
					// $("#transaction_task").prop("disabled", false);
					// $("#transaction_form").submit();
					// submit_complete_transaction();
					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .date_of_entry_or_update').val() && $("#tran_status").val() == 2) 
					{
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .date_of_entry_or_update').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) 
					{
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 33)
				{
					// $("#transaction_form").submit();

					for (var i = 0; i < $('#resign_of_director_form input[name="resign_date_of_cessation[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form input[name="resign_date_of_cessation[]"]')[i].value && !$('#resign_of_director_form input[name="resign_date_of_cessation[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form input[name="resign_date_of_cessation[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#resign_of_director_form select[name="resign_director_reason_selection[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form select[name="resign_director_reason_selection[]"]')[i].value && !$('#resign_of_director_form select[name="resign_director_reason_selection[]"]').eq(i).is(':disabled')) 
						{
							$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form select[name="resign_director_reason_selection[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
						else if($('#resign_of_director_form select[name="resign_director_reason_selection[]"]')[i].value == 'OTHERS' && !$('#resign_of_director_form textarea[name="resign_director_reason[]"]')[i].value)
						{
							$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form textarea[name="resign_director_reason[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#resign_of_director_form input[name="identification_register_no[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form input[name="identification_register_no[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form input[name="identification_register_no[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#resign_of_director_form input[name="date_of_appointment[]"]').length; i++) 
					{
						if (!$('#resign_of_director_form input[name="date_of_appointment[]"]')[i].value) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#resign_of_director_form input[name="date_of_appointment[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if($('#transaction_form .tran_status').val() == 0) {
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						// $("#transaction_task").prop("disabled", false);
						// $("#transaction_form").submit();
						submit_complete_transaction();
					}
				}
				else if($('.transaction_task option:selected').val() == 34)
				{
					// if($('#body_appoint_new_auditor').find(".row_appoint_resign_auditor").length != 0)
					// {
					// 	if(!$('#apointment_of_auditor_form .notice_date').val()) {
					// 		//alert('You must enter your activity1');
					// 		$('[href="#transaction_data"]').tab('show');
					// 		$('#transaction_form .notice_date').focus();
					// 		toastr.error("Please complete all required field", "Error");
					// 		return false;
					// 	}
					// }

					for (var i = 0; i < $('#apointment_of_secretarial_form input[name="identification_register_no[]"]').length; i++) 
					{
						if (!$('#apointment_of_secretarial_form input[name="identification_register_no[]"]')[i].value && $('#appoint_new_director').css('display') != 'none') 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_secretarial_form input[name="identification_register_no[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#apointment_of_secretarial_form input[name="date_of_appointment[]"]').length; i++) 
					{
						if (!$('#apointment_of_secretarial_form input[name="date_of_appointment[]"]')[i].value && !$('#apointment_of_secretarial_form input[name="date_of_appointment[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_secretarial_form input[name="date_of_appointment[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#apointment_of_secretarial_form input[name="resign_date_of_cessation[]"]').length; i++) 
					{
						if (!$('#apointment_of_secretarial_form input[name="resign_date_of_cessation[]"]')[i].value && !$('#apointment_of_secretarial_form input[name="resign_date_of_cessation[]"]').eq(i).is(':disabled')) 
						{
	    					$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_secretarial_form input[name="resign_date_of_cessation[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					for (var i = 0; i < $('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]').length; i++) 
					{
						if (!$('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]')[i].value && !$('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]').eq(i).is(':disabled')) 
						{
							$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
						else if($('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]')[i].value == 'OTHERS' && !$('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]')[i].value)
						{
							$('[href="#transaction_data"]').tab('show');
	    					$('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]')[i].focus();
	    					toastr.error("Please complete all required field", "Error");
						 	return false;
						}
					}

					// for (var i = 0; i < $('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]').length; i++) 
					// {
					// 	if (!$('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]')[i].value && !$('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]').eq(i).is(':disabled')) 
					// 	{
	    // 					$('[href="#transaction_data"]').tab('show');
	    // 					$('#apointment_of_secretarial_form select[name="resign_secretarial_reason_selection[]"]')[i].focus();
	    // 					toastr.error("Please complete all required field", "Error");
					// 	 	return false;
					// 	}
					// }

					// for (var i = 0; i < $('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]').length; i++) 
					// {
					// 	if (!$('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]')[i].value && !$('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]').eq(i).is(':disabled') && !$('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]').eq(i).is(':hidden')) 
					// 	{
	    // 					$('[href="#transaction_data"]').tab('show');
	    // 					$('#apointment_of_secretarial_form textarea[name="resign_secretarial_reason[]"]')[i].focus();
	    // 					toastr.error("Please complete all required field", "Error");
					// 	 	return false;
					// 	}
					// }

					// if($("#pending_doc_body .update_button").length >= 1)
					// {
					// 	$('[href="#transaction_confirm"]').tab('show');
					// 	toastr.error("Please update the document.", "Error");
					// 	return false;
					// }
					// else 
					if($(".each_all_documents #add_all_document_file").is(":visible") && $("#tran_status").val() == 2)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('.each_all_documents #add_all_document_file').focus();
						toastr.error("Please upload the document", "Error");
						return false;
					}
					else if($("#tran_status").val() == 0)
					{
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form #tran_status').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else if(!$('#transaction_form .lodgement_date').val() && $("#tran_status").val() == 2) 
					{
						//alert('You must enter your activity1');
						$('[href="#transaction_completion"]').tab('show');
						$('#transaction_form .lodgement_date').focus();
						toastr.error("Please complete all required field", "Error");
						return false;
					}
					else
					{
						$.ajax({
			                type: "POST",
			                url: "transaction/check_valid_officer",
			                data: $('#transaction_form').serialize(), // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    if(response.Status == 1)
			                    {
			      //               	$("#transaction_task").prop("disabled", false);
									// $("#transaction_form").submit();
									submit_complete_transaction();
			                    }
			                    else
			                    {
			                    	toastr.error(response.message, response.title);
			                    }
			                }
			            });
					}
				}
				else if($('.transaction_task option:selected').val() == 35)
				{
					submit_complete_transaction();
				}
			}
		},
		onTabClick: function( tab, navigation, index, newindex ) {
			return validateTransactionTab(index + 1);
			// if(index == 0)
		 //    {
		 //    	if($('.transaction_task option:selected').val() == 2)
			// 	{
			// 		if(!$('#uen').val()) {
			// 			//alert('You must enter your activity1');
			// 			//$('[href="#transaction_data"]').tab('show');
			// 			$('#uen').focus();
			// 			toastr.error("Please fill in the Registration No field", "Error");
			// 			return false;
			// 		}
			// 	}
		 //    }
		},
		onPrevious: function(tab, navigation, index) {
			return true;
           // return validateAllotmentTab(index + 1);
        },
		onTabChange: function( tab, navigation, index, newindex ) {
			// var totalTabs = navigation.find('li').size() - 1;
			// $wTransactionfinish[ newindex != totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
			// $('#w2').find(this.nextSelector)[ newindex == totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
			if($('#transaction_tab .active > a').attr('href')=="#transaction_data")
            {
				//if client condition
				if(user_base_client)
				{
					$("[name='director_retiring_checkbox']").bootstrapSwitch('disabled',true);
					$('#transaction_data button').prop('disabled', true);
					$('#transaction_data input').prop('disabled', true);
					$('#transaction_data select').prop('disabled', true);
				}
			}

			if($('#transaction_tab .active > a').attr('href')=="#transaction_document")
            {
            	if($('.transaction_task option:selected').val() == 1 || $('.transaction_task option:selected').val() == 2 || $('.transaction_task option:selected').val() == 3 || $('.transaction_task option:selected').val() == 4 || $('.transaction_task option:selected').val() == 5 || $('.transaction_task option:selected').val() == 6 || $('.transaction_task option:selected').val() == 7 || $('.transaction_task option:selected').val() == 8 || $('.transaction_task option:selected').val() == 9 || $('.transaction_task option:selected').val() == 10 || $('.transaction_task option:selected').val() == 11 || $('.transaction_task option:selected').val() == 12 || $('.transaction_task option:selected').val() == 14 || $('.transaction_task option:selected').val() == 15  || $('.transaction_task option:selected').val() == 20 || $('.transaction_task option:selected').val() == 24 || $('.transaction_task option:selected').val() == 26 || $('.transaction_task option:selected').val() == 28 || $('.transaction_task option:selected').val() == 29 || $('.transaction_task option:selected').val() == 30 || $('.transaction_task option:selected').val() == 31 || $('.transaction_task option:selected').val() == 32 || $('.transaction_task option:selected').val() == 33 || $('.transaction_task option:selected').val() == 34 || $('.transaction_task option:selected').val() == 35)
            	{
            		// if($('.transaction_task option:selected').val() == 3 && $('#appoint_new_director').css('display') != 'none')
            		// {
            		// 	$.ajax({
			           //      type: "POST",
			           //      url: "transaction/get_latest_document",
			           //      data: {"transaction_task_id":$('.transaction_task option:selected').val(), "company_code":transaction_company_code, "second_transaction_task_id": 2, "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
			           //      dataType: "json",
			           //      async: false,
			           //      success: function(response){
			           //          // console.log(response[0]["document"]);
			           //          documentInterface(response[0]["document"]);
			           //          documentCategoryList(response[0]["document_categoty_list"]);
			           //      }
			           //  });
            		// }
            		// else 
            		if($('.transaction_task option:selected').val() == 7 && $('#body_appoint_new_auditor').find(".row_appoint_resign_auditor").length == 0)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":71, "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 7 && $('#body_appoint_new_auditor').find(".row_appoint_resign_auditor").length != 0 && $('#body_resign_auditor').find(".row_resign_auditor").length != 0)
            		{	
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":7, "company_code":transaction_company_code, "second_transaction_task_id": 72, "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 34 && $('#body_appoint_new_secretarial').find(".row_appoint_new_secretarial").length == 0)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":341, "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 34 && $('#body_appoint_new_secretarial').find(".row_appoint_new_secretarial").length != 0 && $('#body_resign_secretarial').find(".cancel_withdraw_secretarial").length != 0)
            		{	
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":34, "company_code":transaction_company_code, "second_transaction_task_id": 341, "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 30)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":$('.transaction_task option:selected').val(), "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": $('input[name="hidden_selected_el_id[]"]').map(function(){return $(this).val();}).get(), "transaction_master_id": $("#transaction_trans #transaction_master_id").val()}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    //console.log(response[0]["document"]);
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
		            }
            		else if($('.transaction_task option:selected').val() == 15)
		            {
		            	$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":$('.transaction_task option:selected').val(), "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": "", "transaction_master_id": $("#transaction_trans #transaction_master_id").val(), "audited_fs": $("#audited_fs").val(), "activity_status": $("#activity_status").val(), "shorter_notice": $("#shorter_notice").val(), "require_hold_agm_list": $("#require_hold_agm_list").val()}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
		            }
		            else if($('.transaction_task option:selected').val() == 33)
            		{
            			var transaction_task = $('.transaction_task option:selected').val();
            			var response_document = [];
            			var response_document_categoty_list = [];

            			if($('#body_appoint_new_director').find(".row_appoint_new_director").length != 0)
            			{
            				$.ajax({
				                type: "POST",
				                url: "transaction/get_latest_document",
				                data: {"transaction_task_id":2, "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
				                dataType: "json",
				                async: false,
				                success: function(response)
				                {
				                    if(response[0]["document"].length != 0)
				                    {
				                    	for(var a = 0; a < response[0]["document"].length; a++)
				                    	{
				                    		response_document.push(response[0]["document"][a]);
				                    	}
				                    }

				                    if(response[0]["document_categoty_list"].length != 0)
				                    {
				                    	for(var a = 0; a < response[0]["document_categoty_list"].length; a++)
				                    	{
				                    		response_document_categoty_list.push(response[0]["document_categoty_list"][a]);
				                    	}
				                    }
				                }
				            });
            			}
            			if($('#body_resign_director').find(".cancel_withdraw_director").length != 0)
            			{
            				$.ajax({
				                type: "POST",
				                url: "transaction/get_latest_document",
				                data: {"transaction_task_id":3, "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
				                dataType: "json",
				                async: false,
				                success: function(response)
				                {
				                    if(response[0]["document"].length != 0)
				                    {
				                    	for(var a = 0; a < response[0]["document"].length; a++)
				                    	{
				                    		response_document.push(response[0]["document"][a]);
				                    	}
				                    }

				                    if(response[0]["document_categoty_list"].length != 0)
				                    {
				                    	for(var a = 0; a < response[0]["document_categoty_list"].length; a++)
				                    	{
				                    		response_document_categoty_list.push(response[0]["document_categoty_list"][a]);
				                    	}
				                    }
				                }
				            });
            			}

            			$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":transaction_task, "company_code":transaction_company_code, "second_transaction_task_id": 2, "hidden_selected_el_id": ""}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    if(response[0]["document"].length != 0)
			                    {
			                    	for(var a = 0; a < response[0]["document"].length; a++)
			                    	{
			                    		response_document.push(response[0]["document"][a]);
			                    	}
			                    }

			                    if(response[0]["document_categoty_list"].length != 0)
			                    {
			                    	for(var a = 0; a < response[0]["document_categoty_list"].length; a++)
			                    	{
			                    		response_document_categoty_list.push(response[0]["document_categoty_list"][a]);
			                    	}
			                    }
			                }
			            });

            			// REMOVE DUPLICATE
			            var non_duplicate_list = [];

						response_document_categoty_list.forEach(function(a,b){

							if(non_duplicate_list.length == 0)
							{
								non_duplicate_list.push(a);
							}
							else
							{
								non_duplicate_list.forEach(function(c,d){

									if(!JSON.stringify(non_duplicate_list).includes(JSON.stringify(a)))
									{
										non_duplicate_list.push(a);
									}
								});
							}
						});

						response_document_categoty_list = non_duplicate_list;
						// END REMOVE DUPLICATE

			            documentInterface(response_document);
				        documentCategoryList(response_document_categoty_list);
            		}
            		else
            		{
		            	$.ajax({
			                type: "POST",
			                url: "transaction/get_latest_document",
			                data: {"transaction_task_id":$('.transaction_task option:selected').val(), "company_code":transaction_company_code, "second_transaction_task_id": "", "hidden_selected_el_id": "", "transaction_master_id": $("#transaction_trans #transaction_master_id").val()}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    documentInterface(response[0]["document"]);
			                    documentCategoryList(response[0]["document_categoty_list"]);
			                }
			            });
		            }
		        }
		    }

		    if($('#transaction_tab .active > a').attr('href')=="#transaction_completion")
            {
	            var hold_client_code = $("#client_code").val();
	            var hold_registration_no = $("#registration_no").val();
	            var hold_lodgement_date = $(".lodgement_date").val();
	            var hold_tran_status = $("#tran_status").val();

	            var hold_radio_confirm_registrable_controller = $("#radio_confirm_registrable_controller").val();
	            var hold_date_of_the_conf_received = $(".date_of_the_conf_received").val();
	            var hold_date_of_entry_or_update = $(".date_of_entry_or_update").val();

	            $.ajax({
	            	type: "POST",
	            	url: "transaction/get_status_and_follow_up_detail",
		            data: {"transaction_task_id":$('.transaction_task option:selected').val(), "transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
	            	dataType: "json",
	            	async: false,
	            	success: function(response){
	            		follow_up_history_data = response[0]['follow_up_history'];
		            	var follow_up_history = response[0]['follow_up_history'];
		            	var all_documents = response[0]['all_documents'];

		            	if(response[0]['transaction_client'] != false)
		            	{
		            		if(response[0]['transaction_client'][0]['client_code'] != null && response[0]['transaction_client'][0]['client_code'] != "")
					        {	
					        	$("#client_code").val(response[0]['transaction_client'][0]['client_code']);
					        }
					        else if(hold_client_code != "")
					        {
					        	$("#client_code").val(hold_client_code);
					        }
					        else
					        {
					        	$("#client_code").val(response[0]['latest_client_code']);
					        }
		            		
		            		if(response[0]['transaction_client'][0]['registration_no'] != null && response[0]['transaction_client'][0]['registration_no'] != "")
					        {
					        	$("#registration_no").val(response[0]['transaction_client'][0]['registration_no']);
					        }
					        else
					        {
					        	$("#registration_no").val(hold_registration_no);
					        }
				        }
				        if(response[0]['lodgement_date'] != "")
				        {
				        	$(".lodgement_date").val(response[0]['lodgement_date']);
				        }
				        else
				        {
				        	$(".lodgement_date").val(hold_lodgement_date);
				        }
				        if(response[0]['date_of_the_conf_received'] != null)
				        {
				        	$(".date_of_the_conf_received").val(response[0]['date_of_the_conf_received']);
				        }
				        else
				        {
				        	$(".date_of_the_conf_received").val(hold_date_of_the_conf_received);
				        }
				        if(response[0]['date_of_entry_or_update'] != null)
				        {
				        	$(".date_of_entry_or_update").val(response[0]['date_of_entry_or_update']);
				        }
				        else
				        {
				        	$(".date_of_entry_or_update").val(hold_date_of_entry_or_update);
				        }
		            	$(".each_follow_up_history").remove();
		            	if(response[0]["follow_up_history"] != false)
		            	{
					        for(var i = 0; i < follow_up_history.length; i++)
					        {
					            var c = ""; 
					            c += '<tr class="each_follow_up_history">';
					            c += '<td class="hidden"><input type="text" class="form-control" name="each_follow_up_history_id" id="each_follow_up_history_id" value="'+follow_up_history[i]["id"]+'"/></td>';
					            c += '<td style="text-align: center"><a href="javascript:void(0)" class="edit_follow_up_history" data-id="'+follow_up_history[i]["id"]+'" data-follow_up_info="'+follow_up_history[i]+'" id="edit_follow_up_history">'+follow_up_history[i]["follow_up_id"]+'</a></td>';
					            c += '<td style="text-align: center">' + follow_up_history[i]["date_of_follow_up"] +" "+ follow_up_history[i]["time_of_follow_up"] +'</td>';
					            c += '<td style="text-align: center">' + follow_up_history[i]["next_follow_up_date"] +" "+ follow_up_history[i]["next_follow_up_time"] +'</td>';
					            c += '<td>'+follow_up_history[i]["first_name"]+'</td>';
					            c += '<td><button type="button" class="btn btn-primary delete_follow_up_history_button" onclick="delete_follow_up_history(this)">Delete</button></td>';
					            c += '</tr>';

					            $("#follow_up_table").append(c);
					        }
					    }

		            	$("#follow_up_outcome option").remove();
		                var select_follow_up_outcome_option = $('<option />');
		                select_follow_up_outcome_option.attr('value', '0').text("Select Outcome");
		                $("#follow_up_outcome").append(select_follow_up_outcome_option);
		                for(var i = 0; i < response[0]['follow_up_outcome'].length; i++) {
		                    var option = $('<option />');
		                    option.attr('value', response[0]['follow_up_outcome'][i]["id"]).text(response[0]['follow_up_outcome'][i]["outcome"]);
		                    if(response[0]["follow_up_outcome_id"] != 0 && response[0]['follow_up_outcome'][i]["id"] == response[0]["follow_up_outcome_id"])
		                    {
		                        option.attr('selected', 'selected');
		                        //$("#transaction_task").prop("disabled", true);
		                        //$("#uen").prop("readonly", true);
		                    }				                    
		                   
		                    $("#follow_up_outcome").append(option);
		                };

		                $("#follow_up_action option").remove();
		                var select_follow_up_outcome_option = $('<option />');
		                select_follow_up_outcome_option.attr('value', '0').text("Select Action");
		                $("#follow_up_action").append(select_follow_up_outcome_option);
		                for(var i = 0; i < response[0]['follow_up_action'].length; i++) {
		                    var option = $('<option />');
		                    option.attr('value', response[0]['follow_up_action'][i]["id"]).text(response[0]['follow_up_action'][i]["action"]);
		                    if(response[0]["follow_up_action_id"] != 0 && response[0]['follow_up_action'][i]["id"] == response[0]["follow_up_action_id"])
		                    {
		                        option.attr('selected', 'selected');
		                        //$("#transaction_task").prop("disabled", true);
		                        //$("#uen").prop("readonly", true);
		                    }				                    
		                   
		                    $("#follow_up_action").append(option);
		                };

		            	if(all_documents != false)
		            	{
		            		create_upload_document_list(all_documents); 
					    }

						$("#tran_status option").remove();
		                var select_option = $('<option />');
		                select_option.attr('value', '0').text("Select Status");
		                $("#tran_status").append(select_option);
		                if($('.transaction_task option:selected').val() == 29)
		                {
		                	for(var i = 0; i < response[0]['transaction_service_proposal_status'].length; i++) 
			                {
			                    var option = $('<option />');
			                    if(response[0]['transaction_service_proposal_status'][i]["id"] == 2)
			                    {
			                    	option.attr('style',"display:none");
			                    }
			                    
			                    option.attr('value', response[0]['transaction_service_proposal_status'][i]["id"]).text(response[0]['transaction_service_proposal_status'][i]["service_proposal_status"]);
			                    if(response[0]["service_status"] != 0 && response[0]['transaction_service_proposal_status'][i]["id"] == response[0]["service_status"])
			                    {
			                    	if(response[0]['transaction_service_proposal_status'][i]["id"] == 2)
				                    {
				                    	option.removeAttr('style');
				                    }
			                        option.attr('selected', 'selected');
			                    }				                    
			                    else
			                    {
			                    	if(response[0]['transaction_service_proposal_status'][i]["id"] == 1)
			                    	{
			                    		option.attr('selected', 'selected');
			                    	}
			                        loadFirstTab();
			                    }
			                    $("#tran_status").append(option);
			                };
		                }
		                else if($('.transaction_task option:selected').val() == 30)
		                {
		                	for(var i = 0; i < response[0]['transaction_engagement_letter_status'].length; i++) {
			                    var option = $('<option />');
			                    option.attr('value', response[0]['transaction_engagement_letter_status'][i]["id"]).text(response[0]['transaction_engagement_letter_status'][i]["engagement_letter_status"]);
			                    if(response[0]["service_status"] != 0 && response[0]['transaction_engagement_letter_status'][i]["id"] == response[0]["service_status"])
			                    {
			                        option.attr('selected', 'selected');
			                    }				                    
			                    else
			                    {
			                    	if(response[0]['transaction_engagement_letter_status'][i]["id"] == hold_tran_status)
				                    {
				                        option.attr('selected', 'selected');
				                    }
			                        loadFirstTab();
			                    }
			                    $("#tran_status").append(option);
			                };
		                }
		                else
		                {
		                	for(var i = 0; i < response[0]['transaction_service_status'].length; i++) 
			                {
			                    var option = $('<option />');
			                    option.attr('value', response[0]['transaction_service_status'][i]["id"]).text(response[0]['transaction_service_status'][i]["service_status"]);
			                    if(response[0]["service_status"] != 0 && response[0]['transaction_service_status'][i]["id"] == response[0]["service_status"])
			                    {
			                        option.attr('selected', 'selected');
			                    }				                    
			                    else
			                    {
			                    	if(response[0]['transaction_service_status'][i]["id"] == hold_tran_status)
				                    {
				                        option.attr('selected', 'selected');
				                    }
			                        loadFirstTab();
			                    }
			                    $("#tran_status").append(option);
			                };
		                }
	            	}
	            })
				//if client condition
				if(user_base_client)
				{
					$('#transaction_data button').prop('disabled', true);
					$('#transaction_completion input').prop('disabled', true);
					$('#transaction_completion select').prop('disabled', true);
				}
            }

            if($('#transaction_tab .active > a').attr('href')=="#transaction_create_billing")
            {
            	if($('.transaction_task option:selected').val() == 1 || $('.transaction_task option:selected').val() == 4 || $('.transaction_task option:selected').val() == 5 || $('.transaction_task option:selected').val() == 6 || $('.transaction_task option:selected').val() == 7 || $('.transaction_task option:selected').val() == 12 || $('.transaction_task option:selected').val() == 15 || $('.transaction_task option:selected').val() == 26 || $('.transaction_task option:selected').val() == 31 || $('.transaction_task option:selected').val() == 32 || $('.transaction_task option:selected').val() == 33 || $('.transaction_task option:selected').val() == 34 || $('.transaction_task option:selected').val() == 31 || $('.transaction_task option:selected').val() == 32)
            	{
            		$.ajax({
		                type: "POST",
		                url: "transaction/get_create_billing_interface",
		                data: {"transaction_task_id":$('.transaction_task option:selected').val(), "company_code":transaction_company_code, "transaction_master_id": $("#transaction_trans #transaction_master_id").val()}, // <--- THIS IS THE CHANGE
		                dataType: "json",
		                async: false,
		                success: function(response){
		                	$(".transaction_create_billing_interface").remove();

		                	//billing_top_info = response[0]["edit_bill"];
					        //billing_below_info = response[0]["edit_bill_service"];
					        billings = response[0]["billings"];
					        paid_billings = response[0]["paid_billings"];
					        // service_dropdown = response[0]["get_client_billing_info"];
					        // service_category = response[0]["get_service_category"];
					        // get_unit_pricing = response[0]["get_unit_pricing"];
					        //console.log(billings);
					        //console.log(paid_billings);
					        // console.log(service_dropdown);
					        // console.log(service_category);
					        // console.log(get_unit_pricing);
		                    createBillingInterface(response["billing_interface"], billings, paid_billings);
		                }
		            });
            	}
            	else if($('.transaction_task option:selected').val() == 36)
            	{
            		$.ajax({
		                type: "POST",
		                url: "transaction/get_create_billing_interface",
		                data: {"transaction_task_id":$('.transaction_task option:selected').val(), "company_code":transaction_company_code, "transaction_master_id": $("#transaction_trans #transaction_master_id").val()}, // <--- THIS IS THE CHANGE
		                dataType: "json",
		                async: false,
		                success: function(response){
		                	$(".transaction_create_billing_interface").remove();

		                	//billing_top_info = response[0]["edit_bill"];
					        //billing_below_info = response[0]["edit_bill_service"];
					        billings = response[0]["billings"];
					        paid_billings = response[0]["paid_billings"];
					        // service_dropdown = response[0]["get_client_billing_info"];
					        // service_category = response[0]["get_service_category"];
					        // get_unit_pricing = response[0]["get_unit_pricing"];
					        //console.log(billings);
					        //console.log(paid_billings);
					        // console.log(service_dropdown);
					        // console.log(service_category);
					        // console.log(get_unit_pricing);
		                    createBillingInterface(response["billing_interface"], billings, paid_billings);
		                }
		            });
            	}
            }

            if($('#transaction_tab .active > a').attr('href')=="#transaction_confirm")
            {
            	if($('.transaction_task option:selected').val() == 1)
            	{
	            	$.ajax({
			                type: "POST",
			                url: "transaction/get_all_transaction_incorporation_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    //console.log(response);
			                    $("#transaction_incorporation").remove();
			                    $("#transaction_confirm").append(response['interface']);
			                    if(response[0]["transaction_client"] != false)
			                    {
			                    	$("#transaction_incorporation").show();

					            	//$("#transaction_client_code").html(response[0]["transaction_client"][0]["client_code"]);
					            	$("#transaction_company_name").html(response[0]["transaction_client"][0]["company_name"]);
					            	$("#transaction_company_type").html(response[0]["transaction_client"][0]["company_type_name"]);
					            	$("#transaction_activity1").html(response[0]["transaction_client"][0]["activity1"]);
					            	if(response[0]["transaction_client"][0]["activity2"] == "")
					            	{
					            		$(".activity2").css("display", "none");
					            	}
					            	else
					            	{
					            		$(".activity2").show();
					            		$("#transaction_activity2").html(response[0]["transaction_client"][0]["activity2"]);
					            	}

					            	if(response[0]["transaction_client"][0]["description1"] == "")
					            	{
					            		$(".confirm_description1").css("display", "none");
					            	}
					            	else
					            	{
					            		$(".confirm_description1").show();
					            		$("#transaction_description1").html(response[0]["transaction_client"][0]["description1"]);
					            	}

					            	if(response[0]["transaction_client"][0]["description2"] == "")
					            	{
					            		$(".confirm_description2").css("display", "none");
					            	}
					            	else
					            	{
					            		$(".confirm_description2").show();
					            		$("#transaction_description2").html(response[0]["transaction_client"][0]["description2"]);
					            	}
					            	/*$("#register_activity2").html(response[0]["profile"][0]["activity2"]);*/
					            	$("#transaction_postal_code").html(response[0]["transaction_client"][0]["postal_code"]);
					            	$("#transaction_street_name").html(response[0]["transaction_client"][0]["street_name"]);
					            	$("#transaction_building_name").html(response[0]["transaction_client"][0]["building_name"]);
					            	$("#transaction_unit_no1").html(response[0]["transaction_client"][0]["unit_no1"]);
					            	$("#transaction_unit_no2").html(response[0]["transaction_client"][0]["unit_no2"]);

					            	if(response[0]["transaction_client_officers"] != false)
					            	{
					            		$(".confirmation_officer").remove();

					            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
					            		{
						            		$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       
									        $a += '</tr>';
									            

									        $("#confirmation_officer_table").prepend($a);
									    }
					            	}

					            	if(response[0]["transaction_member"] != false)
					            	{
					            		$(".confirmation_member").remove();

					            		for(var z = 0; z < response[0]["transaction_member"].length; z++)
					            		{
						            		$a = ""; 
									        $a += '<tr class="confirmation_member">';
									        $a += '<td><div class="input-group mb-md">'+(response[0]["transaction_member"][z]["identification_no"]!=null ? response[0]["transaction_member"][z]["identification_no"] : (response[0]["transaction_member"][z]["register_no"]!=null ? response[0]["transaction_member"][z]["register_no"] : response[0]["transaction_member"][z]["registration_no"]))+'</div><div class="input-group mb-md name">'+(response[0]["transaction_member"][z]["company_name"]!=null ? response[0]["transaction_member"][z]["company_name"] : (response[0]["transaction_member"][z]["name"] != null ? response[0]["transaction_member"][z]["name"] : response[0]["transaction_member"][z]["client_company_name"]))+'</div></td>';
									        // $a += '<td><div class="mb-md"><select class="form-control" style="text-align:right;width: 100%;" name="class[]" id="class'+v+'" onchange="optionCheckClass(this);"><option value="0" >Select Class</option></select><div id="form_class"></div><div id="other_class" hidden><p style="font-weight:bold;">Others: </p><input type="text" name="other_class[]" class="form-control input_other_class" value="'+response[0]["transaction_member"][z]["other_class"]+'" disabled="true"/><div id="form_other_class"></div></div></div><div class="input-group mb-md"><select class="form-control" style="text-align:right;width: 170px;" name="currency[]" id="currency'+v+'"><option value="0" >Select Currency</option></select><div id="form_currency"></div></div></td>';
									        // $a += '<td><div class="input-group mb-md"><input type="text" name="number_of_share['+v+']" class="numberdes form-control number_of_share" value="'+addCommas(response[0]["transaction_member"][z]["number_of_share"])+'" id="number_of_share" style="text-align:right;" pattern="^[0-9,]+$"/><div id="form_number_of_share"></div></div><div class="input-group mb-md"><input type="text" name="amount_share['+v+']" id="amount_share" class="numberdes form-control amount_share" value="'+addCommas(response[0]["transaction_member"][z]["amount_share"])+'" style="text-align:right;" pattern="[0-9.,]"/><div id="form_amount_share"></div></div></td>';
									        $a += '<td><div class="input-group mb-md" id="member_class'+z+'">'+response[0]["transaction_member"][z]["sharetype"]+'</div><div class="input-group mb-md " id="other_class'+z+'" style="display: none">'+response[0]["transaction_member"][z]["other_class"]+'</div><div class="input-group mb-md">'+response[0]["transaction_member"][z]["currency"]+'</div></td>';
									        $a += '<td><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["number_of_share"])+'</div><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["amount_share"])+'</div></td>';
									        $a += '<td><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["no_of_share_paid"])+'</div><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["amount_paid"])+'</div></td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_member"][z]["certificate_no"]+'</div></td>';
									        $a += '</tr>';

									        $("#confirmation_member_table").append($a); 

									        if(response[0]["transaction_member"][z]["sharetype"] == "Others")
									        {
									        	//console.log(response[0]["transaction_member"][z]["sharetype"]);
									        	$("#other_class"+z+"").show();
									        	$("#member_class"+z+"").hide();
									        	//console.log($("#confirmation_member_table #other_class"+z+""));
												//tr.find("DIV#other_class").attr("hidden","true");
									        }
									        else if(response[0]["transaction_member"][z]["sharetype"] == "Ordinary Share")
									        {
									        	$("#member_class"+z+"").show();
									        	$("#other_class"+z+"").hide();
									        }
									    }
					            	}

					            	if(response[0]["transaction_client_controller"] != false)
					            	{
					            		$(".confirmation_controller").remove();

										var full_address;

					            		for(var z = 0; z < response[0]["transaction_client_controller"].length; z++)
					            		{
					            			if(response[0]["transaction_client_controller"][z]["client_controller_field_type"] == "individual")
									        {
									            if(response[0]["transaction_client_controller"][z]["alternate_address"] == 1)
									            {
									                full_address = address_format (response[0]["transaction_client_controller"][z]["postal_code2"], response[0]["transaction_client_controller"][z]["street_name2"], response[0]["transaction_client_controller"][z]["building_name2"], response[0]["transaction_client_controller"][z]["unit_no3"], response[0]["transaction_client_controller"][z]["unit_no4"]);
									            }
									            else
									            {
									                full_address = address_format (response[0]["transaction_client_controller"][z]["postal_code1"], response[0]["transaction_client_controller"][z]["street_name1"], response[0]["transaction_client_controller"][z]["building_name1"], response[0]["transaction_client_controller"][z]["officer_unit_no1"], response[0]["transaction_client_controller"][z]["officer_unit_no2"], response[0]["transaction_client_controller"][z]["foreign_address1"], response[0]["transaction_client_controller"][z]["foreign_address2"], response[0]["transaction_client_controller"][z]["foreign_address3"]);
									            }
									        }
									        else if(response[0]["transaction_client_controller"][z]["client_controller_field_type"] == "company")
									        {
									            full_address = address_format (response[0]["transaction_client_controller"][z]["company_postal_code"], response[0]["transaction_client_controller"][z]["company_street_name"], response[0]["transaction_client_controller"][z]["company_building_name"], response[0]["transaction_client_controller"][z]["company_unit_no1"], response[0]["transaction_client_controller"][z]["company_unit_no2"], response[0]["transaction_client_controller"][z]["company_foreign_address1"], response[0]["transaction_client_controller"][z]["company_foreign_address2"], response[0]["transaction_client_controller"][z]["company_foreign_address3"]);
									        }
									        else if(response[0]["transaction_client_controller"][z]["client_controller_field_type"] == "client")
									        {
									            full_address = address_format (response[0]["transaction_client_controller"][z]["postal_code"], response[0]["transaction_client_controller"][z]["street_name"], response[0]["transaction_client_controller"][z]["building_name"], response[0]["transaction_client_controller"][z]["client_unit_no1"], response[0]["transaction_client_controller"][z]["client_unit_no2"], response[0]["transaction_client_controller"][z]["foreign_add_1"], response[0]["transaction_client_controller"][z]["foreign_add_2"], response[0]["transaction_client_controller"][z]["foreign_add_3"]);
									        }
						            		$a = ""; 
									        $a += '<tr class="confirmation_controller">';
									        $a += '<td><div class="input-group mb-md">'+(response[0]["transaction_client_controller"][z]["identification_no"]!=null ? response[0]["transaction_client_controller"][z]["identification_no"] : (response[0]["transaction_client_controller"][z]["register_no"]!=null ? response[0]["transaction_client_controller"][z]["register_no"] : response[0]["transaction_client_controller"][z]["registration_no"]))+'</div><div class="input-group mb-md name">'+(response[0]["transaction_client_controller"][z]["company_name"]!=null ? response[0]["transaction_client_controller"][z]["company_name"] : (response[0]["transaction_client_controller"][z]["name"] != null ? response[0]["transaction_client_controller"][z]["name"] : response[0]["transaction_client_controller"][z]["client_company_name"]))+'</div></td>';
									       	$a += '<td><div class="input-group mb-md">'+(response[0]["transaction_client_controller"][z]["date_of_birth"] != null ? response[0]["transaction_client_controller"][z]["date_of_birth"] : response[0]["transaction_client_controller"][z]["date_of_incorporation"] != null ? response[0]["transaction_client_controller"][z]["date_of_incorporation"] : response[0]["transaction_client_controller"][z]["incorporation_date"])+'</div><div class="input-group mb-md">'+(response[0]["transaction_client_controller"][z]["officer_nationality_name"] != null ? response[0]["transaction_client_controller"][z]["officer_nationality_name"]:response[0]["transaction_client_controller"][z]["country_of_incorporation"])+'</div></td>';
									        $a += '<td><div class="mb-md">'+full_address+'</div></td>';
									        $a += '</tr>';

									        $("#confirmation_controller_table").append($a); 

									    }
					            	}

					            	if(response[0]["transaction_filing"] != false)
					            	{
					            		$("#transaction_filing_year_end").html(response[0]["transaction_filing"][0]["year_end"]);
					            		$("#transaction_filing_cycle").html(response[0]["transaction_filing"][0]["period"]);
					            		
					            	}

					            	if(response[0]["transaction_billing"] != false)
					            	{
					            		$(".confirmation_billing").remove();

					            		for(var z = 0; z < response[0]["transaction_billing"].length; z++)
					            		{
						            		$a = ""; 
									        $a += '<tr class="confirmation_billing">';
									        $a += '<td><div class="input-group mb-md">'+response[0]["transaction_billing"][z]["service_name"]+'</div></td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["invoice_description"]+'</div></td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["currency_name"]+'</div><div>'+addCommas(response[0]["transaction_billing"][z]["amount"])+'</div></td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["unit_pricing_name"]+'</div></td>';
									        if(response[0]["transaction_billing"][z]["branch_name"] != "")
									        {
									        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?(response[0]["transaction_billing"][z]["firm_name"] +' ('+response[0]["transaction_billing"][z]["branch_name"]+')'):'')+'</div></td>';
									        }
									        else
									        {
									        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?response[0]["transaction_billing"][z]["firm_name"]:'')+'</div></td>';
									        }
									        
									        $a += "</tr>";

									        $("#confirmation_billing_table").append($a); 

									    }
					            		
					            	}

					            	if(response[0]["transaction_client_signing_info"] != false)
					            	{
					            		$("#confirm_chairman_name").html(response[0]["transaction_client_signing_info"][0]["chairman_name"]);
					            		$("#confirm_director_name_1").html(response[0]["transaction_client_signing_info"][0]["director_name_1"]);
					            		if(response[0]["transaction_client_signing_info"][0]["director_name_2"] != null)
					            		{
					            			$(".confirm_director_signature_2").show();
					            			$("#confirm_director_name_2").html(response[0]["transaction_client_signing_info"][0]["director_name_2"]);
					            		}
					            		else
					            		{
					            			$(".confirm_director_signature_2").hide();
					            		}

					            		
					            	}

					            	if(response[0]["transaction_contact_person_info"] != false)
					            	{
					            		$("#confirm_contact_name").html(response[0]["transaction_contact_person_info"][0]["name"]);

					            		var client_contact_info_email = response[0]["transaction_contact_person_info"][0]["transaction_client_contact_info_email"];
					            		var client_contact_info_phone = response[0]["transaction_contact_person_info"][0]["transaction_client_contact_info_phone"];
					            		
					            		$(".confirm_phone").remove();
					            		$(".confirm_email").remove();

					            		if(client_contact_info_phone != null)
					            		{
						            		for (var h = 0; h < client_contact_info_phone.length; h++) 
										    {
										        var clientContactInfoPhoneArray = client_contact_info_phone[h].split(',');

										        if(clientContactInfoPhoneArray[2] == 1)
										        {
										            $(".confirm_fieldGroup_contact_phone").find('.confirm_contact_phone').html(clientContactInfoPhoneArray[1]);
										          
										            $(".confirm_fieldGroup_contact_phone").find('.confirm_main_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);
										        }
										        else
										        {
										        	$(".confirm_fieldGroupCopy_contact_phone").find('.confirm_second_hp').html(clientContactInfoPhoneArray[1]);
										            $(".confirm_fieldGroupCopy_contact_phone").find('.confirm_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);


										            var fieldHTML = '<div class="input-group confirm_fieldGroup_contact_phone confirm_phone" style="margin-top:10px;">'+$(".confirm_fieldGroupCopy_contact_phone").html()+'</div>';

										            $( fieldHTML).prependTo(".confirm_contact_phone_toggle");
										        }
										    }
										}
										if(client_contact_info_email != null)
					            		{
						            		for (var h = 0; h < client_contact_info_email.length; h++) 
										    {
										        var clientContactInfoEmailArray = client_contact_info_email[h].split(',');

										        if(clientContactInfoEmailArray[2] == 1)
										        {
										            $(".confirm_fieldGroup_contact_email").find('.confirm_contact_email').html(clientContactInfoEmailArray[1]);
										            $(".confirm_fieldGroup_contact_email").find('.confirm_main_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

										            $(".confirm_fieldGroup_contact_email").find(".confirm_button_increment_contact_email").css({"visibility": "visible"});
										        }
										        else
										        {
										            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_second_contact_email').html(clientContactInfoEmailArray[1]);

										            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

										            var fieldHTML = '<div class="input-group confirm_fieldGroup_contact_email confirm_email" style="margin-top:10px; display: block !important;">'+$(".confirm_fieldGroupCopy_contact_email").html()+'</div>';

										            //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
										            $( fieldHTML).prependTo(".confirm_contact_email_toggle");

										            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_second_contact_email').html("");
										            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_contact_email_primary').attr("value", "");
										        }
										    }
										}
					            	}

					            	if(response[0]["transaction_client_selected_reminder"] != false)
					            	{
					            		if(response[0]["transaction_client_selected_reminder"].length == 1)
					            		{
					            			$(".confirm_select_reminder_group").html(response[0]["transaction_client_selected_reminder"][0]["reminder_tag_name"]);
					            		}
					            		else
					            		{
					            			var reminder_tag = "";

					            			for (var h = 0; h < response[0]["transaction_client_selected_reminder"].length; h++) 
									    	{
									    		
									    		if(h == 0)
									    		{
									    			if(response[0]["transaction_client_selected_reminder"][h]['reminder_tag_name'] != null)
									    			{
									    				reminder_tag = response[0]["transaction_client_selected_reminder"][h]['reminder_tag_name'];
									    			}
									    		}
									    		else if(h == response[0]["transaction_client_selected_reminder"].length)
									    		{
									    			if(response[0]["transaction_client_selected_reminder"][h]['reminder_tag_name'] != null)
									    			{
									    				reminder_tag = reminder_tag + ', ' + response[0]["transaction_client_selected_reminder"][h]['reminder_tag_name'];
									    			}
									    		}
									    		else
									    		{
									    			if(response[0]["transaction_client_selected_reminder"][h]['reminder_tag_name'] != null)
									    			{
									    				reminder_tag = reminder_tag + ', ' + response[0]["transaction_client_selected_reminder"][h]['reminder_tag_name'];
									    			}
									    		}

									    	}

									    	$(".confirm_select_reminder_group").html(reminder_tag);
					            		}
					            	}

					            	
			                    }
			      //               if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                	var a="";
								 //        a += '<tr class="row_doc_pending">';
								 //        a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        // $a += '<td>'+response[0]["transaction_pending_documents"][h]["company_name"]+'</td>';
								 //        a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        //$a += '<td style="text-align: center"><?=date("d F Y",strtotime('+response[0]["transaction_pending_documents"][h]["created_at"]+'))?></td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        a += '</tr>';
								            

								 //        $("#pending_doc_body").append(a);
								 //    }
			      //               }
			                }
			            });
					}
					else if($('.transaction_task option:selected').val() == 2)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_all_appoint_new_director_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_officer_table").remove();
			                	$("#transaction_confirm").append(response['interface']);
			                	$("#transaction_confirmation_officer_table .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_officer_table .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);
			                	
			                	if(response[0]["transaction_client_officers"] != false)
				            	{
				            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
				            		{
					            		$a="";
								        $a += '<tr class="confirmation_officer">';
								        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
								        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
								        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
								       	// $a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
								        $a += '</tr>';
								            

								        $("#confirmation_officer_table").prepend($a);
								    }
				            	}

				     //        	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                	$a="";
								 //        $a += '<tr class="row_doc_pending">';
								 //        $a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        $a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	$a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	$a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        $a += '</tr>';
								            

								 //        $("#pending_doc_body").append($a);
								 //    }
			      //               }
			                    //$(".lodgement_date").val(response[0]['transaction_client_officers'][0]['effective_date']);

			                    
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 3)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_all_resign_director_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_officer_table").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_officer_table .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_officer_table .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);
			                	
			                	if(response[0]["transaction_client_officers"] != false)
				            	{
				            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
				            		{
				            			if(response[0]["transaction_client_officers"][i]["date_of_appointment"] != "")
				            			{
						            		$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_cessation"] +'</td>';
									       	$a += '<td>'+ (response[0]["transaction_client_officers"][i]["reason"]!=null ? response[0]["transaction_client_officers"][i]["reason"]:"") +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["is_resign"]!=0 ? "Withdraw":"") +'</td>';
									        $a += '</tr>';
									            
									        $("#confirmation_officer_table").append($a);
									    }
									    else
									    {
									    	$("#confirmation_appoint_officer").show();
									    	var c = "";
									        c += '<tr class="confirmation_officer">';
									        c += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        c += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        c += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									        c += '</tr>';
									            
									        $("#confirmation_appoint_officer_table").append(c);
									    }
								    }
				            	}

				     //        	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                	$a="";
								 //        $a += '<tr class="row_doc_pending">';
								 //        $a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        $a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	$a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	$a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        $a += '</tr>';
								            

								 //        $("#pending_doc_body").append($a);
								 //    }
			      //               }
			      //               $(".lodgement_date").val(response[0]['transaction_client_officers'][0]['effective_date']);

			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 4)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_change_regis_office_address_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_change_regis_ofis_address").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_change_regis_ofis_address .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_change_regis_ofis_address .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);

			                	if(response[0]['transaction_change_regis_ofis_address'] != false)
			                	{
				                	if(response[0]['transaction_change_regis_ofis_address'][0]["registered_address"] == "1")
								    {
								        $("#confirmation_use_registered_address").attr("checked", true);
								    }
								    else
								    {
								        $("#confirmation_use_registered_address").attr("checked", false);
								    }

								    $("#confirmation_postal_code").append(response[0]['transaction_change_regis_ofis_address'][0]['postal_code']);
								    $("#confirmation_street_name").append(response[0]['transaction_change_regis_ofis_address'][0]['street_name']);
								    $("#confirmation_building_name").append(response[0]['transaction_change_regis_ofis_address'][0]['building_name']);
								    $("#confirmation_unit_no1").append(response[0]['transaction_change_regis_ofis_address'][0]['unit_no1']);
								    $("#confirmation_unit_no2").append(response[0]['transaction_change_regis_ofis_address'][0]['unit_no2']);
			                		$("#transaction_confirmation_change_regis_ofis_address #confirm_effective_date").append(response[0]['transaction_change_regis_ofis_address'][0]['effective_date']);
			                	}

			                	if(response[0]["transaction_billing"] != false)
				            	{
				            		$(".confirmation_billing").remove();

				            		for(var z = 0; z < response[0]["transaction_billing"].length; z++)
				            		{
					            		$a = ""; 
								        $a += '<tr class="confirmation_billing">';
								        $a += '<td><div class="input-group mb-md">'+response[0]["transaction_billing"][z]["service_name"]+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["invoice_description"]+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["currency_name"]+'</div><div>'+addCommas(response[0]["transaction_billing"][z]["amount"])+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["unit_pricing_name"]+'</div></td>';
								        if(response[0]["transaction_billing"][z]["branch_name"] != "")
								        {
								        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?(response[0]["transaction_billing"][z]["firm_name"] +' ('+response[0]["transaction_billing"][z]["branch_name"]+')'):'')+'</div></td>';
								        }
								        else
								        {
								        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?response[0]["transaction_billing"][z]["firm_name"]:'')+'</div></td>';
								        }
								        
								        $a += "</tr>";

								        $("#confirmation_billing_table").append($a); 

								    }
				            		
				            	}
			      //           	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	//$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                 	var b = "";

								 //        b += '<tr class="row_doc_pending">';
								 //        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        b += '</tr>';
								            

								 //        $("#pending_doc_body").append(b);
								 //    }
			      //               }

			      //               $(".lodgement_date").val(response[0]['transaction_change_regis_ofis_address'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 5)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_change_biz_activity_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_change_biz_activity").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_change_biz_activity .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_change_biz_activity .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);

			                	if(response[0]['transaction_change_biz_activity'] != false)
			                	{
			                		//console.log($("#new_biz_activity"));
								    $("#transaction_confirmation_change_biz_activity #new_activity1").append(response[0]['transaction_change_biz_activity'][0]['activity1']);
			                		$("#transaction_confirmation_change_biz_activity #new_description1").append(response[0]['transaction_change_biz_activity'][0]['description1']);
			                		$("#transaction_confirmation_change_biz_activity #new_activity2").append(response[0]['transaction_change_biz_activity'][0]['activity2']);
			                		$("#transaction_confirmation_change_biz_activity #new_description2").append(response[0]['transaction_change_biz_activity'][0]['description2']);
			                		$("#transaction_confirmation_change_biz_activity #confirm_effective_date").append(response[0]['transaction_change_biz_activity'][0]['effective_date']);
			                	}

			                	
			      //           	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	//$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                 	var b = "";

								 //        b += '<tr class="row_doc_pending">';
								 //        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        b += '</tr>';
								            

								 //        $("#pending_doc_body").append(b);
								 //    }
			      //               }

			                    //$(".lodgement_date").val(response[0]['transaction_change_biz_activity'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 6)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_change_FYE_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_change_FYE").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_change_FYE .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_change_FYE .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);

			                	if(response[0]['transaction_change_FYE'] != false)
			                	{
								    $("#transaction_confirmation_change_FYE #new_FYE").append(response[0]['transaction_change_FYE'][0]['new_year_end']);
								    $("#transaction_confirmation_change_FYE #new_financial_year_period").append(response[0]['transaction_change_FYE'][0]['period']);
								    $("#transaction_confirmation_change_FYE #confirm_effective_date").append(response[0]['transaction_change_FYE'][0]['effective_date']);
			                	}

			                	
			      //           	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	//$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                 	var b = "";

								 //        b += '<tr class="row_doc_pending">';
								 //        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        b += '</tr>';
								            

								 //        $("#pending_doc_body").append(b);
								 //    }
			      //               }

			      //               $(".lodgement_date").val(response[0]['transaction_change_FYE'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 7)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_all_appoint_resign_auditor_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_officer_table").remove();
			                	$("#transaction_confirm").append(response['interface']);
			                	
			                	$(".confirmation_registration_no").html($("#apointment_of_auditor_form #registration_no").text());
			                	$(".confirmation_company_name").html($("#apointment_of_auditor_form #company_name").text());
			                	if(response[0]["transaction_meeting_date"])
			                	{
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

									$(".confirmation_notice_date").html(response[0]["transaction_meeting_date"][0]["notice_date"]);
				                	$(".confirmation_director_meeting_date").html(response[0]["transaction_meeting_date"][0]["director_meeting_date"]);
				                	$(".confirmation_director_meeting_time").html(response[0]["transaction_meeting_date"][0]["director_meeting_time"]);
				                	$(".confirmation_member_meeting_date").html(response[0]["transaction_meeting_date"][0]["member_meeting_date"]);
				                	$(".confirmation_member_meeting_time").html(response[0]["transaction_meeting_date"][0]["member_meeting_time"]);
				                	
				                	if(response[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address" || response[0]["transaction_meeting_date"][0]['address_type'] == "")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_meeting_date"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_meeting_date"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_meeting_date"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_meeting_date"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_meeting_date"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_meeting_date"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_meeting_date"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_meeting_date"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_meeting_date"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_meeting_date"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_meeting_date"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_meeting_date"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_meeting_date"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                }
				                else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }

			                	if(response[0]["transaction_client_officers"] != false)
				            	{
				            		//$(".confirmation_meeting_date").html(response[0]["transaction_client_officers"][0]["meeting_date"]);
				            		$(".confirmation_notice_date").html(response[0]["transaction_client_officers"][0]["notice_date"]);

				            		response[0]["transaction_client_officers"][0]["notice_date"];
				            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
				            		{
				            			if(response[0]["transaction_client_officers"][i]["date_of_appointment"] != "" && response[0]["transaction_client_officers"][i]["appoint_resign_flag"] ==  "resign")
				            			{
						            		$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_cessation"] +'</td>';
									       	$a += '<td>'+ (response[0]["transaction_client_officers"][i]["reason"]!="" && response[0]["transaction_client_officers"][i]["reason"]!="NULL" ? response[0]["transaction_client_officers"][i]["reason"] : (response[0]["transaction_client_officers"][i]["reason_selected"] != null ? response[0]["transaction_client_officers"][i]["reason_selected"] : "")) +'</td>';
									        $a += '</tr>';
									        
									        $("#confirmation_officer_table").append($a);
									    }
									    else
									    {
									    	$("#confirmation_appoint_officer").show();
									    	var c = "";
									        c += '<tr class="confirmation_officer">';
									        c += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        c += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        c += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									        c += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									        c += '</tr>';
									            
									        $("#confirmation_appoint_officer_table").append(c);
									    }
								    }
				            	}

				            	if($('#body_appoint_new_auditor').find(".row_appoint_resign_auditor").length == 0)
    							{
    								$("#confirmation_appoint_officer").hide();
    							}
    							else
    							{
    								$("#confirmation_appoint_officer").show();
    							}

				            	if($("#body_resign_auditor .withdraw_auditor").length >= 1 || $("#body_resign_auditor .cancel_withdraw_auditor").length >= 1)
    							{
    								$("#confirmation_resign_officer").show();
    							}
    							else
    							{
    								$("#confirmation_resign_officer").hide();
    							}


				            	if(response[0]["transaction_pending_documents"] != false)
			                    {
			                    	$(".row_doc_pending").remove();
			                    	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									{
				                    	$a="";
								        $a += '<tr class="row_doc_pending">';
								        $a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								        $a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
										{
								        	$a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								        }
								        else
								        {
								        	$a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								        }
								        $a += '</tr>';
								            

								        $("#pending_doc_body").append($a);
								    }
			                    }

			                    // ADDED BY JW
			                    if(response[0]['transaction_client_officers'] != false)
			                    {
			                    	$(".lodgement_date").val(response[0]['transaction_client_officers'][0]['effective_date']);
			                    }
			                    else
			                    {
			                    	$(".lodgement_date").val();
			                    }
			                    // END ADDED BY JW
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 8)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_issue_dividend_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_issue_dividend").remove();
			                	$("#transaction_confirm").append(response['interface']);
			                	$(".confirmation_company_name").append(response[0]["client_name"]);
			                	if(response[0]["transaction_meeting_date"])
			                	{
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	$(".confirmation_director_meeting_date").html(response[0]["transaction_meeting_date"][0]["director_meeting_date"]);
				                	$(".confirmation_director_meeting_time").html(response[0]["transaction_meeting_date"][0]["director_meeting_time"]);
				                	$(".confirmation_member_meeting_date").html(response[0]["transaction_meeting_date"][0]["member_meeting_date"]);
				                	$(".confirmation_member_meeting_time").html(response[0]["transaction_meeting_date"][0]["member_meeting_time"]);
				                	
				                	if(response[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_meeting_date"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_meeting_date"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_meeting_date"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_meeting_date"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_meeting_date"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_meeting_date"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_meeting_date"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_meeting_date"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_meeting_date"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_meeting_date"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_meeting_date"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_meeting_date"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_meeting_date"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                }
				                else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }

			                	if(response[0]['transaction_issue_dividend'] != false)
			                 	{
				                	var transaction_issue_dividend = response[0]['transaction_issue_dividend'];

				                	$(".confirmation_currency").html(transaction_issue_dividend[0]["currency_name"]);
				                	$(".confirmation_total_dividend_amount").html(addCommas(transaction_issue_dividend[0]["total_dividend_amount"]));
				                	$(".confirmation_declare_of_fye").html(transaction_issue_dividend[0]["declare_of_fye"]);
				                	$(".confirmation_devidend_of_cut_off_date").html(transaction_issue_dividend[0]["devidend_of_cut_off_date"]);
				                	$(".confirmation_devidend_payment_date").html(transaction_issue_dividend[0]["devidend_payment_date"]);
				                	$(".confirmation_nature").html(transaction_issue_dividend[0]["nature_name"]);

				                	var dividend_list = transaction_issue_dividend;
							        for(var r = 0; r < dividend_list.length; r++)
							        {
							            $b=""; 
							            $b += '<tr class="dividend_table">';
							            $b += '<td style="text-align: left;">'+dividend_list[r]["shareholder_name"]+'</td>';
							            $b += '<td style="text-align: right;">'+addCommas(dividend_list[r]["number_of_share"])+'</td>';
							            $b += '<td style="text-align: right;">'+addCommas(dividend_list[r]["devidend_paid"])+'</td>';
							            $b += '</tr>';

							            $("#confirmation_body_issue_dividend").append($b);
							        }

							        $b =""; 
								    $b += '<tr class="total_amount remove_member_info_class">';
								    $b += '<td style="font-weight: bold;">Total</td>';
								    $b += '<td style="text-align:right">'+addCommas(transaction_issue_dividend[0]["total_number_of_share"])+'</td>';
								    $b += '<td style="text-align:right">'+addCommas(transaction_issue_dividend[0]["total_devidend_paid"])+'</td>';
								    $b += '</tr>';

								    $("#confirmation_body_issue_dividend").append($b);
				                }
			                	
			      //           	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	//$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                 	var b = "";

								 //        b += '<tr class="row_doc_pending">';
								 //        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        b += '</tr>';
								            

								 //        $("#pending_doc_body").append(b);
								 //    }
			      //               }

			                    //$(".lodgement_date").val(response[0]['transaction_issue_dividend'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 9)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_issue_director_fee_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_issue_director_fee").remove();
			                	$("#transaction_confirm").append(response['interface']);
			                	$("#confimation_company_name").text(response[0]["client_name"]);
			                	if(response[0]["transaction_meeting_date"])
			                	{
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	$(".confirmation_director_meeting_date").html(response[0]["transaction_meeting_date"][0]["director_meeting_date"]);
				                	$(".confirmation_director_meeting_time").html(response[0]["transaction_meeting_date"][0]["director_meeting_time"]);
				                	$(".confirmation_member_meeting_date").html(response[0]["transaction_meeting_date"][0]["member_meeting_date"]);
				                	$(".confirmation_member_meeting_time").html(response[0]["transaction_meeting_date"][0]["member_meeting_time"]);
				                	
				                	if(response[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_meeting_date"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_meeting_date"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_meeting_date"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_meeting_date"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_meeting_date"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_meeting_date"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_meeting_date"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_meeting_date"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_meeting_date"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_meeting_date"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_meeting_date"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_meeting_date"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_meeting_date"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                }
				                else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }

			                	if(response[0]['transaction_issue_director_fee'] != false)
			                 	{
				                	var transaction_issue_director_fee = response[0]['transaction_issue_director_fee'];

				                	$(".confirmation_declare_of_fye").html(transaction_issue_director_fee[0]["declare_of_fye"]);
				                	$(".confirmation_resolution_date").html(transaction_issue_director_fee[0]["resolution_date"]);
				                	$(".confirmation_meeting_date").html(transaction_issue_director_fee[0]["meeting_date"]);
				                	$(".confirmation_notice_date").html(transaction_issue_director_fee[0]["notice_date"]);

				                	var director_list = transaction_issue_director_fee;
							        for(var r = 0; r < director_list.length; r++)
							        {
							            $b=""; 
							            $b += '<tr class="director_fee_table'+r+'">';
							            $b += '<td style="text-align: left;">'+director_list[r]["identification_register_no"]+'</td>';
							            $b += '<td style="text-align: left;">'+director_list[r]["director_name"]+'</td>';
							            $b += '<td style="text-align: center;">'+director_list[r]["date_of_appointment"]+'</td>';
							            $b += '<td>'+((director_list[r]["currency_name"] != null)?director_list[r]["currency_name"]:"-")+'</td>';
							            $b += '<td style="text-align: right;">'+addCommas(director_list[r]["director_fee"])+'</td>';
							            $b += '</tr>';

							            $("#comfirmation_body_issue_director_fee").append($b);
							        }
				                }
			         //        	if(response[0]['transaction_incorporation_subsidiary'] != false)
			         //        	{
								    // var transaction_incorporation_subsidiary = response[0]['transaction_incorporation_subsidiary']
								    // for(var i = 0; i < transaction_incorporation_subsidiary.length; i++)
								    // {
								    //     $a = ""; 
								    //     $a += '<tr class="row_corp_rep">';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["subsidiary_name"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["name_of_corp_rep"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["identity_number"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["corp_rep_effective_date"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["cessation_date"]+'</td>';
								    //     $a += '</tr>';
								        
								    //     $("#transaction_confirmation_incorp_subsidiary #body_corp_rep").prepend($a); 
								    // }
			         //        	}

			                	
			      //           	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	//$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                 	var b = "";

								 //        b += '<tr class="row_doc_pending">';
								 //        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        b += '</tr>';
								            

								 //        $("#pending_doc_body").append(b);
								 //    }
			      //               }

			                    //$(".lodgement_date").val(response[0]['transaction_issue_director_fee'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 10)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_share_transfer_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){

			                	$("#transaction_share_transfer").remove();
			                	$("#transaction_confirm").append(response['interface']);
				     			//Transferor Record
				     			if(response[0]["share_number_for_cert_record"])
			                 	{
			                 		var transaction_share_number_for_cert_record = response[0]["share_number_for_cert_record"];
			                 		var transaction_last_cert_no = response[0]["last_cert_no"][0]["last_cert_no"], latest_certificate_no;

								    $(".confirm_share_number_for_cert_record").remove();
								    //console.log(transaction_share_number_for_cert_record);
								    if(transaction_share_number_for_cert_record)
								    {
								        for(var i = 0; i < transaction_share_number_for_cert_record.length; i++)
								        {
								        	if(transaction_share_number_for_cert_record[i]["number_of_share"] != 0)
								        	{
									            if(transaction_share_number_for_cert_record[i]["sharetype"] == "Others")
									            {
									                var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")";
									            }
									            else
									            {
									                var sharetype = "";
									            }

									            if(transaction_share_number_for_cert_record[i]["certificate_no"] != "")
									            {
									            	latest_certificate_no = transaction_share_number_for_cert_record[i]["certificate_no"];
									            	transaction_last_cert_no = transaction_share_number_for_cert_record[i]["certificate_no"];
									            }
									            else
									            {
									            	latest_certificate_no = parseInt(transaction_last_cert_no) + 1;
									            	transaction_last_cert_no = parseInt(transaction_last_cert_no) + 1;
									            	$(".cert_remind_tag").text("* Below certificate number are suggested by the system, please save it before you proceed to the next page.");
									            }

									            var a = ""; 
									            a += '<tr class="confirm_share_number_for_cert_record">';
									            a += '<td class="transferor_name"><input type="hidden" class="officer_id" value="'+transaction_share_number_for_cert_record[i]["officer_id"]+'"><input type="hidden" class="field_type" value="'+transaction_share_number_for_cert_record[i]["field_type"]+'">'+((transaction_share_number_for_cert_record[i]["identification_no"] != null)?transaction_share_number_for_cert_record[i]["identification_no"] : (transaction_share_number_for_cert_record[i]["register_no"] != null?transaction_share_number_for_cert_record[i]["register_no"]:transaction_share_number_for_cert_record[i]["registration_no"]))+' - '+((transaction_share_number_for_cert_record[i]["name"] != null)?transaction_share_number_for_cert_record[i]["name"] : (transaction_share_number_for_cert_record[i]["company_name"] != null?transaction_share_number_for_cert_record[i]["company_name"]:transaction_share_number_for_cert_record[i]["client_company_name"]))+'</td>';
									            a += '<td>'+transaction_share_number_for_cert_record[i]["currency"]+'/'+ transaction_share_number_for_cert_record[i]["sharetype"] + " " + sharetype+'</td>';
									            a += '<td style="text-align:right" class="old_nfs">'+addCommas(transaction_share_number_for_cert_record[i]["number_of_share"])+'</td>';
									            a += '<td style="text-align:center">'+transaction_share_number_for_cert_record[i]["new_certificate_no"]+'</td>';
									            // a += '<td style="text-align:center"><input type="checkbox" name="cancel_shares_cert[]" class="cancel_shares_cert" value="'+transaction_share_number_for_cert_record[i]["id"]+'" disabled="true"/></td>';
									            a += '<td style="text-align:right" class="transfered_share">0</td>';
									            a += '<td><input class="form-control numberdes assign_number_of_share" type="text" name="assign_number_of_share[]" value="" readonly="true"></td>';
									            a += '<td><input class="form-control new_number_of_share" type="text" name="new_number_of_share[]" value="" readonly="true"></td>';
									            a += '<td><input type="hidden" name="officer_id[]" value="'+transaction_share_number_for_cert_record[i]["officer_id"]+'"><input type="hidden" name="field_type[]" value="'+transaction_share_number_for_cert_record[i]["field_type"]+'"><input type="hidden" name="certificate_id[]" value="'+transaction_share_number_for_cert_record[i]["id"]+'"><input type="hidden" name="sharetype[]" value="'+transaction_share_number_for_cert_record[i]["sharetype"] + " " + sharetype+'"><input class="form-control latest_certificate" type="text" name="certificate[]" value="" readonly="true"></td>';//'+latest_certificate_no+'
									            a += '</tr>';

									            $(".transferor_table_add").append(a);
									        }
								        }
								    }
			      				}

								$('.transferor_table_add').each(function () {
							        var values = $(this).find("tr>td:first-of-type");
							        var currency_class_values = $(this).find("tr>td:nth-of-type(2)");
							        //console.log(currency_class_values.eq(0).text());
							        var number_of_share_transfer = $(this).find("tr>td:nth-of-type(5)");
							        var run = 1
							        for (var i=values.length-1;i>-1;i--){
							            if ( values.eq(i).text() === values.eq(i-1).text() && currency_class_values.eq(i).text() === currency_class_values.eq(i-1).text() && i != 0){
							                values.eq(i).hide();
							                number_of_share_transfer.eq(i).hide();
							                run++;
							            }else{
							                values.eq(i).attr("rowspan",run);
							                number_of_share_transfer.eq(i).attr("rowspan",run);
							                run = 1;
							            }
							        }
							    });

			      				//Transferee Record
				     			if(response[0]["latest_share_number_for_cert"])
			                 	{
			                 		var transaction_latest_share_number_for_cert = response[0]["latest_share_number_for_cert"];
			                 		var transaction_last_cert_no = response[0]["last_cert_no"][0]["last_cert_no"], latest_certificate_no;

								    $(".confirm_latest_share_number_for_cert").remove();

								    if(transaction_latest_share_number_for_cert)
								    {
								        for(var i = 0; i < transaction_latest_share_number_for_cert.length; i++)
								        {	//console.log(parseInt(transaction_latest_share_number_for_cert[i]["number_of_share"]) > 0);
								        	if(parseInt(transaction_latest_share_number_for_cert[i]["number_of_share"]) > 0)
								        	{
									            if(transaction_latest_share_number_for_cert[i]["sharetype"] == "Others")
									            {
									                var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")";
									            }
									            else
									            {
									                var sharetype = "";
									            }

									            if(transaction_latest_share_number_for_cert[i]["certificate_no"] != "")
									            {
									            	latest_certificate_no = transaction_latest_share_number_for_cert[i]["certificate_no"];
									            	transaction_last_cert_no = transaction_latest_share_number_for_cert[i]["certificate_no"];
									            }
									            else
									            {
									            	latest_certificate_no = parseInt(transaction_last_cert_no) + 1;
									            	transaction_last_cert_no = parseInt(transaction_last_cert_no) + 1;
									            }

									            var a = ""; 
									            a += '<tr class="confirm_latest_share_number_for_cert">';
									            a += '<td>'+((transaction_latest_share_number_for_cert[i]["identification_no"] != null)?transaction_latest_share_number_for_cert[i]["identification_no"] : (transaction_latest_share_number_for_cert[i]["register_no"] != null?transaction_latest_share_number_for_cert[i]["register_no"]:transaction_latest_share_number_for_cert[i]["registration_no"]))+' - '+((transaction_latest_share_number_for_cert[i]["name"] != null)?transaction_latest_share_number_for_cert[i]["name"] : (transaction_latest_share_number_for_cert[i]["company_name"] != null?transaction_latest_share_number_for_cert[i]["company_name"]:transaction_latest_share_number_for_cert[i]["client_company_name"]))+'</td>';
									            a += '<td>'+transaction_latest_share_number_for_cert[i]["currency"]+'/'+ transaction_latest_share_number_for_cert[i]["sharetype"] + " " + sharetype+'</td>';
									            a += '<td style="text-align:right"><input type="hidden" name="transferee_new_number_of_share[]" value="'+transaction_latest_share_number_for_cert[i]["number_of_share"]+'"/>'+addCommas(transaction_latest_share_number_for_cert[i]["number_of_share"])+'</td>';
									            //a += '<td style="text-align:center"> - </td>';
									            //a += '<td><input class="form-control" type="text" name="new_number_of_share[]" value=""></td>';
									            a += '<td><input type="hidden" name="transferee_officer_id[]" value="'+transaction_latest_share_number_for_cert[i]["officer_id"]+'"/><input type="hidden" name="transferee_field_type[]" value="'+transaction_latest_share_number_for_cert[i]["field_type"]+'"/><input type="hidden" name="transferee_certificate_id[]" value="'+transaction_latest_share_number_for_cert[i]["id"]+'"/><input type="hidden" name="transferee_sharetype[]" value="'+transaction_latest_share_number_for_cert[i]["sharetype"] + " " + sharetype+'"><input class="form-control" type="text" name="transferee_certificate[]" value="'+latest_certificate_no+'"/></td>';
									            a += '</tr>';

									            $(".certificate_table_add").append(a);
									        }
									        else
									        {
									        	var negative_transferee_officer_id = transaction_latest_share_number_for_cert[i]["officer_id"];
									        	var negative_transferee_field_type = transaction_latest_share_number_for_cert[i]["field_type"];
									        	var negative_transferee_currency = transaction_latest_share_number_for_cert[i]["currency"]+'/'+transaction_latest_share_number_for_cert[i]["sharetype"];
									        	// console.log(negative_transferee_officer_id);
									        	// console.log(negative_transferee_field_type);
									        	// console.log(negative_transferee_currency);
									        	$('.transferor_table_add td:first-child').each(function() 
												{
													var transferor_row = $(this);
													var transferor_officer_id = transferor_row.find(".officer_id").val();
													var transferor_field_type = transferor_row.find(".field_type").val();
													var transferor_currency_class = transferor_row.parent().find("td:nth-of-type(2)").text();
													// console.log(transferor_officer_id);
													// console.log(transferor_field_type);
													//console.log(transferor_currency_class);
													//console.log(negative_transferee_currency.localeCompare(transferor_currency_class));
													if(negative_transferee_officer_id == transferor_officer_id && negative_transferee_field_type == transferor_field_type && negative_transferee_currency.trim() == transferor_currency_class.trim())
													{
														//latest_certificate_no = parseInt(transaction_last_cert_no) + 1;
														$(this).parent().find(".transfered_share").text(addCommas(transaction_latest_share_number_for_cert[i]["number_of_share"]));
														//$(this).parent().find(".latest_certificate").val(latest_certificate_no);
														//console.log(transaction_latest_share_number_for_cert[i]["number_of_share"]);
													}
													//console.log($(this).parent());
													//console.log(transferee_officer_id);
													//console.log(transferee_field_type);
												});
									        }
								        }
								    }
								    var new_number_of_share = 0;
								    var transferor_first_name = $(".transferor_table_add td").eq(0).text();
								    var finish_minus_calculation = false;
								    var number_of_loop = 0;

								    if(response[0]["transaction_share_transfer_record"])
			                 		{
			                 			var transaction_share_transfer_record = response[0]["transaction_share_transfer_record"][0];
			                 			var transferor_array = JSON.parse(response[0]["transaction_share_transfer_record"][0]["transferor_array"]);
			                 			var transferee_array = JSON.parse(response[0]["transaction_share_transfer_record"][0]["transferee_array"]);

			                 			$('.transferor_table_add td:nth-child(5)').each(function() 
										{
											if($(this).text() != "0")
											{
												$(this).parent().find(".assign_number_of_share").removeAttr("readonly");
												//$(this).parent().parent().find(".new_number_of_share").removeAttr("readonly");
												$(this).parent().find(".latest_certificate").removeAttr("readonly");
											}
			                 			});

			                 			$.each(transferor_array, function(index, value) {
			                 				//console.log(value);
			                 				// if(value["officer_id"] == $('.transferor_table_add td:nth-child(1)').eq(index).find(".officer_id").val())
			                 				// {
			                 				// 	$('.transferor_table_add td:nth-child(6)').eq(index).find(".assign_number_of_share").removeAttr("readonly");
			                 				// }
			                 				if(value["number_of_shares_to_transfer"] != "")
			                 				{
			                 					$('.transferor_table_add td:nth-child(6)').eq(index).find(".assign_number_of_share").val(value["number_of_shares_to_transfer"]);
			                 					$('.transferor_table_add td:nth-child(7)').eq(index).find(".new_number_of_share").val(value["new_number_of_share"]);
			                 					$('.transferor_table_add td:nth-child(8)').eq(index).find(".latest_certificate").val(value["certificate"]);
			                 				}
										});

										$.each(transferee_array, function(index, value) {
											$('.certificate_table_add td:nth-child(4)').eq(index).find("input[name='transferee_certificate[]']").val(value["certificate"]);
										});
			                 			// console.log(transferor_array);
			                 			// console.log(transferee_array);
			                 			// console.log($('.transferor_table_add td:nth-child(6)').eq(2));
			                 		}
			                 		else
			                 		{
			                 			$(".cert_remind_tag").text("* Below certificate number are suggested by the system, please save it before you proceed to the next page.");
			                 			
									    $('.transferor_table_add td:nth-child(3)').each(function() 
										{
											var transferor_row = $(this);
											var number_of_share_to_transfer = parseInt(transferor_row.parent().find(".transfered_share").text().replace(/\,/g,''));
											var amount_of_left, old_number_of_share = parseInt(transferor_row.text().replace(/\,/g,''));
											var transferor_next_name = transferor_row.parent().find(".transferor_name").text(), numberOfShareTransfer = 0;
											console.log(number_of_share_to_transfer);
											if(0 > number_of_share_to_transfer)
											{	
												console.log(finish_minus_calculation);
												console.log(new_number_of_share);
												if(new_number_of_share == 0 && transferor_next_name == transferor_first_name)
												{
													if(!finish_minus_calculation)
													{
														new_number_of_share = old_number_of_share - (-(number_of_share_to_transfer));
														if(new_number_of_share > 0)
														{
															numberOfShareTransfer = number_of_share_to_transfer;
															//finish_minus_calculation = true;
														}
													}

													if(number_of_loop > 0 && new_number_of_share == 0)
													{
														finish_minus_calculation = true;
													}
												}
												else if(0 > new_number_of_share && transferor_next_name == transferor_first_name)
												{
													numberOfShareTransfer = new_number_of_share;
													new_number_of_share = old_number_of_share - (-(new_number_of_share));
												}
												else
												{
													transferor_first_name = transferor_next_name;
													new_number_of_share = old_number_of_share - (-(number_of_share_to_transfer));
													numberOfShareTransfer = number_of_share_to_transfer;
													finish_minus_calculation = false;
												}
												
												if(finish_minus_calculation)
												{
													transferor_row.parent().find(".new_number_of_share").val("");
													transferor_row.parent().find(".latest_certificate").val("");
													transferor_row.parent().find(".assign_number_of_share").removeAttr("readonly");
													transferor_row.parent().find(".assign_number_of_share").val("");
													transferor_row.parent().find(".latest_certificate").removeAttr("readonly");
													//transferor_row.parent().find(".cancel_shares_cert").removeAttr("disabled");
													//transferor_row.parent().find(":checkbox.cancel_shares_cert").prop('checked', true);
												}
												else
												{
													if(0 >= new_number_of_share)
													{
														transferor_row.parent().find(".new_number_of_share").val(0);
														transferor_row.parent().find(".latest_certificate").val("NA");
														transferor_row.parent().find(".assign_number_of_share").removeAttr("readonly");
														transferor_row.parent().find(".assign_number_of_share").val(addCommas(old_number_of_share));

														//transferor_row.parent().find(".cancel_shares_cert").removeAttr("disabled");
														//transferor_row.parent().find(":checkbox.cancel_shares_cert").prop('checked', true);
														
														// transferor_row.parent().find(".new_number_of_share").removeAttr("readonly");
														transferor_row.parent().find(".latest_certificate").removeAttr("readonly");
													}
													else if (new_number_of_share > 0)
													{
														latest_certificate_no = latest_certificate_no + 1;
														transferor_row.parent().find(".new_number_of_share").val(addCommas(new_number_of_share));
														transferor_row.parent().find(".latest_certificate").val(latest_certificate_no);
														transferor_row.parent().find(".assign_number_of_share").removeAttr("readonly");
														transferor_row.parent().find(".assign_number_of_share").val(addCommas(-(numberOfShareTransfer)));
														// transferor_row.parent().find(".cancel_shares_cert").removeAttr("disabled");
														// transferor_row.parent().find(":checkbox.cancel_shares_cert").prop('checked', true);

														// transferor_row.parent().find(".new_number_of_share").removeAttr("readonly");
														transferor_row.parent().find(".latest_certificate").removeAttr("readonly");
														new_number_of_share = 0;

														finish_minus_calculation = true;
														
													}
												}
											}

											number_of_loop++;
											// console.log(parseFloat(transferor_row.parent().find(".transfered_share").text().replace(/\,/g,'')).toFixed(2));
											// console.log(parseFloat(transferor_row.text().replace(/\,/g,'')).toFixed(2));
										});

										$(".hidden_latest_cert_no").val(latest_certificate_no);
									}
				      			}

			                	if(response[0]["transaction_member"] != false)
				            	{
								    var transaction_share_transfer = response[0]["transaction_member"];

								    $(".confirm_member_info_for_each_company").remove();
								    if(transaction_share_transfer)
								    {
								        var row_id = 0;
								        for(var i = 0; i < transaction_share_transfer.length; i++)
								        {
								            row_id++;
								            if(transaction_share_transfer[i]["sharetype"] == "Others")
								            {
								                var sharetype = "(" +transaction_share_transfer[i]["other_class"]+ ")" ;
								            }
								            else
								            {
								                var sharetype = "";
								            }

								            $b=""; 
								            $b += '<tr class="confirm_member_info_for_each_company">';
								            $b += '<td style="text-align: right;width:10px">'+row_id+'</td>';
								            $b += '<td>'+((transaction_share_transfer[i]["from_officer_identification_no"] != null)?transaction_share_transfer[i]["from_officer_identification_no"] : (transaction_share_transfer[i]["from_officer_company_register_no"] != null?transaction_share_transfer[i]["from_officer_company_register_no"]:transaction_share_transfer[i]["from_client_regis_no"]))+'</td>';
								            $b += '<td>'+((transaction_share_transfer[i]["from_officer_name"] != null)?transaction_share_transfer[i]["from_officer_name"] : (transaction_share_transfer[i]["from_officer_company_name"] != null?transaction_share_transfer[i]["from_officer_company_name"]:transaction_share_transfer[i]["from_client_company_name"]))+'</td>';
								            $b += '<td>'+((transaction_share_transfer[i]["to_officer_identification_no"] != null)?transaction_share_transfer[i]["to_officer_identification_no"] : (transaction_share_transfer[i]["to_officer_company_register_no"] != null?transaction_share_transfer[i]["to_officer_company_register_no"]:transaction_share_transfer[i]["to_client_regis_no"]))+'</td>';
								            $b += '<td>'+((transaction_share_transfer[i]["to_officer_name"] != null)?transaction_share_transfer[i]["to_officer_name"] : (transaction_share_transfer[i]["to_officer_company_name"] != null?transaction_share_transfer[i]["to_officer_company_name"]:transaction_share_transfer[i]["to_client_company_name"]))+'</td>';
								            
								            $b += '<td>'+transaction_share_transfer[i]["sharetype"] + " " + sharetype+'</td>';
								            // $b += '<td style="text-align:center">'+transaction_share_transfer[i]["to_new_certificate_no"]+'</td>';
								            $b += '<td style="text-align:center">'+transaction_share_transfer[i]["currency"]+'</td>';
								            $b += '<td style="text-align:right">'+addCommas(transaction_share_transfer[i]["to_number_of_share"])+'</td>';
								            $b += '</tr>';

								            $("#confirm_transfer_info_add").append($b);
								        }
								    }

								   //  for(var i = 0; i < transaction_member.length; i++)
								   //  {
								   //      if(0 > transaction_member[i]["number_of_share"])
								   //      {
								   //          $a0=""; 
								   //          $a0 += '<div class="tr editing transfer transfer_coll" method="post" name="form'+i+'" id="confirmation_form'+i+'" num="'+i+'">';
								   //          /*$a += '<div class="td">'+$count_allotment+'</div>';*/
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px">'+(transaction_member[i]["identification_no"]!=null ? transaction_member[i]["identification_no"] : (transaction_member[i]["register_no"]!=null ? transaction_member[i]["register_no"] : transaction_member[i]["registration_no"]))+'</div></div>';
								   //          /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md" id="name" style="width: 200px; text-align:left">'+(transaction_member[i]["name"]!=null ? transaction_member[i]["name"] : (transaction_member[i]["client_company_name"]!=null ? transaction_member[i]["client_company_name"] : transaction_member[i]["company_name"]))+'</div></div>';
								   //          $a0 += '<div class="td"><div style="text-align:right;width: 180px; text-align:right;" class="transfer_group mb-md" id="current_share"></div></div>';
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 200px; text-align:right;">'+addCommas(Math.abs(transaction_member[i]["number_of_share"]))+'</div></div>';
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 100px; text-align:right;">'+addCommas(Math.abs(transaction_member[i]["consideration"]))+'</div></div>';
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md" style="width: 100px">'+transaction_member[i]["certificate_no"]+'</div></div>';
								   //          $a0 += '</div>';

								   //          $("#confirmation_transfer_add").append($a0); 

								   //          $.ajax({
								   //              type: "POST",
								   //              url: "transaction/get_transfer_people",
								   //              data: {"client_member_share_capital_id":$('select[name="class"]').val(), "company_code":$("#company_code").val(), "transaction_id":transaction_id}, // <--- THIS IS THE CHANGE
								   //              dataType: "json",
								   //              async: false,
								   //              success: function(response){
								   //                  $('#loadingWizardMessage').hide();
								   //                  var allotmentPeople = response;
										 //            for(var a = 0; a < allotmentPeople.length; a++)
										 //            {	
										 //            	console.log(allotmentPeople);
										 //            	if(transaction_member[i]["identification_no"] == allotmentPeople[a]['identification_no'] && allotmentPeople[a]['identification_no'] != null || transaction_member[i]["register_no"] == allotmentPeople[a]['register_no'] && allotmentPeople[a]['register_no'] != null || transaction_member[i]["registration_no"] == allotmentPeople[a]['registration_no'] && allotmentPeople[a]['registration_no'] != null)
											// 			{
											// 				$("#confirmation_form"+i+" #current_share").text(addCommas(parseInt(allotmentPeople[a]['number_of_share'])));
											// 			}
											// 		}
											// 	}
											// });

								   //      }
								   //      else if(transaction_member[i]["number_of_share"] > 0)
								   //      {
								   //          //console.log(transfer);
								   //          $atoe =""; 
								   //          $atoe += '<div class="tr editing to to_coll" method="post" name="form_to'+i+'" id="form_to'+i+'" num_to="'+i+'">';
								            

								   //          /*$a += '<div class="td">'+$count_allotment+'</div>';*/
								   //          $atoe += '<div class="td"><div class="transfer_group">'+(transaction_member[i]["identification_no"]!=null ? transaction_member[i]["identification_no"] : (transaction_member[i]["register_no"]!=null ? transaction_member[i]["register_no"] : transaction_member[i]["registration_no"]))+'</div></div>';
								   //          /*$a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="name['+0+']" class="form-control" value=""/></div></div>';
								   //          $a0 += '<div class="td"><div class="transfer_group mb-md"><input type="text" name="number_of_share['+0+']" class="form-control" value=""/></div></div>';*/
								   //          $atoe += '<div class="td"><div class="transfer_group mb-md" id="name_to" style="width: 200px; text-align:left">'+(transaction_member[i]["company_name"]!=null ? transaction_member[i]["company_name"] : (transaction_member[i]["name"]!=null ? transaction_member[i]["name"] : transaction_member[i]["client_company_name"]))+'</div><input type="hidden" class="form-control" name="to_person_name['+i+']" value="'+(transaction_member[i]["company_name"]!=null ? transaction_member[i]["company_name"] : (transaction_member[i]["name"]!=null ? transaction_member[i]["name"] : transaction_member[i]["client_company_name"]))+'" id="to_person_name"/></div>';
								   //          $atoe += '<div class="td"><div class="transfer_group mb-md" style="width: 200px; text-align:right;">'+addCommas(transaction_member[i]["number_of_share"])+'</div></div>';
								   //          /*$ato += '<div class="td"><div class="transfer_group mb-md"><input type="text" class="form-control" name="certificate['+0+']" value="" id="certificate"/></div></div>';*/
								   //          $atoe += '<div class="td"><div class="transfer_group mb-md" style="width: 100px">'+transaction_member[i]["certificate_no"]+'</div></div>';

								   //          $atoe += '</div>';

								   //          $("#confirmation_transfer_to_add").append($atoe); 
								   //      }
								        
								   //  }
								   //  sum_total();
				            	}
			                }
			            });
	
						$('.assign_number_of_share').change(function() {
							var numberOfShareToTransferRow = $(this);
							var $tr = $(this).closest('tr');
					    	var myRow = $("#transferee_table tr").index($tr);
							var transferor_name = numberOfShareToTransferRow.parent().parent().find(".transferor_name").text();
							var current_old_nfs = parseInt(numberOfShareToTransferRow.parent().parent().find(".old_nfs").text().replace(/\,/g,''));
							var transfered_share = -(parseInt(numberOfShareToTransferRow.parent().parent().find(".transfered_share").text().replace(/\,/g,'')));
							var latest_certificate_number = parseInt($(".hidden_latest_cert_no").val());
							var cert_value = numberOfShareToTransferRow.parent().parent().find(".latest_certificate").val();

							var new_number_of_share = 0;
							var new_number_of_share_transfer = 0;
							//console.log(numberOfShareToTransferRow.val());
							if(numberOfShareToTransferRow.val() == 0 || numberOfShareToTransferRow.val() == "")
							{
								numberOfShareToTransferRow.val(-1);
								numberOfShareToTransferRow.parent().parent().find(".new_number_of_share").val("");
								numberOfShareToTransferRow.parent().parent().find(".latest_certificate").val("");
							}

							$('.transferor_table_add td:nth-child(1)').each(function() 
							{
								var transferor_row = $(this);
								var check_transferor_name = $(this).text();
								
								if(check_transferor_name == transferor_name)
								{
									var number_of_share_to_transfer = parseInt(transferor_row.parent().find(".assign_number_of_share").val().replace(/\,/g,''));
									//console.log(number_of_share_to_transfer);

									if(isNaN(number_of_share_to_transfer))
									{
										number_of_share_to_transfer = 0;
									}

									new_number_of_share_transfer = new_number_of_share_transfer + number_of_share_to_transfer;
								}
								//console.log(check_transferor_name);
							});
							// console.log(new_number_of_share_transfer);
							// console.log(transfered_share);
							var number_of_share_left = 0;
							console.log(transfered_share);
							console.log(new_number_of_share_transfer);
							if(new_number_of_share_transfer > transfered_share)
							{
								$(this).val("");
								$(this).parent().parent().find(".new_number_of_share").val("");
								$(this).parent().parent().find(".latest_certificate").val("");
								toastr.warning("The total value cannot bigger that total number of shares to transfer.", "Warning");
							}
							else if(transfered_share > new_number_of_share_transfer)
							{
								var total_number_of_share_left = transfered_share;
								
								// $('.transferor_table_add td:nth-child(1)').each(function() 
								// {
								var transferor_row = $(this);
								var $trNumberST = $(this).closest('tr');
								//var myRowSTF = $("#transferee_table tr").index($trNumberST);
								var check_transferor_name = $(this).text();
								var check_old_number_of_share = parseInt(transferor_row.parent().parent().find(".old_nfs").text().replace(/\,/g,''));
								//console.log(check_old_number_of_share);
								//console.log(total_number_of_share_left);
								// if(check_transferor_name == transferor_name)
								// {
								var number_of_share_to_transfer = parseInt(transferor_row.parent().parent().find(".assign_number_of_share").val().replace(/\,/g,''));
									//console.log(number_of_share_to_transfer);
								// console.log(number_of_share_to_transfer);
								// console.log(check_old_number_of_share);
								if(check_old_number_of_share > number_of_share_to_transfer)
								{
									if(isNaN(number_of_share_to_transfer) || number_of_share_to_transfer > 0)//|| number_of_share_to_transfer > 0
									{
										if(total_number_of_share_left >= check_old_number_of_share)
										{
											var latest_number_of_share = check_old_number_of_share - number_of_share_to_transfer;
											transferor_row.parent().parent().find(".new_number_of_share").val(addCommas(parseInt(latest_number_of_share)));
											//transferor_row.parent().find(".latest_certificate").val(latest_certificate_number + 1);
											// $(".hidden_latest_cert_no").val(latest_certificate_number + 1);
											total_number_of_share_left = total_number_of_share_left - number_of_share_to_transfer;
											// transferor_row.parent().parent().find(".assign_number_of_share").val(addCommas(parseInt(check_old_number_of_share)));
											// total_number_of_share_left = total_number_of_share_left - check_old_number_of_share;

											// transferor_row.parent().parent().find(".new_number_of_share").val(0);
											transferor_row.parent().parent().find(".latest_certificate").val("");
										}
										else if(check_old_number_of_share > total_number_of_share_left)
										{
											if(total_number_of_share_left > 0)
											{
												if(number_of_share_to_transfer > 0)
												{
													var latest_number_of_share = check_old_number_of_share - number_of_share_to_transfer;
													transferor_row.parent().parent().find(".new_number_of_share").val(addCommas(parseInt(latest_number_of_share)));
													//transferor_row.parent().find(".latest_certificate").val(latest_certificate_number + 1);
													// $(".hidden_latest_cert_no").val(latest_certificate_number + 1);
													total_number_of_share_left = total_number_of_share_left - number_of_share_to_transfer;
												}
												else
												{
													var latest_number_of_share = check_old_number_of_share - total_number_of_share_left;
													transferor_row.parent().parent().find(".assign_number_of_share").val(addCommas(parseInt(total_number_of_share_left)));
													total_number_of_share_left = total_number_of_share_left - total_number_of_share_left;

													transferor_row.parent().parent().find(".new_number_of_share").val(addCommas(parseInt(latest_number_of_share)));
													// if(cert_value != "" && cert_value != "NA")
													// {
													// 	transferor_row.parent().find(".latest_certificate").val(parseInt(cert_value) + 1);
													// }
													// else if(cert_value == "NA")
													// {
													// 	transferor_row.parent().find(".latest_certificate").val(latest_certificate_number);
													// }
													// else
													// {
													// 	transferor_row.parent().find(".latest_certificate").val(latest_certificate_number + 1);
													// 	$(".hidden_latest_cert_no").val(latest_certificate_number + 1);
													// }
												}
											}
											else
											{
												transferor_row.parent().parent().find(".assign_number_of_share").val("");
												transferor_row.parent().find(".new_number_of_share").val("");
												transferor_row.parent().find(".latest_certificate").val("");
											}
										}
										else
										{
											transferor_row.parent().parent().find(".assign_number_of_share").val("");
											transferor_row.parent().parent().find(".new_number_of_share").val("");
											transferor_row.parent().parent().find(".latest_certificate").val("");
										}
									}
									else if(number_of_share_to_transfer == 0)
									{
										numberOfShareToTransferRow.val(-1);
										numberOfShareToTransferRow.parent().parent().find(".new_number_of_share").val("");
										numberOfShareToTransferRow.parent().parent().find(".latest_certificate").val("");
									}
								}
								else
								{
									transferor_row.parent().parent().find(".assign_number_of_share").val("");
									transferor_row.parent().parent().find(".new_number_of_share").val("");
									transferor_row.parent().parent().find(".latest_certificate").val("");
									toastr.warning("The number of shares to transfer value cannot bigger that old number of shares.", "Warning");
								}
									//}
								//});
							}
							else
							{
								
								var new_number_of_share = current_old_nfs - parseInt($(this).parent().find(".assign_number_of_share").val().replace(/\,/g,''));
								console.log(new_number_of_share);
								$(this).parent().parent().find(".new_number_of_share").val(addCommas(new_number_of_share));
								if(new_number_of_share == 0)
								{
									$(this).parent().parent().find(".latest_certificate").val("NA");
								}
							}

							if(0 > numberOfShareToTransferRow.val())
							{
								numberOfShareToTransferRow.val("");
							}   
					    });
            		}
            		else if($('.transaction_task option:selected').val() == 11)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_share_allotment_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){

			                	$("#transaction_share_allotment").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_share_allotment .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_share_allotment .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);

			                	if(response[0]["transaction_meeting_date"])
			                	{
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	$(".confirmation_director_meeting_date").html(response[0]["transaction_meeting_date"][0]["director_meeting_date"]);
				                	$(".confirmation_director_meeting_time").html(response[0]["transaction_meeting_date"][0]["director_meeting_time"]);
				                	$(".confirmation_member_meeting_date").html(response[0]["transaction_meeting_date"][0]["member_meeting_date"]);
				                	$(".confirmation_member_meeting_time").html(response[0]["transaction_meeting_date"][0]["member_meeting_time"]);
				                	
				                	if(response[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_meeting_date"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_meeting_date"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_meeting_date"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_meeting_date"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_meeting_date"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_meeting_date"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_meeting_date"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_meeting_date"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_meeting_date"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_meeting_date"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_meeting_date"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_meeting_date"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_meeting_date"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                }
				                else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }
			                	
			                	if(response[0]["transaction_member"] != false)
				            	{
				            		for(var z = 0; z < response[0]["transaction_member"].length; z++)
				            		{
					            		$a = ""; 
								        $a += '<tr class="confirmation_share_allotment">';
								        $a += '<td><div class="input-group mb-md">'+(response[0]["transaction_member"][z]["identification_no"]!=null ? response[0]["transaction_member"][z]["identification_no"] : (response[0]["transaction_member"][z]["register_no"]!=null ? response[0]["transaction_member"][z]["register_no"] : response[0]["transaction_member"][z]["registration_no"]))+'</div><div class="input-group mb-md name">'+(response[0]["transaction_member"][z]["company_name"]!=null ? response[0]["transaction_member"][z]["company_name"] : (response[0]["transaction_member"][z]["name"] != null ? response[0]["transaction_member"][z]["name"] : response[0]["transaction_member"][z]["client_company_name"]))+'</div></td>';
								        $a += '<td><div class="input-group mb-md" id="member_class'+z+'">'+response[0]["transaction_member"][z]["sharetype"]+'</div><div class="input-group mb-md " id="other_class'+z+'" style="display: none">'+response[0]["transaction_member"][z]["other_class"]+'</div><div class="input-group mb-md">'+response[0]["transaction_member"][z]["currency"]+'</div></td>';
								        $a += '<td><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["number_of_share"])+'</div><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["amount_share"])+'</div></td>';
								        $a += '<td><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["no_of_share_paid"])+'</div><div class="input-group mb-md">'+addCommas(response[0]["transaction_member"][z]["amount_paid"])+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_member"][z]["certificate_no"]+'</div></td>';
								        $a += '</tr>';

								        $("#confirmation_share_allotment_table").append($a); 

								        if(response[0]["transaction_member"][z]["sharetype"] == "Others")
								        {
								        	//console.log(response[0]["transaction_member"][z]["sharetype"]);
								        	$("#other_class"+z+"").show();
								        	$("#member_class"+z+"").hide();
								        	//console.log($("#confirmation_member_table #other_class"+z+""));
											//tr.find("DIV#other_class").attr("hidden","true");
								        }
								        else if(response[0]["transaction_member"][z]["sharetype"] == "Ordinary Share")
								        {
								        	$("#member_class"+z+"").show();
								        	$("#other_class"+z+"").hide();
								        }
								    }
				            	}
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 12)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_change_company_name_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_change_company_name").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_change_company_name .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_change_company_name .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);

			                	if(response[0]["transaction_meeting_date"])
			                	{
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	$(".confirmation_director_meeting_date").html(response[0]["transaction_meeting_date"][0]["director_meeting_date"]);
				                	$(".confirmation_director_meeting_time").html(response[0]["transaction_meeting_date"][0]["director_meeting_time"]);
				                	$(".confirmation_member_meeting_date").html(response[0]["transaction_meeting_date"][0]["member_meeting_date"]);
				                	$(".confirmation_member_meeting_time").html(response[0]["transaction_meeting_date"][0]["member_meeting_time"]);
				                	
				                	if(response[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_meeting_date"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_meeting_date"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_meeting_date"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_meeting_date"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_meeting_date"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_meeting_date"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_meeting_date"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_meeting_date"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_meeting_date"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_meeting_date"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_meeting_date"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_meeting_date"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_meeting_date"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                }
				                else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }

			                	if(response[0]['transaction_change_company_name'] != false)
			                	{
								    $("#transaction_confirmation_change_company_name #new_company_name").append(response[0]['transaction_change_company_name'][0]['new_company_name']);
			                		$("#transaction_confirmation_change_company_name #confirm_effective_date").append(response[0]['transaction_change_company_name'][0]['effective_date']);
			                	}

			                	
			      //           	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	//$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                 	var b = "";

								 //        b += '<tr class="row_doc_pending">';
								 //        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        b += '</tr>';
								            

								 //        $("#pending_doc_body").append(b);
								 //    }
			      //               }

			      //               $(".lodgement_date").val(response[0]['transaction_change_company_name'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 15)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_agm_ar_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_agm_ar").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	if(response[0]["transaction_meeting_date"])
			                	{
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	$(".confirmation_director_meeting_date").html(response[0]["transaction_meeting_date"][0]["director_meeting_date"]);
				                	$(".confirmation_director_meeting_time").html(response[0]["transaction_meeting_date"][0]["director_meeting_time"]);
				                	$(".confirmation_member_meeting_date").html(response[0]["transaction_meeting_date"][0]["member_meeting_date"]);
				                	$(".confirmation_member_meeting_time").html(response[0]["transaction_meeting_date"][0]["member_meeting_time"]);
				                	
				                	if(response[0]["transaction_meeting_date"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_meeting_date"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_meeting_date"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_meeting_date"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_meeting_date"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_meeting_date"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_meeting_date"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_meeting_date"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_meeting_date"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_meeting_date"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_meeting_date"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_meeting_date"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_meeting_date"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_meeting_date"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_meeting_date"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                }
				                else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }

			                	if(response[0]['transaction_agm_ar'] != false)
			                	{
			                		$("#confirmation_company_name").append(response[0]['transaction_agm_ar'][0]['company_name']);
			                		$("#confirmation_activity_status").append(response[0]['transaction_agm_ar'][0]['activity_status_name']);
			                		$("#confirmation_solvency_status").append(response[0]['transaction_agm_ar'][0]['solvency_status_name']);
			                		$("#confirmation_small_company").append(response[0]['transaction_agm_ar'][0]['small_company_decision']);
			                		$("#confirmation_xbrl").append(response[0]['transaction_agm_ar'][0]['xbrl_list_name']);

			                		$("#confirmation_first_agm").append(response[0]['transaction_agm_ar'][0]['is_first_agm']);
			                		$("#confirmation_fye").append(response[0]['transaction_agm_ar'][0]['year_end_date']);
			                		$("#confirmation_shorter_notice").append(response[0]['transaction_agm_ar'][0]['is_shorter_notice']);
			                		$("#confirmation_notice_date").append(response[0]['transaction_agm_ar'][0]['notice_date']);
			                		$("#confirmation_require_hold_agm_list_name").append(response[0]['transaction_agm_ar'][0]['require_hold_agm_list_name']);

			                		if(response[0]['transaction_agm_ar'][0]['require_hold_agm_list'] == 1)
		                            {
		                                $(".conf_shorter_notice").show();
		                                $(".conf_notice_date").show();
		                                $(".conf_agm_time").show();
		                                $(".conf_agm_date").show();
		                                $(".conf_date_fs").hide();
		                            }
		                            else if(response[0]['transaction_agm_ar'][0]['require_hold_agm_list'] == 2 || response[0]['transaction_agm_ar'][0]['require_hold_agm_list'] == 4)
		                            {
		                                $(".conf_shorter_notice").hide();
		                                $(".conf_notice_date").hide();
		                                $(".conf_agm_time").hide();
		                                $(".conf_agm_date").hide();
		                                $(".conf_date_fs").show();
		                            }
		                            else if(response[0]['transaction_agm_ar'][0]['require_hold_agm_list'] == 3)
		                            {
		                                $(".conf_shorter_notice").hide();
		                                $(".conf_notice_date").hide();
		                                $(".conf_agm_time").hide();
		                                $(".conf_agm_date").hide();
		                                $(".conf_date_fs").hide();
		                            }
		                            else
		                            {
		                                $(".conf_shorter_notice").show();
		                                $(".conf_notice_date").show();
		                                $(".conf_agm_time").show();
		                                $(".conf_agm_date").hide();
		                                $(".conf_date_fs").hide();
		                            }
			                		$("#confirmation_agm").append(response[0]['transaction_agm_ar'][0]['agm_date']);
			                		$("#confirmation_date_fs_sent_to_members").append(response[0]['transaction_agm_ar'][0]['date_fs_sent_to_member']);
			                		$("#confirmation_agm_time").append(response[0]['transaction_agm_ar'][0]['agm_time']);
			                		//$("#confirmation_reso_time").append(response[0]['transaction_agm_ar'][0]['reso_time']);


			                		// $("#confirmation_controller_exempt").append(response[0]['transaction_agm_ar'][0]['cont_exemption_name']);
			                		// $("#confirmation_controller_kept").append(response[0]['transaction_agm_ar'][0]['cont_is_kept_at']);
			                		// $("#confirmation_director_exempt").append(response[0]['transaction_agm_ar'][0]['dir_exemption_name']);
			                		// $("#confirmation_director_kept").append(response[0]['transaction_agm_ar'][0]['dir_is_kept_at']);
			                		
			                		
			                		//$("#confirmation_epc_status").append(response[0]['transaction_agm_ar'][0]['is_epc_status']);
			                		//$("#confirmation_small_company").append(response[0]['transaction_agm_ar'][0]['small_company_decision']);
			                		$("#confirmation_financial_statements_audited").append(response[0]['transaction_agm_ar'][0]['audited_fs_decision']);
			                		$("#confirmation_share_transfer").append(response[0]['transaction_agm_ar'][0]['share_transfer_name']);
			                		$("#confirmation_register_of_controller").append(response[0]['transaction_agm_ar'][0]['register_of_controller']);
			                		$("#confirmation_register_of_nominee_directors").append(response[0]['transaction_agm_ar'][0]['register_of_nominee_director']);
			                		
			                		$("#confirmation_chairman").append(response[0]['transaction_agm_ar'][0]['chairman_name']);
			                		
			                		$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	if(response[0]["transaction_agm_ar"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_agm_ar"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_agm_ar"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_agm_ar"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_agm_ar"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_agm_ar"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_agm_ar"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_agm_ar"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_agm_ar"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_agm_ar"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_agm_ar"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_agm_ar"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_agm_ar"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_agm_ar"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_agm_ar"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_agm_ar"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }
			                	}
			                	else
				                {
				                	$("#confirmation_tr_registered_edit").hide();
			                        $("#confirmation_tr_local_edit").hide();
			                        $("#confirmation_tr_foreign_edit").hide();
				                }

			                	if(response[0]['transaction_agm_ar_director_fee'] != false)
					            {
					                if(response[0]['transaction_agm_ar_director_fee'][0]["id"] != null)
					                {
					                    $('#confirm_director_fee').prop('checked', true);
					                    $(".confirm_director_fee_div").show();
					                

						                for(var i = 0; i < response[0]['transaction_agm_ar_director_fee'].length; i++)
									    {
									        $a="";
									        $a += '<tr class="row_director_fee">';
									        $a += '<td><input type="text" style="text-transform:uppercase;" name="director_fee_name[]" id="name" class="form-control" value="'+ response[0]['transaction_agm_ar_director_fee'][i]["director_fee_name"] +'" readonly/><input type="hidden" class="form-control" name="director_fee_identification_register_no[]" id="director_fee_identification_register_no" value="'+ response[0]['transaction_agm_ar_director_fee'][i]["director_fee_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="director_fee_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_id[]" id="officer_id" value="'+response[0]['transaction_agm_ar_director_fee'][i]["director_fee_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="director_fee_officer_field_type[]" id="officer_field_type" value="'+response[0]['transaction_agm_ar_director_fee'][i]["director_fee_officer_field_type"]+'"/></div></td>';
									        $a += '<td><input type="text" style="text-transform:uppercase;" name="currency[]" id="currency numberdes" class="form-control" value="'+addCommas(response[0]['transaction_agm_ar_director_fee'][i]["currency"])+'" readonly/></td>';
									        $a += '<td><input type="text" style="text-transform:uppercase;text-align:right;" name="salary[]" id="salary numberdes" class="form-control" value="'+addCommas(response[0]['transaction_agm_ar_director_fee'][i]["salary"])+'" readonly/></td>';
									        $a += '<td><input type="text" style="text-transform:uppercase;text-align:right;" name="cpf[]" id="cpf numberdes" class="form-control" value="'+addCommas(response[0]['transaction_agm_ar_director_fee'][i]["cpf"])+'" readonly/></td>';
									        $a += '<td><input type="text" style="text-transform:uppercase;text-align:right;" name="director_fee[]" id="director_fee numberdes" class="form-control" value="'+addCommas(response[0]['transaction_agm_ar_director_fee'][i]["director_fee"])+'" readonly/></td>';
									        $a += '<td><input type="text" style="text-transform:uppercase;text-align:right;" name="total_director_fee[]" id="total_director_fee numberdes" class="form-control" value="'+addCommas(response[0]['transaction_agm_ar_director_fee'][i]["total_director_fee"])+'" readonly/></td>';
									        $a += '</tr>';

									        $("#confirm_director_fee_add").append($a);

									    }
									}
					            }

					       //      if(response[0]['transaction_agm_ar_dividend'] != false)
					       //      {	
					       //      	var comfirm_total_number_of_share = 0;
					       //          if(response[0]['transaction_agm_ar_dividend'][0]["id"] != null)
					       //          {
					       //              $('#confirm_dividend').prop('checked', true);
					       //              $(".confirm_dividend_div").show();
					                
						      //           for(var i = 0; i < response[0]['transaction_agm_ar_dividend'].length; i++)
									   //  {
									   //      $a="";
									   //      $a += '<tr class="row_dividend" data-numberOfShare="'+response[0]['transaction_agm_ar_dividend'][i]["number_of_share"]+'">';
									   //      $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend_name[]" id="name" class="form-control" value="'+response[0]['transaction_agm_ar_dividend'][i]["dividend_name"] +'" readonly/><input type="hidden" class="form-control" name="dividend_identification_register_no[]" id="dividend_identification_register_no" value="'+ response[0]['transaction_agm_ar_dividend'][i]["dividend_identification_no"]+'"/><div class="hidden"><input type="text" class="form-control" name="dividend_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_id[]" id="officer_id" value="'+response[0]['transaction_agm_ar_dividend'][i]["dividend_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="dividend_officer_field_type[]" id="officer_field_type" value="'+response[0]['transaction_agm_ar_dividend'][i]["dividend_officer_field_type"]+'"/></div></td>';
									   //      $a += '<td><input type="text" style="text-transform:uppercase;" name="dividend[]" id="dividend_fee" class="form-control" value="'+addCommas(response[0]['transaction_agm_ar_dividend'][i]["dividend_fee"])+'" readonly/><input type="hidden" name="number_of_share[]" id="number_of_share" class="form-control" value="'+response[0]['transaction_agm_ar_dividend'][i]["number_of_share"]+'"/></td>'
									   //      $a += '</tr>';

									   //      $("#confirm_dividend_add").append($a);

									   //      comfirm_total_number_of_share = comfirm_total_number_of_share + parseInt(response[0]['transaction_agm_ar_dividend'][i]["number_of_share"]);
									   //  }
									   //  $("#confirm_total_dividend").val(addCommas(response[0]['transaction_agm_ar_dividend'][0]["total_dividend_declared"]));
								    // }
					       //      }

					            if(response[0]['transaction_agm_ar_amount_due'] != false)
					            {
					                if(response[0]['transaction_agm_ar_amount_due'][0]["id"] != null)
					                {
					                    $('#confirm_amount_due_from_director').prop('checked', true);
					                    $(".confirm_amount_due_from_director_div").show();
					                
						                for(var i = 0; i < response[0]['transaction_agm_ar_amount_due'].length; i++)
						    			{
									        $a="";
									        $a += '<tr class="row_amount_due">';
									        $a += '<td><input type="text" style="text-transform:uppercase;" name="amount_due_name[]" id="name" class="form-control" value="'+ response[0]['transaction_agm_ar_amount_due'][i]["amount_due_from_director_name"] +'" readonly/><input type="hidden" class="form-control" name="amount_due_identification_register_no[]" id="amount_due_identification_register_no" value="'+ response[0]['transaction_agm_ar_amount_due'][i]["amount_due_from_director_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="amount_due_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_id[]" id="officer_id" value="'+response[0]['transaction_agm_ar_amount_due'][i]["amount_due_from_director_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="amount_due_officer_field_type[]" id="officer_field_type" value="'+response[0]['transaction_agm_ar_amount_due'][i]["amount_due_from_director_officer_field_type"]+'"/></div></td>';
									        $a += '<td><input type="text" style="text-align:right;" name="amount_due[]" id="amount_due" class="form-control numberdes" value="'+addCommas(response[0]['transaction_agm_ar_amount_due'][i]["amount_due_from_director_fee"])+'" readonly/></td>';
									        $a += '</tr>';

									        $("#confirm_amount_due_from_director_add").append($a);

									    }
									}
								    
					            }

					            if(response[0]['transaction_agm_ar_director_retire'])
					            {
					                if(response[0]['transaction_agm_ar_director_retire'][0]["id"] != null)
					                {
					                    $('#confirm_director_retirement').prop('checked', true);
					                    $(".confirm_director_retirement_div").show();
					                
						                for(var i = 0; i < response[0]['transaction_agm_ar_director_retire'].length; i++)
									    {
									        $a="";
									        $a += '<tr class="row_director_retirement">';
									        $a += '<td>'+(i + 1)+'<input type="hidden" class="form-control" name="director_retiring_client_officer_id[]" value="'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_officer_id']+'"/></td>';
									        $a += '<td>'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_identification_no']+'<input type="hidden" class="form-control" name="director_retiring_identification_no[]" value="'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_identification_no']+'"/></td>';
									        $a += '<td>'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_name']+'<input type="hidden" class="form-control" name="director_retiring_name[]" value="'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_name']+'"/></td>';
									        $a += '<td><input type="checkbox" name="director_retiring_checkbox" '+((response[0]['transaction_agm_ar_director_retire'][i]["director_retiring_checkbox"] == "1")?'checked':'')+' disabled="true"/><input type="hidden" name="hidden_director_retiring_checkbox[]" value="'+response[0]['transaction_agm_ar_director_retire'][i]['director_retiring_checkbox']+'"/><input type="hidden" class="form-control" name="director_retiring_officer_id[]" value="'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_officer_id']+'"/><input type="hidden" class="form-control" name="director_retiring_field_type[]" value="'+response[0]['transaction_agm_ar_director_retire'][i]['director_retire_field_type']+'"/></td>';
									        $a += '</tr>';

									        $("#confirm_director_retirement_add").append($a);

									        $("[name='director_retiring_checkbox']").bootstrapSwitch({
									            //state: state_checkbox,
									            size: 'small',
									            onColor: 'primary',
									            onText: 'YES',
									            offText: 'NO',
									            // Text of the center handle of the switch
									            labelText: '&nbsp',
									            // Width of the left and right sides in pixels
									            handleWidth: '20px',
									            // Width of the center handle in pixels
									            labelWidth: 'auto',
									            baseClass: 'bootstrap-switch',
									            wrapperClass: 'wrapper'


									        });

									        // Triggered on switch state change.
									        $("[name='director_retiring_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
									            //console.log($(event.target));
									            if(state == true)
									            {
									                $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(1);

									            }
									            else
									            {
									               $(event.target).parent().parent().parent().find("[name='hidden_director_retiring_checkbox[]']").val(0);
									            }
									        });
									    }
									}
						    			
					            }

					            if(response[0]['transaction_agm_ar_reappoint_auditor'])
							    {
							        if(response[0]['transaction_agm_ar_reappoint_auditor'][0]["id"] != null)
							        {
							            $('#confirm_reappointment_auditor').prop('checked', true);
							            $(".confirm_reappointment_auditor_div").show();
							        
								        for(var i = 0; i < response[0]['transaction_agm_ar_reappoint_auditor'].length; i++)
										{
											if(response[0]['transaction_agm_ar_reappoint_auditor'][i]["reappoint_auditor_name"] == 0)
											{
												response[0]['transaction_agm_ar_reappoint_auditor'][i]["reappoint_auditor_name"] = "";
											}
											var a ="";
											a += '<tr class="row_reappointment_auditor">';
											a += '<td><input type="text" style="text-transform:uppercase;" name="reappointment_auditor_name[]" id="name" class="form-control" value="'+ response[0]['transaction_agm_ar_reappoint_auditor'][i]["reappoint_auditor_name"] +'" readonly/><input type="hidden" class="form-control" name="reappointment_auditor_identification_register_no[]" id="reappointment_auditor_identification_register_no" value="'+ response[0]['transaction_agm_ar_reappoint_auditor'][i]["reappoint_auditor_identification_no"] +'"/><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_client_officer_id[]" id="client_officer_id" value=""/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_id[]" id="officer_id" value="'+response[0]['transaction_agm_ar_reappoint_auditor'][i]["reappoint_auditor_officer_id"]+'"/></div><div class="hidden"><input type="text" class="form-control" name="reappointment_auditor_officer_field_type[]" id="officer_field_type" value="'+response[0]['transaction_agm_ar_reappoint_auditor'][i]["reappoint_field_type"]+'"/></div></td>';
											a += '</tr>';

											$("#confirm_reappointment_auditor_add").append(a);
										}
									}
									
							    }

			                	if(response[0]["transaction_pending_documents"] != false)
			                    {
			                    	//$(".row_doc_pending").remove();
			                    	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									{
				                     	var b = "";

								        b += '<tr class="row_doc_pending">';
								        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
										{
								        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								        }
								        else
								        {
								        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								        }
								        b += '</tr>';
								            

								        $("#pending_doc_body").append(b);
								    }
			                    }

			                    $(".lodgement_date").val(response[0]['transaction_agm_ar'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 20)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_incorp_subsidiary_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_incorp_subsidiary").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	if(response[0]['transaction_incorporation_subsidiary'] != false)
			                 	{
				                	var transaction_incorporation_subsidiary = response[0]['transaction_incorporation_subsidiary'];

				                	$(".confirmation_subsidiary_name").html(transaction_incorporation_subsidiary[0]["subsidiary_name"]);
				                	$(".confirmation_country_of_incorporation").html(transaction_incorporation_subsidiary[0]["country_of_incorporation"]);
				                	$(".confirmation_currency").html(transaction_incorporation_subsidiary[0]["currency_name"]);
				                	$(".confirmation_total_investment_amount").html(transaction_incorporation_subsidiary[0]["total_investment_amount"]);
				                	$(".confirmation_corp_rep_name").html(transaction_incorporation_subsidiary[0]["name_of_corp_rep"]);
				                	$(".confirmation_corp_rep_identity_number").html(transaction_incorporation_subsidiary[0]["identity_number"]);
				                	$(".confirmation_propose_effective_date").html(transaction_incorporation_subsidiary[0]["propose_effective_date"]);
				                }
			         //        	if(response[0]['transaction_incorporation_subsidiary'] != false)
			         //        	{
								    // var transaction_incorporation_subsidiary = response[0]['transaction_incorporation_subsidiary']
								    // for(var i = 0; i < transaction_incorporation_subsidiary.length; i++)
								    // {
								    //     $a = ""; 
								    //     $a += '<tr class="row_corp_rep">';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["subsidiary_name"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["name_of_corp_rep"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["identity_number"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["corp_rep_effective_date"]+'</td>';
								    //     $a += '<td>'+transaction_incorporation_subsidiary[i]["cessation_date"]+'</td>';
								    //     $a += '</tr>';
								        
								    //     $("#transaction_confirmation_incorp_subsidiary #body_corp_rep").prepend($a); 
								    // }
			         //        	}

			                	
			                	if(response[0]["transaction_pending_documents"] != false)
			                    {
			                    	//$(".row_doc_pending").remove();
			                    	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									{
				                     	var b = "";

								        b += '<tr class="row_doc_pending">';
								        b += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								        b += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
										{
								        	b += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								        }
								        else
								        {
								        	b += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								        }
								        b += '</tr>';
								            

								        $("#pending_doc_body").append(b);
								    }
			                    }

			                    $(".lodgement_date").val(response[0]['transaction_incorporation_subsidiary'][0]['effective_date']);
				                
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 24)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_all_appoint_new_secretarial_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_officer_table").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_officer_table .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_officer_table .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);
			                	
			                	if(response[0]["transaction_client_officers"] != false)
				            	{
				            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
				            		{
					            		$a="";
								        $a += '<tr class="confirmation_officer">';
								        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
								        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
								        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
								       	// $a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
								        $a += '</tr>';
								            

								        $("#confirmation_officer_table").prepend($a);
								    }
				            	}

				     //        	if(response[0]["transaction_pending_documents"] != false)
			      //               {
			      //               	$(".row_doc_pending").remove();
			      //               	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									// {
				     //                	$a="";
								 //        $a += '<tr class="row_doc_pending">';
								 //        $a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								 //        $a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								 //        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
									// 	{
								 //        	$a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								 //        }
								 //        else
								 //        {
								 //        	$a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								 //        }
								 //        $a += '</tr>';
								            

								 //        $("#pending_doc_body").append($a);
								 //    }
			      //               }
			      //               $(".lodgement_date").val(response[0]['transaction_client_officers'][0]['effective_date']);

			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 26)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_strike_off_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_strike_off").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	if(response[0]['transaction_strike_off'] != false)
			                 	{
				                	var transaction_strike_off = response[0]['transaction_strike_off'];
				                	$("#confirmation_company_name").text(transaction_strike_off[0]["company_name"]);
				                	$("#confirmation_shorter_notice").text(transaction_strike_off[0]["is_shorter_notice"]);
				                	$("#confirmation_notice_date").text(transaction_strike_off[0]["notice_date"]);
				                	$("#confirmation_agm").text(transaction_strike_off[0]["agm_date"]);
				                	//$("#confirmation_date_fs_sent_to_members").text(transaction_strike_off[0]["date_fs_sent_to_member"]);
				                	$("#confirmation_agm_time").text(transaction_strike_off[0]["agm_time"]);

				                	$("#confirmation_register_address_edit").prop("disabled", true);
									$("#confirmation_local_edit").prop("disabled", true);
									$("#confirmation_foreign_edit").prop("disabled", true);

				                	if(response[0]["transaction_strike_off"][0]['address_type'] == "Registered Office Address")
				                    {
				                    	$('.confirmation_registered_postal_code1').html(response[0]["transaction_strike_off"][0]["registered_postal_code1"]);
					                    $('.confirmation_registered_street_name1').html(response[0]["transaction_strike_off"][0]["registered_street_name1"]);
					                    $('.confirmation_registered_building_name1').html(response[0]["transaction_strike_off"][0]["registered_building_name1"]);
					                    $('.confirmation_registered_unit_no1').html(response[0]["transaction_strike_off"][0]["registered_unit_no1"]);
					                    $('.confirmation_registered_unit_no2').html(response[0]["transaction_strike_off"][0]["registered_unit_no2"]);

				                        $("#confirmation_register_address_edit").prop("checked", true);
				                        $("#confirmation_tr_registered_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_strike_off"][0]['address_type'] == "Local")
				                    {
				                        $('.confirmation_postal_code1').html(response[0]["transaction_strike_off"][0]['postal_code1']);
				                        $('.confirmation_street_name1').html(response[0]["transaction_strike_off"][0]['street_name1']);
				                        $('.confirmation_building_name1').html(response[0]["transaction_strike_off"][0]['building_name1']);
				                        $('.confirmation_unit_no1').html(response[0]["transaction_strike_off"][0]['unit_no1']);
				                        $('.confirmation_unit_no2').html(response[0]["transaction_strike_off"][0]['unit_no2']);

				                        $("#confirmation_local_edit").prop("checked", true);
				                        $("#confirmation_tr_local_edit").show();
				                        $("#confirmation_tr_registered_edit").hide();
				                        $("#confirmation_tr_foreign_edit").hide();
				                    }
				                    else if(response[0]["transaction_strike_off"][0]['address_type'] == "Foreign")
				                    {
				                        $('.confirmation_foreign_address1').html(response[0]["transaction_strike_off"][0]['foreign_address1']);
				                        $('.confirmation_foreign_address2').html(response[0]["transaction_strike_off"][0]['foreign_address2']);
				                        $('.confirmation_foreign_address3').html(response[0]["transaction_strike_off"][0]['foreign_address3']);
				                        
				                        $("#confirmation_foreign_edit").prop("checked", true);
				                        $("#confirmation_tr_foreign_edit").show();
				                        $("#confirmation_tr_local_edit").hide();
				                        $("#confirmation_tr_registered_edit").hide();
				                    }

				                	$(".confirmation_reason_for_appication").html(transaction_strike_off[0]["reason_for_application_content"]);
				                	
				                	if(transaction_strike_off[0]["reason_for_application_id"] == 0 || transaction_strike_off[0]["reason_for_application_id"] == 1)
				                	{
				                		$(".confirmation_ceased_date_row").hide();
				                	}
				                	else
				                	{
				                		$(".confirmation_ceased_date_row").show();
				                		$(".confirmation_ceased_date").text(transaction_strike_off[0]["ceased_date"]);
				                	}
				                }
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 28)
	            	{
		            	$.ajax({
			                type: "POST",
			                url: "transaction/get_all_take_secretarial_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                    //console.log(response);
			                    $("#transaction_incorporation").remove();
			                    $("#transaction_confirm").append(response['interface']);
			                    if(response[0]["transaction_client"] != false)
			                    {
			                    	$("#transaction_incorporation").show();

					            	$("#transaction_client_code").html(response[0]["transaction_client"][0]["client_code"]);
					            	$("#transaction_company_name").html(response[0]["transaction_client"][0]["company_name"]);
					            	$("#transaction_company_type").html(response[0]["transaction_client"][0]["company_type_name"]);
					            	$("#transaction_activity1").html(response[0]["transaction_client"][0]["activity1"]);
					            	if(response[0]["transaction_client"][0]["activity2"] == "")
					            	{
					            		$(".activity2").css("display", "none");
					            	}
					            	else
					            	{
					            		$(".activity2").show();
					            		$("#transaction_activity2").html(response[0]["transaction_client"][0]["activity2"]);
					            	}
					            	/*$("#register_activity2").html(response[0]["profile"][0]["activity2"]);*/
					            	$("#transaction_postal_code").html(response[0]["transaction_client"][0]["postal_code"]);
					            	$("#transaction_street_name").html(response[0]["transaction_client"][0]["street_name"]);
					            	$("#transaction_building_name").html(response[0]["transaction_client"][0]["building_name"]);
					            	$("#transaction_unit_no1").html(response[0]["transaction_client"][0]["unit_no1"]);
					            	$("#transaction_unit_no2").html(response[0]["transaction_client"][0]["unit_no2"]);

					            	$("#registration_no").val(response[0]['transaction_client'][0]['registration_no']);
					            	$(".lodgement_date").val(response[0]['transaction_client'][0]['effective_date']);

					            	if(response[0]["transaction_client_officers"] != false)
					            	{
					            		$(".confirmation_officer").remove();

					            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
					            		{
						            		$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       
									        $a += '</tr>';
									            

									        $("#confirmation_officer_table").prepend($a);
									    }
					            	}
					            	
					            	if(response[0]["transaction_filing"] != false)
					            	{
					            		$("#transaction_filing_year_end").html(response[0]["transaction_filing"][0]["year_end"]);
					            		$("#transaction_filing_cycle").html(response[0]["transaction_filing"][0]["period"]);
					            		
					            	}

					            	if(response[0]["transaction_billing"] != false)
					            	{
					            		$(".confirmation_billing").remove();

					            		for(var z = 0; z < response[0]["transaction_billing"].length; z++)
					            		{
						            		$a = ""; 
									        $a += '<tr class="confirmation_billing">';
									        $a += '<td><div class="input-group mb-md">'+response[0]["transaction_billing"][z]["service_name"]+'</div></td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["invoice_description"]+'</div></td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["currency_name"]+'</div></td>';
									        $a += '<td>'+ addCommas(response[0]["transaction_billing"][z]["amount"])+'</td>';
									        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["unit_pricing_name"]+'</div></td>';
									        $a += "</tr>";

									        $("#confirmation_billing_table").append($a); 

									    }
					            		
					            	}

					            	if(response[0]["transaction_previous_secretarial"] != false)
					            	{
					            		$("#transaction_previous_secretarial_company_name").html(response[0]["transaction_previous_secretarial"][0]["company_name"]);
					            		$("#transaction_previous_secretarial_postal_code").html(response[0]["transaction_previous_secretarial"][0]["postal_code"]);
						            	$("#transaction_previous_secretarial_street_name").html(response[0]["transaction_previous_secretarial"][0]["street_name"]);
						            	$("#transaction_previous_secretarial_building_name").html(response[0]["transaction_previous_secretarial"][0]["building_name"]);
						            	$("#transaction_previous_secretarial_unit_no1").html(response[0]["transaction_previous_secretarial"][0]["unit_no1"]);
						            	$("#transaction_previous_secretarial_unit_no2").html(response[0]["transaction_previous_secretarial"][0]["unit_no2"]);
					            	}
					            	
			                    }
			                    if(response[0]["transaction_pending_documents"] != false)
			                    {
			                    	$(".row_doc_pending").remove();
			                    	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									{
				                    	$a="";
								        $a += '<tr class="row_doc_pending">';
								        $a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								        // $a += '<td>'+response[0]["transaction_pending_documents"][h]["company_name"]+'</td>';
								        $a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								        //$a += '<td style="text-align: center"><?=date("d F Y",strtotime('+response[0]["transaction_pending_documents"][h]["created_at"]+'))?></td>';
								        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
										{
								        	$a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								        }
								        else
								        {
								        	$a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								        }
								        $a += '</tr>';
								            

								        $("#pending_doc_body").append($a);
								    }
			                    }
			                    //documentInterface(response[0]["document"]);
			                }
			            });
					}
					else if($('.transaction_task option:selected').val() == 29)
	            	{
	            		$.ajax({
			                type: "POST",
			                url: "transaction/get_service_proposal_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_service_proposal").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	var confirm_transaction_service_proposal = response[0]["transaction_service_proposal"];

			                	$("#confirmation_proposal_date").html(confirm_transaction_service_proposal[0]['proposal_date']);
			                	$("#confirmation_activity1").html(confirm_transaction_service_proposal[0]['activity1']);
					            $("#confirmation_activity2").html(confirm_transaction_service_proposal[0]['activity2']);
					            $("#confirmation_postal_code").html(confirm_transaction_service_proposal[0]['postal_code']);
					            $("#confirmation_street_name").html(confirm_transaction_service_proposal[0]['street_name']);
					            $("#confirmation_building_name").html(confirm_transaction_service_proposal[0]['building_name']);
					            $("#confirmation_unit_no1").html(confirm_transaction_service_proposal[0]['unit_no1']);
					            $("#confirmation_unit_no2").html(confirm_transaction_service_proposal[0]['unit_no2']);

			                	if(response[0]["transaction_service_proposal_contact_person"] != false)
				            	{
				            		$("#confirm_contact_name").html(response[0]["transaction_service_proposal_contact_person"][0]["name"]);

				            		var client_contact_info_email = response[0]["transaction_service_proposal_contact_person"][0]["transaction_client_contact_info_email"];
				            		var client_contact_info_phone = response[0]["transaction_service_proposal_contact_person"][0]["transaction_client_contact_info_phone"];
				            		
				            		$(".confirm_phone").remove();
				            		$(".confirm_email").remove();

				            		if(client_contact_info_phone != null)
				            		{
					            		for (var h = 0; h < client_contact_info_phone.length; h++) 
									    {
									        var clientContactInfoPhoneArray = client_contact_info_phone[h].split(',');

									        if(clientContactInfoPhoneArray[2] == 1)
									        {
									            $(".confirm_fieldGroup_contact_phone").find('.confirm_contact_phone').html(clientContactInfoPhoneArray[1]);
									          
									            $(".confirm_fieldGroup_contact_phone").find('.confirm_main_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);
									        }
									        else
									        {
									        	$(".confirm_fieldGroupCopy_contact_phone").find('.confirm_second_hp').html(clientContactInfoPhoneArray[1]);
									            $(".confirm_fieldGroupCopy_contact_phone").find('.confirm_contact_phone_primary').attr("value", clientContactInfoPhoneArray[1]);


									            var fieldHTML = '<div class="input-group confirm_fieldGroup_contact_phone confirm_phone" style="margin-top:10px;">'+$(".confirm_fieldGroupCopy_contact_phone").html()+'</div>';

									            $( fieldHTML).prependTo(".confirm_contact_phone_toggle");
									        }
									    }
									}
									if(client_contact_info_email != null)
				            		{
					            		for (var h = 0; h < client_contact_info_email.length; h++) 
									    {
									        var clientContactInfoEmailArray = client_contact_info_email[h].split(',');

									        if(clientContactInfoEmailArray[2] == 1)
									        {
									            $(".confirm_fieldGroup_contact_email").find('.confirm_contact_email').html(clientContactInfoEmailArray[1]);
									            $(".confirm_fieldGroup_contact_email").find('.confirm_main_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

									            $(".confirm_fieldGroup_contact_email").find(".confirm_button_increment_contact_email").css({"visibility": "visible"});
									        }
									        else
									        {
									            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_second_contact_email').html(clientContactInfoEmailArray[1]);

									            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_contact_email_primary').attr("value", clientContactInfoEmailArray[1]);

									            var fieldHTML = '<div class="input-group confirm_fieldGroup_contact_email confirm_email" style="margin-top:10px; display: block !important;">'+$(".confirm_fieldGroupCopy_contact_email").html()+'</div>';

									            //$('body').find('.fieldGroup_contact_email:first').after(fieldHTML);
									            $( fieldHTML).prependTo(".confirm_contact_email_toggle");

									            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_second_contact_email').html("");
									            $(".confirm_fieldGroupCopy_contact_email").find('.confirm_contact_email_primary').attr("value", "");
									        }
									    }
									}
				            	}

				            	if(response[0]["transaction_our_service_list"])
						        {
						            for($i = 0; $i < response[0]["transaction_our_service_list"].length; $i++)
						            {
						                $b =""; 
						                $b += '<tr class="confirm_service_section">';
						                $b += '<td style="text-align:center;"><input type="checkbox" class="confirm_selected_service_id" id="confirm_selected_service_id'+$i+'" name="confirm_selected_service_id[]" value="'+response[0]["transaction_our_service_list"][$i]["id"]+'" disabled></td>';
						                $b += '<td>'+response[0]["transaction_our_service_list"][$i]["service_name"]+'</td>';
						                $b += '<td><span class="confirm_currency" style="text-align:right;width: 100%;" name="confirm_currency[]" id="confirm_currency'+$i+'"></span></td>';
						                $b += '<td style="text-align:right;"><span class="confirm_fee" name="confirm_fee[]" value="" id="confirm_fee'+$i+'"></td>';
						                $b += '<td><span class="confirm_unit_pricing" name="confirm_unit_pricing[]" id="confirm_unit_pricing'+$i+'" value=""></td>';
						                $b += '<td><span class="confirm_servicing_name" name="confirm_servicing_name[]" id="confirm_servicing_name'+$i+'" value=""></td>';
						                $b += '<td><span class="confirm_sequence" name="confirm_sequence[]" id="confirm_sequence'+$i+'" value=""></td>';
						                $b += '</tr>';

						                $("#body_confirm_service_proposal").append($b);

						                if(response[0]["transaction_service_proposal_service_info"])
						                {
						                    for($b = 0; $b < response[0]["transaction_service_proposal_service_info"].length; $b++)
						                    {
						                        if(response[0]["transaction_service_proposal_service_info"][$b]["our_service_id"] == array_for_service_proposal[0]["transaction_our_service_list"][$i]["id"])
						                        {
						                            $("#confirm_selected_service_id"+$i).prop('checked', true);
						                            $("#confirm_currency"+$i).html(response[0]["transaction_service_proposal_service_info"][$b]["currency_name"]);
						                            $("#confirm_fee"+$i).html(addCommas(response[0]["transaction_service_proposal_service_info"][$b]["fee"]));
						                            $("#confirm_unit_pricing"+$i).html(response[0]["transaction_service_proposal_service_info"][$b]["unit_pricing_name"]);
						                            if(response[0]["transaction_service_proposal_service_info"][$b]["branch_name"] != "")
						                            {
						                            	var branch_name = ' ('+response[0]["transaction_service_proposal_service_info"][$b]["branch_name"]+')';
						                            }
						                            else
						                            {
						                            	var branch_name = "";
						                            }
						                        	$("#confirm_servicing_name"+$i).html(response[0]["transaction_service_proposal_service_info"][$b]["firm_name"] + branch_name);
						                        	$("#confirm_sequence"+$i).html(response[0]["transaction_service_proposal_service_info"][$b]["sequence"]);
						                        	
						                        	for(var m = 0; m < response[0]["transaction_service_proposal_sub_service_info"].length; m++)
	                            					{	
	                            						if(response[0]["transaction_service_proposal_service_info"][$b]["id"] == response[0]["transaction_service_proposal_sub_service_info"][m]["service_info_id"])
	                                					{	
	                                						var checkbox_value = response[0]["transaction_service_proposal_service_info"][$b]["our_service_id"];

						                                    var j =""; 
						                                    j += '<tr class="confirm_service_section confirm_sub_row_'+checkbox_value+'">';
						                                    j += '<td style="text-align:center;"></td>';
						                                    j += '<td>'+response[0]["transaction_service_proposal_sub_service_info"][m]["our_service_name"]+'</td>';
						                                    j += '<td>'+response[0]["transaction_service_proposal_sub_service_info"][m]["currency_name"]+'</td>';
						                                    j += '<td style="text-align:right;">'+addCommas(response[0]["transaction_service_proposal_sub_service_info"][m]["sub_fee"])+'</td>';
						                                    j += '<td>'+response[0]["transaction_service_proposal_sub_service_info"][m]["unit_pricing_name"]+'</td>';
						                                    j += '<td></td>';
						                                    j += '<td></td>';
						                                    j += '</tr>';
						                                   	//console.log("in");
						                                    $("#confirm_selected_service_id"+$i).parent().parent().after(j);
	                                					}
	                            					}
						                    	}

						                    	
						                    }
						                }
						            }
						        }

						        if(response[0]["transaction_pending_documents"] != false)
			                    {
			                    	$(".row_doc_pending").remove();
			                    	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									{
				                    	var a="";
								        a += '<tr class="row_doc_pending">';
								        a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								        // $a += '<td>'+response[0]["transaction_pending_documents"][h]["company_name"]+'</td>';
								        a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								        //$a += '<td style="text-align: center"><?=date("d F Y",strtotime('+response[0]["transaction_pending_documents"][h]["created_at"]+'))?></td>';
								        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
										{
								        	a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								        }
								        else
								        {
								        	a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								        }
								        a += '</tr>';
								            

								        $("#pending_doc_body").append(a);
								    }
			                    }

			           //          $(".lodgement_date").val(response[0]['effective_date']);

						        // $("#tran_status option").remove();
				          //       var select_option = $('<option />');
				          //       select_option.attr('value', '0').text("Select Status");
				          //       $("#tran_status").append(select_option);
				          //       for(var i = 0; i < response[0]['transaction_service_proposal_status'].length; i++) 
				          //       {
				          //           var option = $('<option />');
				          //           if(response[0]['transaction_service_proposal_status'][i]["id"] == 2)
				          //           {
				          //           	option.attr('style',"display:none");
				          //           }
				                    
				          //           option.attr('value', response[0]['transaction_service_proposal_status'][i]["id"]).text(response[0]['transaction_service_proposal_status'][i]["service_proposal_status"]);
				          //           if(response[0]["service_status"] != 0 && response[0]['transaction_service_proposal_status'][i]["id"] == response[0]["service_status"])
				          //           {
				          //           	if(response[0]['transaction_service_proposal_status'][i]["id"] == 2)
					         //            {
					         //            	option.removeAttr('style');
					         //            }
				          //               option.attr('selected', 'selected');
				          //           }				                    
				          //           else
				          //           {
				          //           	if(response[0]['transaction_service_proposal_status'][i]["id"] == 1)
				          //           	{
				          //           		option.attr('selected', 'selected');
				          //           	}
				          //               loadFirstTab();
				          //           }
				          //           $("#tran_status").append(option);
				          //       };
			                }
			            });
	            	}
	            	else if($('.transaction_task option:selected').val() == 30)
	            	{
	            		$.ajax({
			                type: "POST",
			                url: "transaction/get_engagement_letter_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_engagement_letter").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	if(response[0]["transaction_engagement_letter_additional_info"] != false)
			                	{
				                	$('#confirmation_engagement_letter_date').html(response[0]["transaction_engagement_letter_additional_info"][0]["engagement_letter_date"]);
				                	$('#confirmation_el_uen').html(response[0]["transaction_engagement_letter_additional_info"][0]["uen"]);
				                	$('#confirmation_fye_date').html(response[0]["transaction_engagement_letter_additional_info"][0]["fye_date"]);
				                	$('#confirmation_director_signing').html(response[0]["transaction_engagement_letter_additional_info"][0]["director_signing"]);
				                }

			                	if(response[0]["transaction_engagement_letter_list"])
						        {
						            for($i = 0; $i < response[0]["transaction_engagement_letter_list"].length; $i++)
						            {
						                $b =""; 
						                $b += '<tr class="confirm_service_section">';
						                $b += '<td style="text-align:center;"><input type="checkbox" class="confirm_selected_el_id" id="confirm_selected_el_id'+$i+'" name="confirm_selected_el_id[]" value="'+response[0]["transaction_engagement_letter_list"][$i]["id"]+'" disabled></td>';
						                $b += '<td>'+response[0]["transaction_engagement_letter_list"][$i]["engagement_letter_list_name"]+'</td>';
						                $b += '<td><span class="confirm_currency" style="text-align:right;width: 100%;" name="confirm_currency[]" id="confirm_currency'+$i+'"></span></td>';
						                $b += '<td><span class="confirm_fee" name="confirm_fee[]" value="" id="confirm_fee'+$i+'" style="text-align:right;"></td>';
						                $b += '<td><span class="confirm_unit_pricing" name="confirm_unit_pricing[]" id="confirm_unit_pricing'+$i+'" value=""></td>';
						                $b += '<td><span class="confirm_servicing_firm" name="confirm_servicing_firm[]" id="confirm_servicing_firm'+$i+'" value=""></td>';
						                $b += '</tr>';

						                $("#body_confirm_engagement_letter").append($b);

						                if(response[0]["transaction_engagement_letter_service_info"])
						                {
						                    for($b = 0; $b < response[0]["transaction_engagement_letter_service_info"].length; $b++)
						                    {
						                        if(response[0]["transaction_engagement_letter_service_info"][$b]["engagement_letter_list_id"] == array_for_engagement_letter[0]["transaction_engagement_letter_list"][$i]["id"])
						                        {
						                            $("#confirm_selected_el_id"+$i).prop('checked', true);
						                            $("#confirm_currency"+$i).html(response[0]["transaction_engagement_letter_service_info"][$b]["currency_name"]);
						                            $("#confirm_fee"+$i).html(addCommas(response[0]["transaction_engagement_letter_service_info"][$b]["fee"]));
						                            $("#confirm_unit_pricing"+$i).html(response[0]["transaction_engagement_letter_service_info"][$b]["unit_pricing_name"]);
						                            if(response[0]["transaction_engagement_letter_service_info"][$b]["branch_name"] != "")
						                            {
						                            	$("#confirm_servicing_firm"+$i).html(response[0]["transaction_engagement_letter_service_info"][$b]["firm_name"]+' ('+response[0]["transaction_engagement_letter_service_info"][$b]["branch_name"]+')');
						                            }
						                            else
						                            {
						                            	$("#confirm_servicing_firm"+$i).html(response[0]["transaction_engagement_letter_service_info"][$b]["firm_name"]);
						                            }
						                        }
						                    }
						                }
						            }
						        }

			                	if(response[0]["transaction_pending_documents"] != false)
			                    {
			                    	$(".row_doc_pending").remove();
			                    	for (var h = 0; h < response[0]["transaction_pending_documents"].length; h++) 
									{
				                    	var a="";
								        a += '<tr class="row_doc_pending">';
								        a += '<td style="text-align: right"><label>'+(h+1)+'</label></td>';
								        // $a += '<td>'+response[0]["transaction_pending_documents"][h]["company_name"]+'</td>';
								        a += '<td>'+response[0]["transaction_pending_documents"][h]["document_name"]+'</td>';
								        //$a += '<td style="text-align: center"><?=date("d F Y",strtotime('+response[0]["transaction_pending_documents"][h]["created_at"]+'))?></td>';
								        if(response[0]["transaction_pending_documents"][h]["received_on"] == "")
										{
								        	a += '<td><a id="add_pending_document_file" href="documents/add_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="btn btn-primary update_button">Update</a></td>';
								        }
								        else
								        {
								        	a += '<td><a href="documents/edit_pending_document_file/'+response[0]["transaction_pending_documents"][h]["id"]+'/'+response[0]["transaction_pending_documents"][h]["type"]+'" target="_blank" class="pointer mb-sm mt-sm mr-sm">'+response[0]["transaction_pending_documents"][h]["received_on"]+'</td>';
								        }
								        a += '</tr>';
								            

								        $("#pending_doc_body").append(a);
								    }
			                    }

			           //      	$(".lodgement_date").val(response[0]['effective_date']);

						        // $("#tran_status option").remove();
				          //       var select_option = $('<option />');
				          //       select_option.attr('value', '0').text("Select Status");
				          //       $("#tran_status").append(select_option);
				          //       for(var i = 0; i < response[0]['transaction_engagement_letter_status'].length; i++) {
				          //           var option = $('<option />');
				          //           option.attr('value', response[0]['transaction_engagement_letter_status'][i]["id"]).text(response[0]['transaction_engagement_letter_status'][i]["engagement_letter_status"]);
				          //           if(response[0]["service_status"] != 0 && response[0]['transaction_engagement_letter_status'][i]["id"] == response[0]["service_status"])
				          //           {
				          //               option.attr('selected', 'selected');
				          //           }				                    
				          //           else
				          //           {
				          //               loadFirstTab();
				          //           }
				          //           $("#tran_status").append(option);
				          //       };
			                }
			            });
	            	}
	            	else if($('.transaction_task option:selected').val() == 31)
	            	{
	            		$.ajax({
			                type: "POST",
			                url: "transaction/get_conf_register_controller_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_controller").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_controller #confirmation_registration_no").text($("#w2-controller #registration_no").text());
			                	$("#transaction_confirmation_controller #confirmation_company_name").text($("#w2-controller #company_name").text());

			                	var conf_get_current_client_controller_data = response[0]["conf_get_current_client_controller_data"];
            					var conf_get_latest_client_controller_data = response[0]["conf_get_latest_client_controller_data"];

            					if(conf_get_current_client_controller_data.length > 0)
            					{
            						for(var i = 0; i < conf_get_current_client_controller_data.length; i++)
	        						{
	            						var selected_controller = conf_get_current_client_controller_data[i];
						                var full_address;

						                if(selected_controller["supporting_document"] != "")
						                {
						                    var file_result = JSON.parse(selected_controller["supporting_document"]);
						                }

						                if(selected_controller["client_controller_field_type"] == "individual")
						                {
						                    // if(selected_controller["alternate_address"] == 1)
						                    // {
						                    //     full_address = address_format (selected_controller["postal_code2"], selected_controller["street_name2"], selected_controller["building_name2"], selected_controller["unit_no3"], selected_controller["unit_no4"]);
						                    // }
						                    // else
						                    // {
						                        full_address = address_format (selected_controller["postal_code1"], selected_controller["street_name1"], selected_controller["building_name1"], selected_controller["officer_unit_no1"], selected_controller["officer_unit_no2"], selected_controller["foreign_address1"], selected_controller["foreign_address2"], selected_controller["foreign_address3"]);
						                    //}
						                }
						                else if(selected_controller["client_controller_field_type"] == "company")
						                {
						                    full_address = address_format (selected_controller["company_postal_code"], selected_controller["company_street_name"], selected_controller["company_building_name"], selected_controller["company_unit_no1"], selected_controller["company_unit_no2"], selected_controller["company_foreign_address1"], selected_controller["company_foreign_address2"], selected_controller["company_foreign_address3"]);
						                }
						                else if(selected_controller["client_controller_field_type"] == "client")
						                {
						                    full_address = address_format (selected_controller["postal_code"], selected_controller["street_name"], selected_controller["building_name"], selected_controller["client_unit_no1"], selected_controller["client_unit_no2"], selected_controller["foreign_add_1"], selected_controller["foreign_add_2"], selected_controller["foreign_add_3"]);
						                }

						                if(selected_controller["date_of_notice"] != "")
						                {
						                    var date_of_notice = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_notice"])));
						                }
						                else
						                {
						                    var date_of_notice = "";
						                }

						                if(selected_controller["confirmation_received_date"] != "")
						                {
						                    var confirmation_received_date = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["confirmation_received_date"])));
						                }
						                else
						                {
						                    var confirmation_received_date = "";
						                }

						                if(selected_controller["date_of_entry"] != "")
						                {
						                    var date_of_entry = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_entry"])));
						                }
						                else
						                {
						                    var date_of_entry = "";
						                }

						                $b = '';
						                $b += '<tr class="tr_controller_table">';
						                $b += '<td style="text-align: center">'+date_of_notice+'</td>';
						                $b += '<td style="text-align: center">'+confirmation_received_date+'</td>'; 
						                $b += '<td style="text-align: center">'+date_of_entry+'</td>';
						                if(selected_controller["client_controller_field_type"] == "individual")
						                {
						                	var date_of_birth = formatDateRONDCFunc(new Date(selected_controller["date_of_birth"]));
						                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
						                    if(selected_controller["date_of_cessation"] != "")
						                    {
						                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
						                    }
						                    else
						                    {
						                        var date_of_cessation = "";
						                    }
						                    $b += '<td><b>Full name:</b> '+ selected_controller["name"] +'<br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
						                }
						                else
						                {
						                	var date_of_incorporation = (selected_controller["date_of_incorporation"] != null ? selected_controller["date_of_incorporation"] : (selected_controller["incorporation_date"] != null)?changeRONDCDateFormat(selected_controller["incorporation_date"]) : "");
						                    if(date_of_incorporation != "")
						                    {
						                        var old_date_of_incorporation = new Date(date_of_incorporation);
						                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
						                    }
						                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
						                    if(selected_controller["date_of_cessation"] != "")
						                    {
						                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
						                    }
						                    else
						                    {
						                        var date_of_cessation = "";
						                    }

						                    $b += '<td><b>Name:</b> '+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:selected_controller["client_company_type"]!=null?selected_controller["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:selected_controller["client_country_of_incorporation"]!=null?selected_controller["client_country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : selected_controller["client_statutes_of"] != null ? ', ' + selected_controller["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> ' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:selected_controller["client_coporate_entity_name"]!=null?selected_controller["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
						                }
						                if(selected_controller["supporting_document"] != "" && selected_controller["supporting_document"] != "[]")
						                {
						                    $b += '<td>'+file_result[0]+'</td>';
						                }
						                else
						                {
						                    $b += '<td></td>';
						                }
						                //$b += '<td><input type="hidden" id="client_controller_id" value="'+selected_controller["client_controller_id"]+'" name="client_controller_id"/><input type="hidden" id="client_controller_officer_id" value="'+selected_controller["officer_id"]+'" name="client_controller_officer_id"/><input type="hidden" id="client_controller_field_type" value="'+selected_controller["client_controller_field_type"]+'" name="client_controller_field_type"/><input type="hidden" id="client_controller_name" value="'+(selected_controller["name"]!=null ? selected_controller["name"] : selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"])+'" name="client_controller_name"/><input type="hidden" id="company_code" value="'+selected_controller["client_controller_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_register_controller(this);">Delete</button></td>';
						                $b += '</tr>';

						                $("#confirmation_table_body_current_controller").append($b);
						            }
            					}
            					else
            					{
            						var $z=""; 
				                    $z += '<tr class="tr_controller_table">';
				                    $z += '<td colspan="5" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				                    $z += '</tr>';

				                    $("#confirmation_table_body_current_controller").append($z);
            					}

            					if(conf_get_latest_client_controller_data.length > 0)
            					{
            						for(var i = 0; i < conf_get_latest_client_controller_data.length; i++)
							        {
							            var selected_controller = conf_get_latest_client_controller_data[i];

							            if(selected_controller)
							            {
							                var full_address;

							                if(selected_controller["supporting_document"] != "")
							                {
							                    var file_result = JSON.parse(selected_controller["supporting_document"]);
							                }

							                if(selected_controller["client_controller_field_type"] == "individual")
							                {
							                    // if(selected_controller["alternate_address"] == 1)
							                    // {
							                    //     full_address = address_format (selected_controller["postal_code2"], selected_controller["street_name2"], selected_controller["building_name2"], selected_controller["unit_no3"], selected_controller["unit_no4"]);
							                    // }
							                    // else
							                    // {
							                        full_address = address_format (selected_controller["postal_code1"], selected_controller["street_name1"], selected_controller["building_name1"], selected_controller["officer_unit_no1"], selected_controller["officer_unit_no2"], selected_controller["foreign_address1"], selected_controller["foreign_address2"], selected_controller["foreign_address3"]);
							                    //}
							                }
							                else if(selected_controller["client_controller_field_type"] == "company")
							                {
							                    full_address = address_format (selected_controller["company_postal_code"], selected_controller["company_street_name"], selected_controller["company_building_name"], selected_controller["company_unit_no1"], selected_controller["company_unit_no2"], selected_controller["company_foreign_address1"], selected_controller["company_foreign_address2"], selected_controller["company_foreign_address3"]);
							                }
							                else if(selected_controller["client_controller_field_type"] == "client")
							                {
							                    full_address = address_format (selected_controller["postal_code"], selected_controller["street_name"], selected_controller["building_name"], selected_controller["client_unit_no1"], selected_controller["client_unit_no2"], selected_controller["foreign_add_1"], selected_controller["foreign_add_2"], selected_controller["foreign_add_3"]);
							                }

							                 if(selected_controller["date_of_notice"] != "")
							                {
							                    var date_of_notice = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_notice"])));
							                }
							                else
							                {
							                    var date_of_notice = "";
							                }

							                $b = '';
							                $b += '<tr class="tr_latest_controller_table">';
							                $b += '<td style="text-align: center">'+date_of_notice+'</td>';
							                // $b += '<td style="text-align: center">'+selected_controller["confirmation_received_date"]+'</td>'; 
							                // $b += '<td style="text-align: center">'+selected_controller["date_of_entry"]+'</td>';
							                if(selected_controller["client_controller_field_type"] == "individual")
							                {
							                	var date_of_birth = formatDateRONDCFunc(new Date(selected_controller["date_of_birth"]));
							                    var date_of_registration = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_registration"])));
							                    if(selected_controller["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_controller["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    $b += '<td><b>Full name:</b> '+ selected_controller["name"] +'<br/><b>Alias:</b> '+selected_controller["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_controller["officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_controller["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                else
							                {
							                    $b += '<td><b>Name:</b> '+ (selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b>'+ (selected_controller["entity_issued_by_registrar"]!=null?selected_controller["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_controller["legal_form_entity"]!=null?selected_controller["legal_form_entity"]:selected_controller["client_company_type"]!=null?selected_controller["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_controller["country_of_incorporation"]!=null?selected_controller["country_of_incorporation"]:selected_controller["client_country_of_incorporation"]!=null?selected_controller["client_country_of_incorporation"]:"") + (selected_controller["statutes_of"] != null ? ', ' + selected_controller["statutes_of"] : selected_controller["client_statutes_of"] != null ? ', ' + selected_controller["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> ' + (selected_controller["coporate_entity_name"]!=null?selected_controller["coporate_entity_name"]:selected_controller["client_coporate_entity_name"]!=null?selected_controller["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_controller["register_no"] != null ? selected_controller["register_no"] : selected_controller["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_of_registration+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                if(selected_controller["supporting_document"] != "" && selected_controller["supporting_document"] != "[]")
							                {
							                    $b += '<td>'+file_result[0]+'</td>';
							                }
							                else
							                {
							                    $b += '<td></td>';
							                }
							                //$b += '<td><input type="hidden" id="client_controller_id" value="'+selected_controller["client_controller_id"]+'" name="client_controller_id"/><input type="hidden" id="client_controller_officer_id" value="'+selected_controller["officer_id"]+'" name="client_controller_officer_id"/><input type="hidden" id="client_controller_field_type" value="'+selected_controller["client_controller_field_type"]+'" name="client_controller_field_type"/><input type="hidden" id="client_controller_name" value="'+(selected_controller["name"]!=null ? selected_controller["name"] : selected_controller["officer_company_company_name"]!=null ? selected_controller["officer_company_company_name"] : selected_controller["client_company_name"])+'" name="client_controller_name"/><input type="hidden" id="company_code" value="'+selected_controller["client_controller_company_code"]+'" name="company_code"/><button type="button" class="btn btn-primary" onclick="delete_register_controller(this);">Delete</button></td>';
							                $b += '</tr>';

							                $("#confirmation_table_body_latest_controller").append($b);
							            }
							        }
            					}
            					else
            					{
            						var $z=""; 
				                    $z += '<tr class="tr_controller_table">';
				                    $z += '<td colspan="4" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				                    $z += '</tr>';

				                    $("#confirmation_table_body_latest_controller").append($z);
            					}
			                	
					        }
					    });
	            	}
	            	else if($('.transaction_task option:selected').val() == 32)
	            	{
	            		$.ajax({
			                type: "POST",
			                url: "transaction/get_conf_register_nominee_director_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_nominee_director").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_nominee_director #confirmation_registration_no").text($("#w2-nominee_director #registration_no").text());
			                	$("#transaction_confirmation_nominee_director #confirmation_company_name").text($("#w2-nominee_director #company_name").text());
			                
			                	var conf_get_current_client_nominee_director_data = response[0]["conf_get_current_client_nominee_director_data"];
            					var conf_get_latest_client_nominee_director_data = response[0]["conf_get_latest_client_nominee_director_data"];

            					if(conf_get_current_client_nominee_director_data.length > 0)
            					{
	            					for(var i = 0; i < conf_get_current_client_nominee_director_data.length; i++)
							        {
							            var selected_nominee_director = conf_get_current_client_nominee_director_data[i];

							            if(selected_nominee_director)
							            {
							                var full_address;

							                if(selected_nominee_director["supporting_document"] != "")
							                {
							                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
							                }

							                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
							                {
							                    if(selected_nominee_director["alternate_address"] == 1)
							                    {
							                        full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
							                    }
							                    else
							                    {
							                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
							                    }
							                }
							                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
							                {
							                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
							                }
							                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
							                {
							                   full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
							                }

							                if(selected_nominee_director["nd_date_entry"] != "")
							                {
							                    var nd_date_entry = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["nd_date_entry"])));
							                }
							                else
							                {
							                    var nd_date_entry = "";
							                }

							                $b = '';
							                $b += '<tr class="tr_nominee_director_table">';
							                $b += '<td style="text-align: center">'+nd_date_entry+'</td>';
							                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
							                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
							                {
							                	var date_of_birth = formatDateRONDCFunc(new Date(selected_nominee_director["date_of_birth"]));
							                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
							                    if(selected_nominee_director["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    $b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+date_of_birth+'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                else
							                {
							                	var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDCDateFormat(selected_nominee_director["incorporation_date"]) : "");
							                    if(date_of_incorporation != "")
							                    {
							                        var old_date_of_incorporation = new Date(date_of_incorporation);
							                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
							                    }
							                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
							                    if(selected_nominee_director["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    $b += '<td><b>Name:</b> '+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b> '+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                //$b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+selected_nominee_director["date_of_birth"] +'<br/><b>Date on which the person becomes a nominator:</b> '+selected_nominee_director["date_become_nominator"]+'<br/><b>Date of cessation:</b>'+selected_nominee_director["date_of_cessation"]+'</td>';
							                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
							                {
							                    $b += '<td>'+file_result[0]+'</td>';
							                }
							                else
							                {
							                    $b += '<td></td>';
							                }
							                $b += '</tr>';

							                $("#confirmation_table_body_current_nominee_director").append($b);
							            }
							        }
							    }
							    else
							    {
            						var $z=""; 
				                    $z += '<tr class="tr_nominee_director_table">';
				                    $z += '<td colspan="4" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				                    $z += '</tr>';

				                    $("#confirmation_table_body_current_nominee_director").append($z);
							    }

							    if(conf_get_latest_client_nominee_director_data.length > 0)
            					{
            						for(var i = 0; i < conf_get_latest_client_nominee_director_data.length; i++)
							        {
							            var selected_nominee_director = conf_get_latest_client_nominee_director_data[i];

							            if(selected_nominee_director)
							            {
							                var full_address;

							                if(selected_nominee_director["supporting_document"] != "")
							                {
							                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
							                }

							                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
							                {
							                    if(selected_nominee_director["alternate_address"] == 1)
							                    {
							                        full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
							                    }
							                    else
							                    {
							                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
							                    }
							                }
							                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
							                {
							                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
							                }
							                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
							                {
							                   	full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
							                }

							                $b = '';
							                $b += '<tr class="tr_nominee_director_table">';
							                //$b += '<td style="text-align: center">'+selected_nominee_director["nd_date_entry"]+'</td>';
							                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
							                //$b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+selected_nominee_director["date_of_birth"] +'<br/><b>Date on which the person becomes a nominator:</b> '+selected_nominee_director["date_become_nominator"]+'<br/><b>Date of cessation:</b>'+selected_nominee_director["date_of_cessation"]+'</td>';
							                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
							                {
							                	var date_of_birth = formatDateRONDCFunc(new Date(selected_nominee_director["date_of_birth"]));
							                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
							                    if(selected_nominee_director["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    $b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+ date_of_birth +'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                else
							                {
							                	var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDCDateFormat(selected_nominee_director["incorporation_date"]) : "");
							                    if(date_of_incorporation != "")
							                    {
							                        var old_date_of_incorporation = new Date(date_of_incorporation);
							                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
							                    }
							                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
							                    if(selected_nominee_director["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    $b += '<td><b>Name:</b> '+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b> '+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
							                {
							                    $b += '<td>'+file_result[0]+'</td>';
							                }
							                else
							                {
							                    $b += '<td></td>';
							                }
							                $b += '</tr>';

							                $("#confirmation_table_body_latest_nominee_director").append($b);
							            }
							        }
            					}
            					else
							    {
            						var $z=""; 
				                    $z += '<tr class="tr_nominee_director_table">';
				                    $z += '<td colspan="4" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				                    $z += '</tr>';

				                    $("#confirmation_table_body_latest_nominee_director").append($z);
							    }
			                }
			            });
	            	}
	            	else if($('.transaction_task option:selected').val() == 33)
	            	{
	           			$.ajax({
			                type: "POST",
			                url: "transaction/get_all_resign_director_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){

			                	$("#transaction_confirmation_officer_table").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_officer_table .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_officer_table .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);
			                	var conf_get_latest_client_nominee_director_data = response[0]["get_latest_client_nominee_director_data"];

			                	if(response[0]["transaction_client_officers"] != false)
				            	{
				            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
				            		{
				            			if(response[0]["transaction_client_officers"][i]["date_of_appointment"] != "" && response[0]["transaction_client_officers"][i]["appoint_resign_flag"] ==  "resign")
				            			{
						            		$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_cessation"] +'</td>';
									       	$a += '<td>'+ ((response[0]["transaction_client_officers"][i]["reason"]!="" && response[0]["transaction_client_officers"][i]["reason"]!="NULL") ? response[0]["transaction_client_officers"][i]["reason"] : (response[0]["transaction_client_officers"][i]["reason_selected"] != null ? response[0]["transaction_client_officers"][i]["reason_selected"] : "")) +'</td>';
									        $a += '</tr>';
									            
									        $("#confirmation_officer_table").append($a);
									    }
									    else
									    {
									    	$("#confirmation_appoint_officer").show();
									    	var c = "";
									        c += '<tr class="confirmation_officer">';
									        c += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        c += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        c += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									        c += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									        c += '</tr>';
									            
									        $("#confirmation_appoint_officer_table").append(c);
									    }
								    }
				            	}

				            	if($('#body_appoint_new_director').find(".row_appoint_new_director").length == 0)
    							{
    								$("#confirmation_appoint_officer").hide();
    							}
    							else
    							{
    								$("#confirmation_appoint_officer").show();
    							}

				            	if($("#body_resign_director .withdraw_director").length >= 1 || $("#body_resign_director .cancel_withdraw_director").length >= 1)
    							{
    								$("#confirmation_resign_officer").show();
    							}
    							else
    							{
    								$("#confirmation_resign_officer").hide();
    							}

			                	if($('#add_nominee_director').is(':checked') && $("#table_body_latest_nominee_director .tr_nominee_director_table").length >= 1)
								{
									$('#confirmation_nominee_officer').show();
								}
								else
								{
									$('#confirmation_nominee_officer').hide();
								}

				            	if(conf_get_latest_client_nominee_director_data.length > 0)
            					{
            						for(var i = 0; i < conf_get_latest_client_nominee_director_data.length; i++)
							        {
							            var selected_nominee_director = conf_get_latest_client_nominee_director_data[i];

							            if(selected_nominee_director)
							            {
							                var full_address;

							                if(selected_nominee_director["supporting_document"] != "")
							                {
							                    var file_result = JSON.parse(selected_nominee_director["supporting_document"]);
							                }

							                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
							                {
							                    if(selected_nominee_director["alternate_address"] == 1)
							                    {
							                        full_address = address_format (selected_nominee_director["postal_code2"], selected_nominee_director["street_name2"], selected_nominee_director["building_name2"], selected_nominee_director["unit_no3"], selected_nominee_director["unit_no4"]);
							                    }
							                    else
							                    {
							                        full_address = address_format (selected_nominee_director["postal_code1"], selected_nominee_director["street_name1"], selected_nominee_director["building_name1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["nomi_officer_unit_no1"], selected_nominee_director["foreign_address1"], selected_nominee_director["foreign_address2"], selected_nominee_director["foreign_address3"]);
							                    }
							                }
							                else if(selected_nominee_director["nomi_officer_field_type"] == "company")
							                {
							                    full_address = address_format (selected_nominee_director["company_postal_code"], selected_nominee_director["company_street_name"], selected_nominee_director["company_building_name"], selected_nominee_director["company_unit_no1"], selected_nominee_director["company_unit_no2"], selected_nominee_director["company_foreign_address1"], selected_nominee_director["company_foreign_address2"], selected_nominee_director["company_foreign_address3"]);
							                }
							                else if(selected_nominee_director["nomi_officer_field_type"] == "client")
							                {
							                   	full_address = address_format (selected_nominee_director["postal_code"], selected_nominee_director["street_name"], selected_nominee_director["building_name"], selected_nominee_director["client_unit_no1"], selected_nominee_director["client_unit_no2"], selected_nominee_director["foreign_add_1"], selected_nominee_director["foreign_add_2"], selected_nominee_director["foreign_add_3"]);
							                }

							                $b = '';
							                $b += '<tr class="tr_nominee_director_table">';
							                //$b += '<td style="text-align: center">'+selected_nominee_director["nd_date_entry"]+'</td>';
							                $b += '<td>'+selected_nominee_director["nd_officer_name"]+'</td>';
							                //$b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+selected_nominee_director["date_of_birth"] +'<br/><b>Date on which the person becomes a nominator:</b> '+selected_nominee_director["date_become_nominator"]+'<br/><b>Date of cessation:</b>'+selected_nominee_director["date_of_cessation"]+'</td>';
							                if(selected_nominee_director["nomi_officer_field_type"] == "individual")
							                {
							                	var date_of_birth = formatDateRONDCFunc(new Date(selected_nominee_director["date_of_birth"]));
							                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
							                    if(selected_nominee_director["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    // $b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'<br/><b>Alias:</b> '+selected_nominee_director["alias"]+'<br/><b>Residential address:</b> '+full_address+'<br/><b>Nationality:</b> '+selected_nominee_director["nomi_officer_nationality_name"]+'<br/><b>Identification card number:</b> '+ selected_nominee_director["identification_no"] +'<br/><b>Date of birth:</b> '+ date_of_birth +'<br/><b>Date on which the person becomes a nominator:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                    $b += '<td><b>Full name:</b> '+ selected_nominee_director["name"] +'</td>';
							                }
							                else
							                {
							                	var date_of_incorporation = (selected_nominee_director["date_of_incorporation"] != null ? selected_nominee_director["date_of_incorporation"] : (selected_nominee_director["incorporation_date"] != null)?changeRONDCDateFormat(selected_nominee_director["incorporation_date"]) : "");
							                    if(date_of_incorporation != "")
							                    {
							                        var old_date_of_incorporation = new Date(date_of_incorporation);
							                        var date_of_incorporation = formatDateRONDCFunc(old_date_of_incorporation);
							                    }
							                    var date_become_nominator = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_become_nominator"])));
							                    if(selected_nominee_director["date_of_cessation"] != "")
							                    {
							                        var date_of_cessation = formatDateRONDCFunc(new Date(changeRONDCDateFormat(selected_nominee_director["date_of_cessation"])));
							                    }
							                    else
							                    {
							                        var date_of_cessation = "";
							                    }
							                    $b += '<td><b>Name:</b> '+ (selected_nominee_director["officer_company_company_name"]!=null ? selected_nominee_director["officer_company_company_name"] : selected_nominee_director["client_company_name"]) + '<br/><b>Unique entity number issued by the Registrar:</b> '+ (selected_nominee_director["entity_issued_by_registrar"]!=null?selected_nominee_director["entity_issued_by_registrar"]:"")+'<br/><b>Address of registered office:</b> '+full_address+ '<br/><b>Legal form:</b> '+ (selected_nominee_director["legal_form_entity"] != null?selected_nominee_director["legal_form_entity"]:selected_nominee_director["client_company_type"] != null?selected_nominee_director["client_company_type"]:"") +'<br/><b>Jurisdiction where and statute under which the registrable corporate controller is formed or incorporated:</b> '+(selected_nominee_director["country_of_incorporation"]!=null?selected_nominee_director["country_of_incorporation"]:selected_nominee_director["client_country_of_incorporation"]!=null?selected_nominee_director["client_country_of_incorporation"]:"") + (selected_nominee_director["statutes_of"] != null ? ', ' + selected_nominee_director["statutes_of"] : selected_nominee_director["client_statutes_of"] != null ? ', ' + selected_nominee_director["client_statutes_of"] : '') + '<br/><b>Name of the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b>' + (selected_nominee_director["coporate_entity_name"]!=null?selected_nominee_director["coporate_entity_name"]:selected_nominee_director["client_coporate_entity_name"]!=null?selected_nominee_director["client_coporate_entity_name"]:"") +'<br/><b>Identification number or registration number on the corporate entity register of the jurisdiction where the registrable corporate controller is formed or incorporated:</b> '+ (selected_nominee_director["register_no"] != null ? selected_nominee_director["register_no"] : selected_nominee_director["registration_no"]) +'<br/><b>Date of Incorporation:</b> '+ date_of_incorporation +'<br/><b>Date of becoming a controller:</b> '+date_become_nominator+'<br/><b>Date of cessation:</b>'+date_of_cessation+'</td>';
							                }
							                if(selected_nominee_director["supporting_document"] != "" && selected_nominee_director["supporting_document"] != "[]")
							                {
							                    $b += '<td>'+file_result[0]+'</td>';
							                }
							                else
							                {
							                    $b += '<td></td>';
							                }
							                $b += '</tr>';

							                $("#confirmation_table_body_latest_nominee_director").append($b);
							            }
							        }
            					}
            					else
							    {
            						var $z=""; 
				                    $z += '<tr class="tr_nominee_director_table">';
				                    $z += '<td colspan="4" style="text-align:center;"><span style="font-weight:bold; font-size:20px;">N/A</span></td>';
				                    $z += '</tr>';

				                    $("#confirmation_table_body_latest_nominee_director").append($z);
							    }

							    if(response[0]["transaction_billing"] != false)
				            	{
				            		$(".confirmation_billing").remove();

				            		for(var z = 0; z < response[0]["transaction_billing"].length; z++)
				            		{
					            		$a = ""; 
								        $a += '<tr class="confirmation_billing">';
								        $a += '<td><div class="input-group mb-md">'+response[0]["transaction_billing"][z]["service_name"]+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["invoice_description"]+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["currency_name"]+'</div><div>'+addCommas(response[0]["transaction_billing"][z]["amount"])+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["unit_pricing_name"]+'</div></td>';
								        if(response[0]["transaction_billing"][z]["branch_name"] != "")
								        {
								        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?(response[0]["transaction_billing"][z]["firm_name"] +' ('+response[0]["transaction_billing"][z]["branch_name"]+')'):'')+'</div></td>';
								        }
								        else
								        {
								        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?response[0]["transaction_billing"][z]["firm_name"]:'')+'</div></td>';
								        }
								        
								        $a += "</tr>";

								        $("#confirmation_billing_table").append($a); 

								    }
				            		
				            	}
			                }
			            });
	            	}
	            	else if($('.transaction_task option:selected').val() == 34)
            		{
            			$.ajax({
			                type: "POST",
			                url: "transaction/get_all_appoint_new_secretarial_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_officer_table").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	$("#transaction_confirmation_officer_table .confirmation_registration_no").append(response[0]["transaction_client"][0]["registration_no"]);
			                	$("#transaction_confirmation_officer_table .confirmation_company_name").append(response[0]["transaction_client"][0]["company_name"]);
			                	
			                	$("#transaction_confirmation_officer_table .confirmation_resignation_of_company_secretary").append(response[0]["transaction_resignation_of_company_secretary"][0]["resignation_of_company_secretary"]);
			                	$("#transaction_confirmation_officer_table .confirmation_resignation_of_corporate_secretarial_agent").append(response[0]["transaction_resignation_of_company_secretary"][0]["resignation_of_corporate_secretarial_agent"]);
			                	$("#transaction_confirmation_officer_table .confirmation_resignation_of_corporate_secretarial_agent_address").append(response[0]["transaction_resignation_of_company_secretary"][0]["resignation_of_corporate_secretarial_agent_address"]);
			                			                	
			                	if(response[0]["transaction_client_officers"] != false)
				            	{
				            		for(var i = 0; i < response[0]["transaction_client_officers"].length; i++)
				            		{
					            		if(response[0]["transaction_client_officers"][i]["date_of_appointment"] != "" && response[0]["transaction_client_officers"][i]["appoint_resign_flag"] ==  "resign")
				            			{
						            		$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_cessation"] +'</td>';
									       	$a += '<td>'+ (response[0]["transaction_client_officers"][i]["reason"]!="" && response[0]["transaction_client_officers"][i]["reason"]!="NULL" ? response[0]["transaction_client_officers"][i]["reason"] : (response[0]["transaction_client_officers"][i]["reason_selected"] != null ? response[0]["transaction_client_officers"][i]["reason_selected"] : "")) +'</td>';
									        $a += '</tr>';
									        
									        $("#confirmation_officer_table").append($a);
									    }
									    else
									    {
									    	$("#confirmation_appoint_officer").show();
									    	$a="";
									        $a += '<tr class="confirmation_officer">';
									        $a += '<td>'+ response[0]["transaction_client_officers"][i]["position_name"] +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["identification_no"]!=null ? response[0]["transaction_client_officers"][i]["identification_no"] : response[0]["transaction_client_officers"][i]["register_no"]) +'</td>';
									        $a += '<td>'+ (response[0]["transaction_client_officers"][i]["company_name"]!=null ? response[0]["transaction_client_officers"][i]["company_name"] : response[0]["transaction_client_officers"][i]["name"]) +'</td>';
									       	$a += '<td>'+ response[0]["transaction_client_officers"][i]["date_of_appointment"] +'</td>';
									        $a += '</tr>';
									            
									        $("#confirmation_appoint_officer_table").prepend($a);
									    }
								    }
				            	}

				            	if(response[0]["transaction_billing"] != false)
				            	{
				            		$(".confirmation_billing").remove();

				            		for(var z = 0; z < response[0]["transaction_billing"].length; z++)
				            		{
					            		$a = ""; 
								        $a += '<tr class="confirmation_billing">';
								        $a += '<td><div class="input-group mb-md">'+response[0]["transaction_billing"][z]["service_name"]+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["invoice_description"]+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["currency_name"]+'</div><div>'+addCommas(response[0]["transaction_billing"][z]["amount"])+'</div></td>';
								        $a += '<td><div class="mb-md">'+response[0]["transaction_billing"][z]["unit_pricing_name"]+'</div></td>';
								        if(response[0]["transaction_billing"][z]["branch_name"] != "")
								        {
								        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?(response[0]["transaction_billing"][z]["firm_name"] +' ('+response[0]["transaction_billing"][z]["branch_name"]+')'):'')+'</div></td>';
								        }
								        else
								        {
								        	$a += '<td><div class="mb-md">'+((response[0]["transaction_billing"][z]["firm_name"] != null)?response[0]["transaction_billing"][z]["firm_name"]:'')+'</div></td>';
								        }
								        
								        $a += "</tr>";

								        $("#confirmation_billing_table").append($a); 

								    }
				            		
				            	}
			                }
			            });
            		}
            		else if($('.transaction_task option:selected').val() == 35)
	            	{
	            		$.ajax({
			                type: "POST",
			                url: "transaction/get_omp_grant_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_omp_grant").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	var confirm_transaction_omp_grant = response[0]["transaction_omp_grant"];

			                	$("#confirmation_date_of_quotation").html(confirm_transaction_omp_grant[0]['date_of_quotation']);
					            $("#confirmation_postal_code").html(confirm_transaction_omp_grant[0]['postal_code']);
					            $("#confirmation_street_name").html(confirm_transaction_omp_grant[0]['street_name']);
					            $("#confirmation_building_name").html(confirm_transaction_omp_grant[0]['building_name']);
					            $("#confirmation_unit_no1").html(confirm_transaction_omp_grant[0]['unit_no1']);
					            $("#confirmation_unit_no2").html(confirm_transaction_omp_grant[0]['unit_no2']);

					            $("#confirmation_attention_name").html(confirm_transaction_omp_grant[0]['attention_name']);
					            $("#confirmation_attention_title").html(confirm_transaction_omp_grant[0]['attention_title']);
					            $("#confirmation_grant_date").html(confirm_transaction_omp_grant[0]['grant_date']);
					            $("#confirmation_quotation_ref").html(confirm_transaction_omp_grant[0]['quotation_ref']);
					            $("#confirmation_cash_deposit").html(addCommas(confirm_transaction_omp_grant[0]['cash_deposit']));
					            $("#confirmation_success_fees").html(confirm_transaction_omp_grant[0]['success_fees']);
					            // if(confirm_transaction_omp_grant[0]['less_the_cash_deposit'] > 0)
					            // {
					            $("#confirmation_less_the_cash_deposit").html(addCommas(confirm_transaction_omp_grant[0]['less_the_cash_deposit']));
					            //}
							}
			            });
	            	}
	            	else if($('.transaction_task option:selected').val() == 36)
	            	{
	            		$.ajax({
			                type: "POST",
			                url: "transaction/get_purchase_common_seal_info",
			                data: {"transaction_master_id":$('#transaction_master_id').val()}, // <--- THIS IS THE CHANGE
			                dataType: "json",
			                async: false,
			                success: function(response){
			                	$("#transaction_confirmation_purchase_common_seal").remove();
			                	$("#transaction_confirm").append(response['interface']);

			                	var confirm_transaction_purchase_common_seal_data = response[0]["transaction_purchase_common_seal_data"];

			                	$(".confirmation_purchase_date").text(confirm_transaction_purchase_common_seal_data[0]["date"]);
							    $(".confirmation_common_seal_vendor").text(confirm_transaction_purchase_common_seal_data[0]["vendor_name"] + " (" + confirm_transaction_purchase_common_seal_data[0]["vendor_email"] + ")");

							    for(var t = 0; t < confirm_transaction_purchase_common_seal_data.length; t++)
							    {
							        $a = ""; 
							        $a += '<tr class="row_purchase_common_seal">';
							        $a += '<td>'+confirm_transaction_purchase_common_seal_data[t]["company_name"]+'</td>';
							        $a += '<td>'+confirm_transaction_purchase_common_seal_data[t]["registration_no"]+'</td>';
							        $a += '<td>'+confirm_transaction_purchase_common_seal_data[t]["order_for"]+'</td>';
							        $a += '</tr>';
							        
							        $("#confirmation_body_purchase_common_seal").prepend($a); 
							    }

							    if(confirm_transaction_purchase_common_seal_data[0]["service_status"] == 2 && confirm_transaction_purchase_common_seal_data[0]["status"] == 2)
                				{
							    	$(".sendEmailForCommonSeal").prop('disabled', true);
							    }
			                }
			            });
	            	}
            }

            $wTransactionprevious[ 0 >= newindex ? 'addClass' : 'removeClass' ]( 'hidden' );
		},
		onTabShow: function(tab, navigation, index) {
			if($('#transaction_form').find(".transaction_task option:selected").val() == 1 || $('#transaction_form').find(".transaction_task option:selected").val() == 4 || $('#transaction_form').find(".transaction_task option:selected").val() == 5 || $('#transaction_form').find(".transaction_task option:selected").val() == 6 || $('#transaction_form').find(".transaction_task option:selected").val() == 7 || $('#transaction_form').find(".transaction_task option:selected").val() == 12 || $('#transaction_form').find(".transaction_task option:selected").val() == 15 || $('#transaction_form').find(".transaction_task option:selected").val() == 26 || $('#transaction_form').find(".transaction_task option:selected").val() == 33 || $('#transaction_form').find(".transaction_task option:selected").val() == 34 || $('#transaction_form').find(".transaction_task option:selected").val() == 31 || $('#transaction_form').find(".transaction_task option:selected").val() == 32 || $('#transaction_form').find(".transaction_task option:selected").val() == 34)
			{
				var numTabs = $('#transaction_form').find('.tab-pane').length;
			}
			else
			{
				var numTabs = $('#transaction_form').find('.tab-pane:hidden').length;
			}

			if($('#transaction_form').find(".transaction_task option:selected").val() != 36)
			{
				$('#transaction_form')
	                .find('ul.pager li.next')
	                	.attr('type', 'submit')
	                    .removeClass('disabled')
	                    .addClass('lodge_button')
	                    .find('a')
	                    .html(index === numTabs - 1 ? 'Complete This Transaction' : 'Go To Next Page');
			}
			else
			{
				if(index == 3)
				{
					$('#transaction_form')
		                .find('ul.pager li.next')
		                	.attr('type', 'submit')
		                    .removeClass('disabled') 
		                    .addClass('lodge_button')
		                    .find('a')
		                    .html('Go To Next Page')
		                    .hide();
				}
				else
				{
					$('#transaction_form')
		                .find('ul.pager li.next')
		                	.attr('type', 'submit')
		                    .removeClass('disabled')    // Enable the Next button
		                    .addClass('lodge_button')
		                    .find('a')
		                    .html('Go To Next Page')
		                    .show();
				}
			}

            if(index === numTabs - 1)
            {
            	$.ajax({
	                type: "POST",
	                url: "transaction/check_lodge_status",
	                data: {"transaction_master_id":$('#transaction_master_id').val(), "company_code":transaction_company_code}, // <--- THIS IS THE CHANGE
	                dataType: "json",
	                async: false,
	                success: function(response){
	                	if(response[0]['get_lodge_status'] != false)
	                	{
		                	if(response[0]['get_lodge_status'][0]['completed'] == '1')
		                	{
		                		$('.lodge_button').addClass('hidden');
		                	}
		                }
	                	
	                }
	            });
            	//if client condition
            	if(user_base_client)
				{
					$('.lodge_button').addClass('hidden');
				}
            }
            else
            {
            	$('.lodge_button').removeClass('hidden');
            	$('.lodge_button').addClass('show');
            }

		}
	});
	
	function submit_complete_transaction()
	{
		if($("#transaction_task").val() == "26")
		{
			var client_status_affected = "(Status will become: Gazetted To Be Struck Off)";
		}
		else if($("#transaction_task").val() == "4")
		{
			var client_status_affected = "(Please make sure you are login in Quickbook Online from Secretary System if you want to auto update this company address in Quickbook Online.)";
		}
		else if($("#transaction_task").val() == "12")
		{
			var client_status_affected = "(Please make sure you are login in Quickbook Online from Secretary System if you want to auto update this company name in Quickbook Online.)";
		}
		else
		{
			var client_status_affected = "";
		}

		bootbox.confirm({
	        message: "You are unable to edit after you save. Are you sure you want to save this completion? "+client_status_affected,
	        closeButton: false,
	        buttons: {
	            confirm: {
	                label: 'Yes'
	            },
	            cancel: {
	                label: 'No',
	                className: 'btn-danger'
	            }
	        },
	        callback: function (result) {
	        	if(result)
	        	{
		        	$("#transaction_task").prop("disabled", false);
		        	if($("#transaction_task").val() == "1")
					{
						 $.ajax({
					        type: "POST", 
					        url: "transaction/set_transaction_master_id",
					        data: {"transaction_master_id": $('#transaction_master_id').val()},
					        dataType: "json",
					        success: function(data){
					            // var win = window.open();
					            // win.document.write(data);
					            window.open('./transaction/open_acknowledgement_page', '_blank');
					            //$("#transaction_data").append(array_for_share_allot['interface']); 
					            $("#transaction_form").submit();
					        }
					    }); 
						//window.open('./transaction/open_acknowledgement_page', '_blank'); 
					}
					else
					{
						$("#transaction_form").submit();
					}
					
				}
			}
		});
	}
	
	function validateTransactionTab(index) {
		//console.log("index==============="+index);
        if(index == 1)
	    {
	    	if($('.transaction_task option:selected').val() == 2 || $('.transaction_task option:selected').val() == 3 || $('.transaction_task option:selected').val() == 4 || $('.transaction_task option:selected').val() == 7)
			{
				if(!$('#uen').val()) {
					//alert('You must enter your activity1');
					//$('[href="#transaction_data"]').tab('show');
					$('#uen').focus();
					toastr.error("Please fill in the Registration No field", "Error");
					return false;
				}
			}
	    }
	    return true;
    }

	/*
	Wizard #2
	*/
	var $w2finish = $('#w2').find('ul.pager li.finish'),
		$w2validator = $("#w2 form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

	$w2finish.on('click', function( ev ) {
		ev.preventDefault();
		var validated = $('#w2 form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
	});

	$('#w2').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var validated = $('#w2 form').valid();
			if( !validated ) {
				$w2validator.focusInvalid();
				return false;
			}
		},
		onTabClick: function( tab, navigation, index, newindex ) {
			if ( newindex == index + 1 ) {
				return this.onNext( tab, navigation, index, newindex);
			} else if ( newindex > index + 1 ) {
				return false;
			} else {
				return true;
			}
		},
		onTabChange: function( tab, navigation, index, newindex ) {
			var totalTabs = navigation.find('li').size() - 1;
			$w2finish[ newindex != totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
			$('#w2').find(this.nextSelector)[ newindex == totalTabs ? 'addClass' : 'removeClass' ]( 'hidden' );
		}
	});

	/*
	Wizard #3
	*/
	var $w3finish = $('#w3').find('ul.pager li.finish'),
		$w3validator = $("#w3 form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

	$w3finish.on('click', function( ev ) {
		ev.preventDefault();
		var validated = $('#w3 form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
	});

	$('#w3').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var validated = $('#w3 form').valid();
			if( !validated ) {
				$w3validator.focusInvalid();
				return false;
			}
		},
		onTabClick: function( tab, navigation, index, newindex ) {
			if ( newindex == index + 1 ) {
				return this.onNext( tab, navigation, index, newindex);
			} else if ( newindex > index + 1 ) {
				return false;
			} else {
				return true;
			}
		},
		onTabChange: function( tab, navigation, index, newindex ) {
			var $total = navigation.find('li').size() - 1;
			$w3finish[ newindex != $total ? 'addClass' : 'removeClass' ]( 'hidden' );
			$('#w3').find(this.nextSelector)[ newindex == $total ? 'addClass' : 'removeClass' ]( 'hidden' );
		},
		onTabShow: function( tab, navigation, index ) {
			var $total = navigation.find('li').length - 1;
			var $current = index;
			var $percent = Math.floor(( $current / $total ) * 100);
			$('#w3').find('.progress-indicator').css({ 'width': $percent + '%' });
			tab.prevAll().addClass('completed');
			tab.nextAll().removeClass('completed');
		}
	});

	/*
	Wizard #4
	*/
	var $w4finish = $('#w4').find('ul.pager li.finish'),
		$w4validator = $("#w4 form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

	$w4finish.on('click', function( ev ) {
		ev.preventDefault();
		var validated = $('#w4 form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
	});

	$('#w4').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var validated = $('#w4 form').valid();
			if( !validated ) {
				$w4validator.focusInvalid();
				return false;
			}
		},
		onTabClick: function( tab, navigation, index, newindex ) {
			if ( newindex == index + 1 ) {
				return this.onNext( tab, navigation, index, newindex);
			} else if ( newindex > index + 1 ) {
				return false;
			} else {
				return true;
			}
		},
		onTabChange: function( tab, navigation, index, newindex ) {
			var $total = navigation.find('li').size() - 1;
			$w4finish[ newindex != $total ? 'addClass' : 'removeClass' ]( 'hidden' );
			$('#w4').find(this.nextSelector)[ newindex == $total ? 'addClass' : 'removeClass' ]( 'hidden' );
		},
		onTabShow: function( tab, navigation, index ) {
			var $total = navigation.find('li').length - 1;
			var $current = index;
			var $percent = Math.floor(( $current / $total ) * 100);
			$('#w4').find('.progress-indicator').css({ 'width': $percent + '%' });
			tab.prevAll().addClass('completed');
			tab.nextAll().removeClass('completed');
		}
	});

	/*
	Wizard #5
	*/
	var $w5finish = $('#w5').find('ul.pager li.finish'),
		$w5validator = $("#w5 form").validate({
		highlight: function(element) {
			$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
		},
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
			$(element).remove();
		},
		errorPlacement: function( error, element ) {
			element.parent().append( error );
		}
	});

	$w5finish.on('click', function( ev ) {
		ev.preventDefault();
		var validated = $('#w5 form').valid();
		if ( validated ) {
			new PNotify({
				title: 'Congratulations',
				text: 'You completed the wizard form.',
				type: 'custom',
				addclass: 'notification-success',
				icon: 'fa fa-check'
			});
		}
	});

	$('#w5').bootstrapWizard({
		tabClass: 'wizard-steps',
		nextSelector: 'ul.pager li.next',
		previousSelector: 'ul.pager li.previous',
		firstSelector: null,
		lastSelector: null,
		onNext: function( tab, navigation, index, newindex ) {
			var validated = $('#w5 form').valid();
			if( !validated ) {
				$w5validator.focusInvalid();
				return false;
			}
		},
		onTabChange: function( tab, navigation, index, newindex ) {
			var $total = navigation.find('li').size() - 1;
			$w5finish[ newindex != $total ? 'addClass' : 'removeClass' ]( 'hidden' );
			$('#w5').find(this.nextSelector)[ newindex == $total ? 'addClass' : 'removeClass' ]( 'hidden' );
		},
		onTabShow: function( tab, navigation, index ) {
			var $total = navigation.find('li').length;
			var $current = index + 1;
			var $percent = ( $current / $total ) * 100;
			$('#w5').find('.progress-bar').css({ 'width': $percent + '%' });
		}
	});

}).apply( this, [ jQuery ]);
