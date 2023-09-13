var pathArray = location.href.split( '/' );
var protocol = pathArray[0];
var host = pathArray[2];
var folder = pathArray[3];
var url = protocol + '//' + host + '/' + folder + '/';
var asc = true, name_asc = true, position_asc = true, dateofappointment_asc = true, dateofcessation_asc = true;

function optionCheck(officer_element, date) {
		//console.log(jQuery(officer_element).parent().parent().parent());
		var tr = jQuery(officer_element).parent().parent().parent();
		/*tr.find("DIV.td").each(function(){
			jQuery(this).find("input").val('');
		});*/
		//tr.find('input[name="name[]"]').removeAttr('readOnly');
		//tr.find("DIV.td").each(function(){
			//jQuery(this).find("input").val('');
			//console.log(tr.find('select[name="position[]"]').val());
			if(tr.find('select[name="position[]"]').val() == "7")
			{
				//console.log("ininini");
				tr.find("DIV#alternate_of").removeAttr('hidden');
				tr.find('select[name="alternate_of[]"]').removeAttr('disabled');
				$("#loadingmessage").show();
				$.ajax({
					type: "POST",
					url: "masterclient/get_director",
					data: {"company_code": tr.find('input[name="company_code"]').val(), "date_of_appointment": date}, // <--- THIS IS THE CHANGE
					dataType: "json",
					success: function(data){
						$("#loadingmessage").hide();
			            if(data.tp == 1){
			            	tr.find('select[name="alternate_of[]"]').html(""); 
			            	tr.find('select[name="alternate_of[]"]').append($('<option>', {
							    value: '0',
							    text: 'Select Director'
							}));
			            	
			            	//option.attr('value', '').text("Select Director");
			            	//tr.find('select[name="alternate_of[]"]').html("Select Director");
			            	//option.attr('value', '0').text("Select Director");
			                $.each(data['result'], function(key, val) {
			                    var option = $('<option />');
			                    option.attr('value', key).text(val);
			                    if(data.selected_director != null && key == data.selected_director)
			                    {
			                        option.attr('selected', 'selected');
			                    }
			                    
			                    tr.find('select[name="alternate_of[]"]').append(option);
			                });
			                
			                //$(".nationality").prop("disabled",false);
			            }
			            else{
			                alert(data.msg);
			            }

						
					}				
				});
			}
			else
			{
				tr.find("DIV#alternate_of").attr("hidden","true");
				tr.find('select[name="alternate_of[]"]').attr('disabled', 'disabled');
			}

			 tr.find('#officer_date_of_appointment').datepicker({ 
			    dateFormat:'dd/mm/yyyy',
			}).datepicker('setStartDate', latest_incorporation_date); 
			
		//});

	    /*document.getElementById("wattage_input").value = '';
	    document.getElementById("dimmable_input").value = '';*/
	}

	toastr.options = {
	  "positionClass": "toast-bottom-right"
	}

	/*$("#date_of_appointment").datepicker().on('changeDate', function(ev) {
		console.log('Input changed');
		$(this).parent().parent().parent('form').find("DIV#form_date_of_appointment").html( "" );
		console.log($(this).parent().parent().parent('form').find("DIV#form_date_of_appointment"));
		if($(this).parent().parent().parent('form').find('select[name="position[]"]').val()!="0")
		{
			if($(this).val() == "")
			{
				toastr.error("Date of appointment should be on or after date incorporation of the Company.", "Error");
			}
		}

		
	});*/
	/*$("#date_of_appointment").live('change',function(eventObject){
		//eventObject.stopPropagation();
		console.log("Change");
		return false;
	});*/

	 
	$("select[name='position[]']").live('change',function(){
		$(this).parent().parent('form').find("DIV#form_position").html( "" );
		//$(this).parent().parent('form').find("input[name='identification_register_no[]']").val( "" );
		//$(this).parent().parent('form').find("input[name='name[]']").val( "" );
		//$(this).parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");

		if($(this).parent().parent('form').find('select[name="position[]"]').val() != "7")
		{
			$(this).parent().parent('form').find('select[name="alternate_of[]"]').val("0");
			$(this).parent().parent('form').find("DIV#alternate_of").attr("hidden","true");
			$(this).parent().parent('form').find('select[name="alternate_of[]"]').attr('disabled', true);
					
		}

		if($(this).parent().parent('form').find('select[name="position[]"]').val() == "7")
		{
			$(this).parent().parent('form').find("input[name='date_of_appointment[]']").val( "" );
			$(this).parent().parent('form').find('select[name="alternate_of[]"]').attr('disabled', false);
		}
		//$(this).parent().parent('form').find('a#add_office_person_link').attr("hidden");
		/*console.log($(this).parent().parent('form').find("#position").val());
		if($(this).parent().parent('form').find("#position").val() != 7)
		{
			$(this).parent().parent().parent('form').find(".select_alternate_of").val(0);
		}*/

		$('#officer_date_of_appointment').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		}).datepicker('setStartDate', latest_incorporation_date);

		$('#date_of_cessation').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		}).datepicker('setStartDate', latest_incorporation_date);
	});
	// $("input[name='date_of_appointment[]']").live('change',function(){
	// 	console.log($(this).val());
	// });

	$("#gid_add_officer").live('change',function(){
		$(this).parent().parent('form').find("DIV#form_identification_register_no").html( "" );
	});
	$("#name").live('change',function(){
		$(this).parent().parent('form').find("DIV#form_name").html( "" );
	});

	
	$("#date_of_cessation").live('change',function(){
		$(this).parent().parent().parent('form').find("DIV#form_date_of_cessation").html( "" );
	});
	$(".select_alternate_of").live('change',function(){

		if($(this).find('option:selected').text() == $(this).parent().parent().parent('form').find("input[name='name[]']").val())
		{
			$(this).find('option:eq(0)').prop('selected', true);

			toastr.error("Cannot choose the same person with alternate director.", "Error");
			//console.log($(this).find('option:eq(0)').prop('selected', true));
		}
		else
		{
			$(this).parent().parent().parent('form').find("DIV#form_alternate_of").html( "" );
			//$(this).parent().parent().parent('form').find("input[name='identification_register_no[]']").val( "" );
			//$(this).parent().parent().parent('form').find("input[name='name[]']").val( "" );
			//$(this).parent().parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
			//console.log($(this).find('option:selected').text());

			$("#loadingmessage").show();
			$.ajax({
				type: "POST",
				url: "masterclient/get_director_appointment_date",
				data: {"client_officers_id": $(this).parent().parent().parent('form').find(".select_alternate_of").val()}, // <--- THIS IS THE CHANGE
				async: false,
				dataType: "json",
				success: function(data){
					//console.log(data);
		            if(data.length != 0)
		            {
						$array = data[0]["date_of_appointment"].split("/");
						$tmp = $array[0];
						$array[0] = $array[1];
						$array[1] = $tmp;
						//unset($tmp);
						$date = $array[0]+"/"+$array[1]+"/"+$array[2];
						//console.log(new Date($date_2));

						var date_of_appointment = new Date($date);

						$('#officer_date_of_appointment').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', date_of_appointment); 

						$('#date_of_cessation').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', date_of_appointment);
					}
					else
					{
						$('#officer_date_of_appointment').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', latest_incorporation_date); 

						$('#date_of_cessation').datepicker({ 
						    dateFormat:'dd/mm/yyyy',
						}).datepicker('setStartDate', latest_incorporation_date);
					}


					$("#loadingmessage").hide();
				}				
			});
		}
	});

	function add_officers_person(elem)
	{
	    //console.log(jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]'));
	    jQuery(elem).parent().parent().find('input[name="identification_register_no[]"]').val("");
	    jQuery(elem).attr('hidden',"true");
	}

	function changeDateFormat(date_info)
	{
		//console.log(date_info != "")
		if(date_info != "")
		{
			$array = date_info.split("/");
			$tmp = $array[0];
			$array[0] = $array[1];
			$array[1] = $tmp;
			//unset($tmp);
			$date = $array[2]+$array[0]+$array[1];
		}
		else
		{
			$date = "";
		}

		return $date;
	}

	/*function officer_change_date(latest_incorporation_date)
	{
		$('#officer_date_of_appointment').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		}).datepicker('setStartDate', latest_incorporation_date); 

		$('#date_of_cessation').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		}).datepicker('setStartDate', latest_incorporation_date);
	}*/

	function delete_client_officer(element)
	{
		var tr = jQuery(element).parent().parent().parent();
		var client_officer_id = tr.find('input[name="client_officer_id[]"]').val();
		var client_officer_name = tr.find('input[name="name[]"]').val();

		if(client_officer_id != undefined && client_officer_id != "")
		{
			bootbox.confirm({
		        message: "Are you sure you want to delete this record?",
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
		        		$('#loadingmessage').show();
						$.ajax({ //Upload common input
			                url: "masterclient/delete_officer",
			                type: "POST",
			                data: {"client_officer_id": client_officer_id, "client_officer_name": client_officer_name},
			                dataType: 'json',
			                success: function (response) {
			                	//console.log(response.Status);
			                	$('#loadingmessage').hide();
			                	if(response.Status == 1)
			                	{
			                		tr.remove();
			                		toastr.success("Updated Information.", "Updated");

			                	}
			                	else if(response.Status == 2)
			                	{
			                		location.reload();
			                		toastr.error("Cannot delete this director because he is alternated by an alternate director.", "Error");
			                	}
			                }
			            });
					}
				}
			});
		}
		else
		{
			tr.remove();
			toastr.success("Updated Information.", "Updated");
		}
	}

	function edit(element)
	{
		 //element.preventDefault();
		var tr = jQuery(element).parent().parent().parent();
		if(!tr.hasClass("editing")) 
		{
			tr.addClass("editing");
			tr.find("DIV.td").each(function()
			{
				if(!jQuery(this).hasClass("action"))
				{
					/*if(jQuery(this).find('input[name="name[]"]').val()=="")
					{
						jQuery(this).find("input").attr('disabled', false);
					}
					else
					{
						jQuery(this).find("input").attr('disabled', false);
					}*/

					jQuery(this).find('input[name="identification_register_no[]"]').attr('disabled', false);
					jQuery(this).find('input[name="date_of_appointment[]"]').attr('disabled', false);
					jQuery(this).find('input[name="date_of_cessation[]"]').attr('disabled', false);
					jQuery(this).find("select").attr('disabled', false);
					
					//jQuery(this).find(".datepicker").datepicker('disable');
					//jQuery(this).text("");
					//jQuery(this).append('<input type="text" value="'+value+'" />');
				} 
				else 
				{
					jQuery(this).find(".submit_officer_button").text("Save");
				}
			});
		} 
		else 
		{
/*			var form_id = $(element).closest('form').attr('id');*/

			//console.log(tr.find('input[name="name[]"]').val()=="");

			// if(tr.find('select[name="position[]"]').val()=="0" && tr.find('input[name="identification_register_no[]"]').val()=="" && tr.find('input[name="name[]"]').val()=="" && tr.find('input[name="date_of_appointment[]"]').val()=="" && tr.find('input[name="date_of_cessation[]"]').val()=="")
			// {
			// 	//console.log(jQuery(this).find('input[name="client_officer_id[]"]'));
			// 	var client_officer_id = tr.find('input[name="client_officer_id[]"]').val();
			// 	//console.log("client_officer_id==="+client_officer_id);
			// 	if(client_officer_id != undefined)
			// 	{
			// 		$.ajax({ //Upload common input
		 //                url: "masterclient/delete_officer",
		 //                type: "POST",
		 //                data: {"client_officer_id": client_officer_id},
		 //                dataType: 'json',
		 //                success: function (response) {
		 //                	//console.log(response.Status);
		 //                	if(response.Status == 1)
		 //                	{
		 //                		tr.remove();
		 //                		toastr.success("Updated Information.", "Updated");

		 //                	}
		 //                	else if(response.Status == 2)
		 //                	{
		 //                		location.reload();
		 //                		toastr.error("Cannot delete this director because he is alternated by an alternate director.", "Error");
		 //                	}
		 //                }
		 //            });
			// 	}
				
			// }
			// else
			// {
				var frm = $(element).closest('form');

				var frm_serialized = frm.serialize();

				$.ajax({
					type: "POST",
					url: "masterclient/check_officer_data",
					data: frm_serialized, // <--- THIS IS THE CHANGE
					dataType: "json",
					async: false,
					success: function(response)
					{
						if(response)
						{
							/*if (confirm('Do you want to submit?')) 
							{
					           officer_submit(frm_serialized, tr);
							} 
							else 
							{
							   return false;
							}*/
							bootbox.confirm("Do you want to submit? Do you want to overwrite previous document?", function (result) {
					            if (result) 
					            {
					            	officer_submit(frm_serialized, tr);
					            }
					            else
					            {
					            	return false;
					            }
					        });
						}
						else
						{
							officer_submit(frm_serialized, tr);
						}
					}
				});

				

				//console.log(frm_serialized);
				
			//}
		}
	}

	function officer_submit(frm_serialized, tr)
	{
		console.log(tr);
		$('#loadingmessage').show();
		$.ajax({ //Upload common input
            url: "masterclient/add_officer",
            type: "POST",
            data: frm_serialized,
            dataType: 'json',
            success: function (response) {
            	$('#loadingmessage').hide();
            	//console.log(response.Status);
                if (response.Status === 1) {
                	//var errorsDateOfCessation = ' ';
                	toastr.success(response.message, response.title);
                	tr.find("DIV#form_date_of_cessation").html("");
                	if(response.insert_client_officers_id != null)
                	{
                		tr.find('input[name="client_officer_id[]"]').attr('value', response.insert_client_officers_id);
                	}
                	tr.removeClass("editing");

                	tr.attr("data-position",tr.find('#position option:selected').text());
                	tr.attr("data-registe_no",tr.find('input[name="identification_register_no[]"]').val());
                	tr.attr("data-name",tr.find('input[name="name[]"]').val());
                	tr.attr("data-dateofappointment",tr.find('input[name="date_of_appointment[]"]').val());
                	tr.attr("data-dateofcessation",tr.find('input[name="date_of_cessation[]"]').val());
                	
                	/*tr.data('registe_no',tr.find('input[name="identification_register_no[]"]').val());
                	tr.data('name',tr.find('input[name="identification_register_no[]"]').val());*/
					tr.find("DIV.td").each(function(){
						if(!jQuery(this).hasClass("action")){
							
							//jQuery(this).find("input[name='name[]']").attr('readonly', true);
							jQuery(this).find("input[name='identification_register_no[]']").attr('disabled', true);
							jQuery(this).find("input[name='date_of_appointment[]']").attr('disabled', true);
							jQuery(this).find("input[name='date_of_cessation[]']").attr('disabled', true);
							jQuery(this).find("select").attr('disabled', true);

							

							
						} else {
							jQuery(this).find(".submit_officer_button").text("Edit");
						}
					});
				    
                }
                else if (response.Status === 2)
                {
                	//console.log(response.data);
                	toastr.error(response.message, response.title);
                }
                else
                {
                	//console.log(tr.find("DIV#form_date_of_cessation"));
					toastr.error(response.message, response.title);
                	if (response.error["date_of_cessation"] != "")
                	{
                		var errorsDateOfCessation = '<span class="help-block">*' + response.error["date_of_cessation"] + '</span>';
                		tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
                		tr.find("#date_of_cessation").val("");

                	}
                	else
                	{
                		var errorsDateOfCessation = ' ';
                		tr.find("DIV#form_date_of_cessation").html( errorsDateOfCessation );
                	}

                	if (response.error["alternate_of"] != "")
                	{
                		var errorsAlternateOf = '<span class="help-block">' + response.error["alternate_of"] + '</span>';
                		tr.find("DIV#form_alternate_of").html( errorsAlternateOf );

                	}
                	else
                	{
                		var errorsAlternateOf = ' ';
                		tr.find("DIV#form_alternate_of").html( errorsAlternateOf );
                	}

                	if (response.error["identification_register_no"] != "")
                	{
                		var errorsIdentificationRegisterNo = '<span class="help-block">*' + response.error["identification_register_no"] + '</span>';
                		tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );

                	}
                	else
                	{
                		var errorsIdentificationRegisterNo = ' ';
                		tr.find("DIV#form_identification_register_no").html( errorsIdentificationRegisterNo );
                	}

                	if (response.error["name"] != "")
                	{
                		var errorsName = '<span class="help-block">*' + response.error["name"] + '</span>';
                		tr.find("DIV#form_name").html( errorsName );

                	}
                	else
                	{
                		var errorsName = ' ';
                		tr.find("DIV#form_name").html( errorsName );
                	}

                	if (response.error["date_of_appointment"] != "")
                	{
                		var errorsDateOfAppointment = '<span class="help-block">*' + response.error["date_of_appointment"] + '</span>';
                		tr.find("DIV#form_date_of_appointment").html( errorsDateOfAppointment );

                	}
                	else
                	{
                		var errorsDateOfAppointment = ' ';
                		tr.find("DIV#form_date_of_appointment").html( errorsDateOfAppointment );
                	}

                	if (response.error["position"] != "")
                	{
                		var errorsPosition = '<span class="help-block">*' + response.error["position"] + '</span>';
                		tr.find("DIV#form_position").html( errorsPosition );

                	}
                	else
                	{
                		var errorsPosition = ' ';
                		tr.find("DIV#form_position").html( errorsPosition );
                	}
                }
            }
        });
	}

	$(document).ready(function(){
	    $('#id_header')
	    .wrapInner('<span title="sort this column"/>')
	    .each(function(){
	        
	        var th = $(this),
	            thIndex = th.index(),
	            inverse = false;
	        
	        th.click(function(){
	            //console.log($("#body_officer").find('.sort_id'));
	            if(asc)
	            {
	            	$("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? 1 : -1;
					    }));
					});

					asc = false;
	            }
	            else
	            {
		            $("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('registe_no').toString().toLowerCase()) < ($(a).data('registe_no').toString().toLowerCase()) ? -1 : 1;
					    }));
					});

					asc = true;
	            }
	            $('#officer_date_of_appointment').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date); 

				$('#date_of_cessation').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date);
	        });
	            
	    });

	    $('#id_name')
	    .wrapInner('<span title="sort this column"/>')
	    .each(function(){
	        
	        var th = $(this),
	            thIndex = th.index(),
	            inverse = false;
	        
	        th.click(function(){
	            //console.log($("#body_officer").find('.sort_id'));
	            if(name_asc)
	            {
	            	$("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? 1 : -1;
					    }));
					});

					name_asc = false;
	            }
	            else
	            {
		            $("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('name').toString().toLowerCase()) < ($(a).data('name').toString().toLowerCase()) ? -1 : 1;
					    }));
					});

					name_asc = true;
	            }
	            $('#officer_date_of_appointment').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date); 

				$('#date_of_cessation').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date);
	        });
	            
	    });

	    $('#id_position')
	    .wrapInner('<span title="sort this column"/>')
	    .each(function(){
	        
	        var th = $(this),
	            thIndex = th.index(),
	            inverse = false;
	        
	        th.click(function(){
	            //console.log($("#body_officer").find('.sort_id'));
	            if(position_asc)
	            {
	            	$("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('position').toLowerCase()) < ($(a).data('position').toLowerCase()) ? 1 : -1;
					    }));
					});

					position_asc = false;
	            }
	            else
	            {
		            $("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('position').toLowerCase()) < ($(a).data('position').toLowerCase()) ? -1 : 1;
					    }));
					});

					position_asc = true;
	            }
	            $('#officer_date_of_appointment').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date); 

				$('#date_of_cessation').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date);
	        });
	            
	    });

	    $('#id_dateofappointment')
	    .wrapInner('<span title="sort this column"/>')
	    .each(function(){
	        
	        var th = $(this),
	            thIndex = th.index(),
	            inverse = false;
	        
	        th.click(function(){
	            //console.log($("#body_officer").find('.sort_id'));
	            if(dateofappointment_asc)
	            {
	            	$("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('dateofappointment')) < ($(a).data('dateofappointment')) ? 1 : -1;
					    }));
					});

					dateofappointment_asc = false;
	            }
	            else
	            {
		            $("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('dateofappointment')) < ($(a).data('dateofappointment')) ? -1 : 1;
					    }));
					});

					dateofappointment_asc = true;
	            }
	            $('#officer_date_of_appointment').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date); 

				$('#date_of_cessation').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date);
	        });
	            
	    });

	    $('#id_dateofcessation')
	    .wrapInner('<span title="sort this column"/>')
	    .each(function(){
	        
	        var th = $(this),
	            thIndex = th.index(),
	            inverse = false;
	        
	        th.click(function(){
	            //console.log($("#body_officer").find('.sort_id'));
	            if(dateofcessation_asc)
	            {
	            	$("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('dateofcessation')) < ($(a).data('dateofcessation')) ? 1 : -1;
					    }));
					});

					dateofcessation_asc = false;
	            }
	            else
	            {
		            $("#body_officer").each(function(){
					    $(this).html($(this).find('.sort_id').sort(function(a, b){
					        return ($(b).data('dateofcessation')) < ($(a).data('dateofcessation')) ? -1 : 1;
					    }));
					});

					dateofcessation_asc = true;
	            }
	            $('#officer_date_of_appointment').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date); 

				$('#date_of_cessation').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				}).datepicker('setStartDate', latest_incorporation_date);
	        });
	            
	    });


	});
	
	$(function () { 
		$('#search_position').change(function() {
	        $('#loadingmessage').css('display', 'block');
	        //console.log($('#loadingmessage'));
	        $.ajax({
				type: "POST",
				url: "masterclient/filter_position",
				data: $("#filter_position").serialize(), // <--- THIS IS THE CHANGE
				async: false,
				dataType: "json",
				success: function(response){
					$('#loadingmessage').hide();
					$(".sort_id").remove();

					client_officers = response.client_officers;
					get_client_officers();
				}
			});
	    });
	});

    get_client_officers();

    function get_client_officers()
    {
    	if(client_officers)
		{
			//console.log(client_officers);
			//console.log(client_officers[0]['name']);
			for(var i = 0; i < client_officers.length; i++)
			{
				$a="";
				$a += '<form class="tr sort_id" method="post" name="form'+i+'" id="form'+i+'" data-dateofcessation="'+changeDateFormat(client_officers[i]["date_of_cessation"])+'" data-dateofappointment="'+changeDateFormat(client_officers[i]["date_of_appointment"])+'" data-position="'+client_officers[i]["position_name"]+'" data-registe_no="'+ (client_officers[i]["identification_no"]!=null ? client_officers[i]["identification_no"] : client_officers[i]["register_no"]) +'" data-name="'+ (client_officers[i]["company_name"]!=null ? client_officers[i]["company_name"] : client_officers[i]["name"]) +'">';
				$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" id="company_code" value="'+client_officers[i]["company_code"]+'"/></div>';
				$a += '<div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value="'+client_officers[i]["id"]+'"/></div>';
				$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value="'+client_officers[i]["officer_id"]+'"/></div>';
				$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value="'+client_officers[i]["field_type"]+'"/></div>';
				$a += '<div class="td"><select class="form-control" style="text-align:right;width: 150px;" name="position[]" id="position'+i+'" disabled><option value="0" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" id="select_alternate_of'+i+'" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></div>';
				$a += '<div class="td"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="'+ (client_officers[i]["identification_no"]!=null ? client_officers[i]["identification_no"] : client_officers[i]["register_no"]) +'" id="gid_add_officer" disabled="disabled" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div>';
				$a += '<div class="td"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="'+ (client_officers[i]["company_name"]!=null ? client_officers[i]["company_name"] : client_officers[i]["name"]) +'" readonly/><div id="form_name"></div></div>';
				$a += '<div class="td"><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+client_officers[i]["date_of_appointment"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><div id="form_date_of_appointment"></div></div>';
				$a += '<div class="td">';
				$a += '<div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" value="'+client_officers[i]["date_of_cessation"]+'" placeholder="DD/MM/YYYY" disabled="disabled"></div><div id="form_date_of_cessation"></div>';
				$a += '</div>';
				$a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_officer_button" onclick="edit(this);">Edit</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_client_officer(this);">Delete</button></div></div>';
				$a += '</form>';
					
					/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
				$("#body_officer").prepend($a);
				//$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
				$('#officer_date_of_appointment').datepicker({ 
			    	dateFormat:'dd/mm/yyyy',
			    	endDate: date,

				}).on('changeDate', function(ev) {
					
					$(this).parent().parent().parent('form').find("DIV#form_date_of_appointment").html( "" );
					console.log($(this).val());
					
					/*if($(this).parent().parent().parent('form').find('select[name="position[]"]').val()!="0")
					{	console.log($(this).val());
						if($(this).val() == "")
						{
							toastr.error("Date of appointment should be on or after date incorporation of the Company.", "Error");
						}
					}*/
					if($(this).parent().parent().parent('form').find('select[name="position[]"]').val()=="7")
					{
						console.log($(this).parent().parent().parent('form').find('select[name="position[]"]').val());
						optionCheck($(this) , $(this).val());
					}

					
				});

				$('#date_of_cessation').datepicker({ 
				    dateFormat:'dd/mm/yyyy',
				    endDate: date,
				});

				if(client_officers[i]["position"] != "7")
				{
					!function (i) {
						$.ajax({
							type: "POST",
							url: "masterclient/check_incorporation_date",
							data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
							dataType: "json",
							async: false,
							success: function(response)
							{
								//console.log("incorporation_date==="+response[0]["incorporation_date"]);
								$array = response[0]["incorporation_date"].split("/");
								$tmp = $array[0];
								$array[0] = $array[1];
								$array[1] = $tmp;
								//unset($tmp);
								$date_2 = $array[0]+"/"+$array[1]+"/"+$array[2];
								//console.log(new Date($date_2));

								$latest_incorporation_date = new Date($date_2);
								$('#officer_date_of_appointment').datepicker({ 
								    dateFormat:'dd/mm/yyyy',
								}).datepicker('setStartDate', $latest_incorporation_date);

								$('#date_of_cessation').datepicker({ 
								    dateFormat:'dd/mm/yyyy',
								}).datepicker('setStartDate', $latest_incorporation_date);
							}
						});
					} (i);
				}

				!function (i) {
					$.ajax({
						type: "POST",
						url: "masterclient/get_client_officers_position",
						data: {"position": client_officers[i]["position"]},
						dataType: "json",
						success: function(data){
							//console.log(data);
							$("#form"+i+" #position"+i+"").find("option:eq(0)").html("Select Position");
				            if(data.tp == 1){
				                $.each(data['result'], function(key, val) {
				                    var option = $('<option />');
				                    option.attr('value', key).text(val);
				                    if(data.selected_client_officers_position != null && key == data.selected_client_officers_position)
				                    {
				                        option.attr('selected', 'selected');
				                    }
				                    $("#form"+i+" #position"+i+"").append(option);
				                });
				                
				                //$(".nationality").prop("disabled",false);
				            }
				            else{
				                alert(data.msg);
				            }

							
						}				
					});
				} (i);

				if(client_officers[i]["position"] == "7")
				{
					//console.log("i==="+i);
					$("#form"+i+" #alternate_of").removeAttr('hidden');

					/*var selected_company_code = client_officers[i]["company_code"];
					var selected_alternate_of = client_officers[i]["alternate_of"];
					var alternate_of_form = "#form"+i+" #alternate_of #select_alternate_of"+i+"";*/

					!function (i) {
						$.ajax({
							type: "POST",
							url: "masterclient/get_director",
							data: {"company_code": client_officers[i]["company_code"], "date_of_appointment": client_officers[i]["date_of_appointment"], "alternate_of": client_officers[i]["alternate_of"]}, // <--- THIS IS THE CHANGE
							dataType: "json",
							async: false,
							success: function(data){
					            $("#form"+i+" #alternate_of #select_alternate_of"+i+"").find("option:eq(0)").html("Select Director");
					            if(data.tp == 1){
					                $.each(data['result'], function(key, val) {
					                    var option = $('<option />');
					                    option.attr('value', key).text(val);
					                    if(data.selected_director != null && key == data.selected_director)
					                    {
					                        option.attr('selected', 'selected');
					                        $("#form"+i+" #alternate_of #select_alternate_of"+i+"").attr('disabled', 'disabled')
					                        /*if (data.selected_director == 166)
					                        {
					                            console.log("selected_director=166");
					                            document.getElementById("nationalityId").disabled = true;
					                        }*/
					                    }
					                    $("#form"+i+" #alternate_of #select_alternate_of"+i+"").append(option);
					                    //console.log("#form"+i+" #alternate_of #select_alternate_of"+i+"");
					                });
					                //$(".nationality").prop("disabled",false);
					            }
					            else{
					                alert(data.msg);
					            }
							}				
						});

						$.ajax({
							type: "POST",
							url: "masterclient/get_director_appointment_date",
							data: {"client_officers_id": client_officers[i]["alternate_of"]}, // <--- THIS IS THE CHANGE
							async: false,
							dataType: "json",
							success: function(data){
								//console.log(data);
					            
								$array = data[0]["date_of_appointment"].split("/");
								$tmp = $array[0];
								$array[0] = $array[1];
								$array[1] = $tmp;
								//unset($tmp);
								$date = $array[0]+"/"+$array[1]+"/"+$array[2];
								//console.log(new Date($date_2));

								var date_of_appointments = new Date($date);

								//console.log(date_of_appointments);

								$('#form'+i+' #officer_date_of_appointment').datepicker({ 
								    dateFormat:'dd/mm/yyyy',
								}).datepicker('setStartDate', date_of_appointments); 

								$('#form'+i+' #date_of_cessation').datepicker({ 
								    dateFormat:'dd/mm/yyyy',
								}).datepicker('setStartDate', date_of_appointments);


								//$("#loadingmessage").hide();
							}				
						});

					} (i);
				}
			}
		}
    }
	

	/*else
	{
		console.log("client_officers="+client_officers);
	}*/

	$count_officer = 0;
	$(document).on('click',"#officers_Add",function() {
			// alert("A");
			
			// $b = $("#body_chargee").html();

			/*tr
						display: table-row;
		    vertical-align: inherit;
		    border-color: inherit;*/

		    /*td
		    display: table-cell;
		        border: 1px solid #dddddd;
		        padding: 5px;
		        line-height: 1.42857143;
		    vertical-align: top;*/

			/*    th
			display: table-cell;
			font-weight: bold;
			text-align: left;
			line-height: 1.42857143;
			border: 1px solid #dddddd;
			padding: 5px;*/
				/*<form class="tr">
			<div class="td">1</div>
			<div class="td">2</div>
			<div class="td">3</div>
			<div class="td">4</div>
			<div class="td action"><button type="button" onclick="edit(this);">edit</button></div>
		</form>*//*<form class="tr" method="post" action="<?php echo site_url();?>masterclient/add_officer">*/
		$count_officer++;
	 	$a=""; 
	 	/*<select class="input-sm" style="text-align:right;width: 150px;" id="position" name="position[]" onchange="optionCheck(this);"><option value="Director" >Director</option><option value="CEO" >CEO</option><option value="Manager" >Manager</option><option value="Secretary" >Secretary</option><option value="Auditor" >Auditor</option><option value="Managing Director" >Managing Director</option><option value="Alternate Director">Alternate Director</option></select>*/
		$a += '<form class="tr editing sort_id" method="post" name="form'+$count_officer+'" id="form'+$count_officer+'">';
		$a += '<div class="hidden"><input type="text" class="form-control" name="company_code" value="'+company_code+'"/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="client_officer_id[]" id="client_officer_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="officer_id" id="officer_id" value=""/></div>';
		$a += '<div class="hidden"><input type="text" class="form-control" name="officer_field_type" id="officer_field_type" value=""/></div>';
		$a += '<div class="td"><select class="form-control" style="text-align:right;width: 150px;" name="position[]" id="position"><option value="0" >Select Position</option></select><div id="form_position"></div><div id="alternate_of" hidden><p style="font-weight:bold;">Alternate of: </p><select class="form-control select_alternate_of" style="text-align:right;width: 150px;" name="alternate_of[]"><option value="" >Select Director</option></select><div id="form_alternate_of"></div></div></div>';
		$a += '<div class="td"><input type="text" style="text-transform:uppercase;" name="identification_register_no[]" class="form-control" value="" id="gid_add_officer" maxlength="15"/><div id="form_identification_register_no"></div><div style="height:16px;" ><a class="" href="'+ url + 'personprofile/add/1" style="cursor:pointer;" id="add_office_person_link" target="_blank" hidden onclick="add_officers_person(this)"><div style="cursor:pointer;" id="click_add_person">Click here to <span style="font-weight:bold">Add Person</span></div></a></div></div>';
		$a += '<div class="td"><input type="text" style="text-transform:uppercase;" name="name[]" id="name" class="form-control" value="" readonly/><div id="form_name"></div></div>';
		$a += '<div class="td"><div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="officer_date_of_appointment" name="date_of_appointment[]" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" data-plugin-datepicker="" value=""></div><div id="form_date_of_appointment"></div></div>';
		$a += '<div class="td">';
		$a += '<div class="input-group" style="width: 150px;"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="form-control datepicker" id="date_of_cessation" name="date_of_cessation[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker="" placeholder="DD/MM/YYYY" value=""></div><div id="form_date_of_cessation"></div>';
		$a += '</div>';
		$a += '<div class="td action"><div style="display: inline-block; margin-right: 5px; margin-bottom: 5px;"><button type="button" class="btn btn-primary submit_officer_button" onclick="edit(this);">Save</button></div><div style="display: inline-block;"><button type="button" class="btn btn-primary" onclick="delete_client_officer(this);">Delete</button></div></div>';
		$a += '</form>';
		
		/*<input type="button" value="Save" id="save" name="save'+$count_officer+'" class="btn btn-primary" onclick="save(this);">*/
		$("#body_officer").prepend($a); 
		//$('.datepicker').datepicker({ dateFormat:'yyyy-mm=dd'});
		//console.log(date);
		$('#officer_date_of_appointment').datepicker({ 
	    	dateFormat:'dd/mm/yyyy',
	    	endDate: date,

		}).datepicker('setStartDate', latest_incorporation_date).on('changeDate', function(ev) {
			
			$(this).parent().parent().parent('form').find("DIV#form_date_of_appointment").html( "" );
			//console.log($(this).val());
			
			/*if($(this).parent().parent().parent('form').find('select[name="position[]"]').val()!="0")
			{	console.log($(this).val());
				if($(this).val() == "")
				{
					toastr.error("Date of appointment should be on or after date incorporation of the Company.", "Error");
				}
			}*/
			if($(this).parent().parent().parent('form').find('select[name="position[]"]').val()=="7")
			{
				//console.log($(this).parent().parent().parent('form').find('select[name="position[]"]').val());
				optionCheck($(this) , $(this).val());
			}

			
		});

		$('#date_of_cessation').datepicker({ 
		    dateFormat:'dd/mm/yyyy',
		    endDate: date,
		}).datepicker('setStartDate', latest_incorporation_date);

		$.ajax({
			type: "GET",
			url: "masterclient/get_client_officers_position",
			dataType: "json",
			success: function(data){
				//console.log(data);
	            
	            //console.log(data);
	            if(data.tp == 1){
	            	/*tr.find('select[name="alternate_of[]"]').html(""); 
	            	tr.find('select[name="alternate_of[]"]').append($('<option>', {
					    value: '0',
					    text: 'Select Director'
					}));*/
	            	
	            	//option.attr('value', '').text("Select Director");
	            	//tr.find('select[name="alternate_of[]"]').html("Select Director");
	            	//option.attr('value', '0').text("Select Director");
	                $.each(data['result'], function(key, val) {
	                    var option = $('<option />');
	                    option.attr('value', key).text(val);
	                    if(data.selected_client_officers_position != null && key == data.client_officers_position)
	                    {
	                        option.attr('selected', 'selected');
	                    }
	                    $("#form"+$count_officer+" #position").append(option);
	                });
	                
	                //$(".nationality").prop("disabled",false);
	            }
	            else{
	                alert(data.msg);
	            }

				
			}				
		});
			
		$("input.number").bind({
			keydown: function(e) {
				if (e.shiftKey === true ) {
					if (e.which == 9) {
						return true;
					}
					return false;
				}
				if (e.which > 57) {
					return false;
				}
				if (e.which==32) {
					return false;
				}
				return true;
			}
		});
	});
	//Search Officer
	$("#gid_add_officer").live('change',function(){
		var officer_frm = $(this);
		var officer_id;
		//console.log(officer_frm.val());
		//console.log($(this).parent().parent('form').find('input[name="name[]"]').val("aaa"));

		if(officer_frm.parent().parent('form').find('select[name="position[]"]').val() == "7")
		{
			officer_id = officer_frm.parent().parent('form').find('.select_alternate_of').val();
		}
		else
		{
			officer_id = null;
		}

		$("#loadingmessage").show();
		$.ajax({
			type: "POST",
			url: "masterclient/get_officer",
			data: {"officer_id":officer_id, "identification_register_no":officer_frm.val(), "position": officer_frm.parent().parent('form').find('select[name="position[]"]').val(), "company_code": company_code}, // <--- THIS IS THE CHANGE
			dataType: "json",
			success: function(data){
				$("#loadingmessage").hide();
				//console.log(response['name']);
				if(data.status == 1)
				{
					var response = data.info;
					if(response['field_type'] == "company")
					{
						//console.log(data['field_type'] == "company");
						officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
						officer_frm.parent().parent('form').find('input[name="officer_id"]').val(response['id']);
						officer_frm.parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);
						if(response['company_name'] != undefined)
						{
							//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
							officer_frm.parent().parent('form').find("DIV#form_name").html( "" );
							//officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['company_name']);
						}
					}
					else
					{
						officer_frm.parent().parent('form').find('input[name="name[]"]').val(response['name']);
						officer_frm.parent().parent('form').find('input[name="officer_id"]').val(response['id']);
						officer_frm.parent().parent('form').find('input[name="officer_field_type"]').val(response['field_type']);
						if(response['name'] != undefined)
						{
							//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', true);
							officer_frm.parent().parent('form').find("DIV#form_name").html( "" );
						}
						
					}
					officer_frm.parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
				}
				else if(data.status == 2)
				{
					toastr.error(data.message, data.title);
					officer_frm.parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
				}
				else if(data.status == 3)
				{
					officer_frm.parent().parent('form').find('input[name="name[]"]').val("");
					//officer_frm.parent().parent('form').find('input[name="name[]"]').attr('readOnly', false);
					officer_frm.parent().parent('form').find('input[name="officer_id"]').val("");
					officer_frm.parent().parent('form').find('input[name="officer_field_type"]').val("");
					officer_frm.parent().parent('form').find('a#add_office_person_link').removeAttr('hidden');
				}
				else if(data.status == 4)
				{
					toastr.error(data.message, data.title);
					officer_frm.parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
					officer_frm.parent().parent('form').find('input[name="name[]"]').val("");
				}
				else if(data.status == 5)
				{
					toastr.error(data.message, data.title);
					officer_frm.parent().parent('form').find('input[name="name[]"]').val("");
					//officer_frm.parent().parent('form').find('a#add_office_person_link').attr('hidden',"true");
				}
				
				


				
				
				//$b = JSON.parse(data);
				/*$("#add_officer_nama").val($b['name']);
				$("#position").val($b['position']);
				$("#position").val($b['position']);
				$("#date_of_appointment").val($b['date_of_appointment']);
				$("#date_of_cessation").val($b['date_of_cessation']);
				$("#add_officer_postal_code").val($b['zipcode']);
				$("#add_officer_street").val($b['street']);
				$("#add_officer_buildingname").val($b['buildingname']);
				$("#unit_no1").val($b['unit_no1']);
				$("#unit_no2").val($b['unit_no2']);
				$("#alternate_address").val($b['alternate_address']);
				$("#nationality").val($b['nationality']);
				$("#date_of_birth").val($b['date_of_birth']);*/
				// console.log($b['id']);
				// console.log($b['id']);
			}				
		});
		// console.log($(this).val());
	});
/*
$(document).ready(function(){
	$("#add_office_person_link").click(function(){
		console.log("in");
	});
});*/