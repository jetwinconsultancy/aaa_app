tinymce.init({
    selector: ".tinymce",
    //height: 450,
    content_style: "body {width: 210mm !important;}",
    mode : "exact",
    elements : "reminder_document_tinymce",
    branding: false, // To disable "Powered by TinyMCE"
    plugins: [
        "advlist autolink lists link image charmap print preview anchor hr noneditable autoresize",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    autoresize_min_height: 300,
  	autoresize_max_height: 500,
    setup: function (editor) {
        //editor.on('init', function () {
             //this.setContent('The init function knows on which editor its called - this is for ' + editor.id);
        //});
        editor.on('init', function () {
             //this.setContent('The init function knows on which editor its called - this is for ' + editor.id);
            if(access_right_document_module == "read" || access_right_reminder_module == "read")
            {
                tinymce.activeEditor.setMode('readonly');
            }
        });

        editor.on('keyup', function (e) {
            tinyMceChange(editor);
        });
        editor.on('change', function(e) {
            tinyMceChange(editor);
        });
        editor.addSidebar('mysidebar', {
	      tooltip: 'My sidebar',
	      icon: 'settings',
	      onrender: function (api) {
	        //console.log('Render panel', api.element());
	      },
	      onshow: function (api) {
	        //console.log('Show panel', api.element());
	        $.ajax({
				type: "GET",
				url: "documents/get_toggle",
				//data: {"company_code": company_code}, // <--- THIS IS THE CHANGE
				dataType: "json",
				async: false,
				success: function(response)
				{
					//console.log(response);
					var toggle = "";
					for(var t = 0; t < response.length; t++)
					{
						toggle += '<li style="margin-bottom: 10px;" data-toggle="tooltip" title="' +response[t]["tooltip"]+ '"><span>' +response[t]["toggle"]+ '</span></li>';
					}
					api.element().innerHTML = '<div style="position:relative;width: 200px;"><div style="position: absolute;top:0;z-index:2;"><input type="search" class="form-control" id="myInput" onkeyup="searchTheTags()" placeholder="Search Toggles" title="Search" style="width:233px;height:30px;font-style: italic;background-color:white;border: 1px solid #c5c5c5;"><i class="searchIcon" id="input_img"></i></div></div><div style="overflow-y:scroll;height:100%"><ul class="dis-tags" id="myTags" style="cursor:pointer;margin-top: 40px;position: relative;">'+toggle+'</ul></div>';
				}
			});

	        var num_principal_activities = 1;
	        var content, str;
	        $('.dis-tags li').click(function () {
	        	str = tinymce.get('reminder_document_tinymce').getContent();
	        	if($(this).text() == "Principal activities")
	        	{
	        		if (str.indexOf("Principal activities"+num_principal_activities+"") >= 0)
	        		{
	        			if(num_principal_activities <= 1)
	        			{
	        				num_principal_activities = num_principal_activities + 1;
	        				content = "Principal activities"+num_principal_activities+"";

	        				if(str.indexOf(content) >= 0)
	        				{
	        					content = "";
	        				}
	        			}
	        			else
	        			{
	        				content = "";
	        			}
	        			
	        		}
	        		else
	        		{
	        			content = "Principal activities1";
	        		}
	        	}
	        	else
	        	{
	        		content = $(this).text();
	        	}
	        	if(content != "")
	        	{
	        		tinymce.get("reminder_document_tinymce").execCommand('mceInsertContent', false, '<span class="myclass mceNonEditable">{{'+content+'}}</span>');
	        	}
			    
			    return false
			});


	      },
	      onhide: function (api) {
	        console.log('Hide panel', api.element());
	      }
	    });
    } 
});

function searchTheTags() {
    var input, filter, ul, li, a, i;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("myTags");
    li = ul.getElementsByTagName("li");
    for (i = 0; i < li.length; i++) {
        a = li[i];
        if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
            li[i].style.display = "";
        } else {
            li[i].style.display = "none";

        }
    }
}

$.ajax({
    type: "POST",
    url: "documents/get_reminder_tag",
    data: {},
    dataType: "json",
    async:false,
    success: function(data){
        console.log(data);
        $("#document_reminder #reminder_tag").find("option:eq(0)").html("Select Reminder Tag");
        if(data.tp == 1){
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(document_reminder)
                {
                	if(document_reminder[0]["reminder_tag_id"] != null && key == document_reminder[0]["reminder_tag_id"])
	                {
	                    option.attr('selected', 'selected');
	                }
                }
                
                $("#document_reminder #reminder_tag").append(option);
            });
        }
        else{
            alert(data.msg);
        }  
    }               
});

var state_document_active_checkbox = true;
$("[name='hidden_document_active_checkbox']").val(1);

if(document_reminder)
{
    if(document_reminder[0]['active'] == 0)
    {
        state_document_active_checkbox = false;
        $("[name='hidden_document_active_checkbox']").val(document_reminder[0]['active']);
    }
    else
    {
        state_document_active_checkbox = true;
        $("[name='hidden_document_active_checkbox']").val(document_reminder[0]['active']);
    }
}

$("[name='document_active_checkbox']").bootstrapSwitch({
    state: state_document_active_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'ON',
    offText: 'OFF',
    // Text of the center handle of the switch
    labelText: '&nbsp',
    // Width of the left and right sides in pixels
    handleWidth: '75px',
    // Width of the center handle in pixels
    labelWidth: 'auto',
    baseClass: 'bootstrap-switch',
    wrapperClass: 'wrapper'


});

// Triggered on switch state change.
$("[name='document_active_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    //console.log(this); // DOM element
    //console.log(event); // jQuery event
    console.log(state); // true | false

    if(state == true)
    {
        //$("#gst_value").val("");
        
        $("[name='hidden_document_active_checkbox']").val(1);
        // /$("[name='gst_value']").attr("value", "");

    }
    else
    {
        //$('#gst_value').attr("value", "");
        //$("#gst_date").val("");

        $("[name='hidden_document_active_checkbox']").val(0);
    }
});


$("#document_name").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_document_name").html( "" );
});

$("#triggered_by").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_triggered_by").html( "" );
});

function tinyMceChange(ed) {
    console.debug('Editor contents was modified. Contents: ' + ed.getContent());
    $("DIV#form_document_content").html( "" );
}

toastr.options = {
  "positionClass": "toast-bottom-right"
}

$("#reminder_tag").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_reminder_tag").html( "" );
});

$("#reminder_name").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_reminder_name").html( "" );
});

$("#before_year_end").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_before_year_end").html( "" );
});

$("#before_due_date").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_before_due_date").html( "" );
});

/*$("#send_to").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_send_to").html( "" );
});*/

$(".start_on").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_start_on").html( "" );
});

function tinyMceChange(ed) {
    console.debug('Editor contents was modified. Contents: ' + ed.getContent());
    $("DIV#form_reminder_document_content").html( "" );
}

$(document).on('submit', '#document_reminder', function (e) {

	e.preventDefault();
	//console.log("in");
	/*var before_due_date = $("#before_due_date").val();
	var before_year_end = $("#before_year_end").val();*/
	 /*if(typeof num1 == 'number' && 5 > num1.lenght){
		document.write(num1 + " is a number <br/>");
	 }else{
		document.write(num1 + " is not a number <br/>");
	 }*/
	/*if(before_year_end != null && typeof before_year_end == 'number' && 5 > before_year_end.lenght && before_due_date != null &&  typeof before_due_date == 'number' && 5 > before_due_date.lenght)
	{*/
	    var form = $('#document_reminder');
	    $('#loadingmessage').show();
	    $.ajax({ //Upload common input
	        url: "documents/add_document_reminder",
	        type: "POST",
	        data: form.serialize(),
	        dataType: 'json',
	        success: function (response) {
	            //console.log(response);
	            if (response.Status === 1) {

	                $('#loadingmessage').hide();
		        	toastr.success(response.message, response.title);
		        	window.location.href = base_url + "documents";
	            }
	            else
	            {
	            	$('#loadingmessage').hide();
	            	if (response.error["reminder_tag"] != "")
	                {
	                    var errorsReminderTag = '<span class="help-block">*' + response.error["reminder_tag"] + '</span>';
	                    $( '#form_reminder_tag' ).html( errorsReminderTag );

	                }
	                else
	                {
	                    var errorsReminderTag = '';
	                    $( '#form_reminder_tag' ).html( errorsReminderTag );
	                }

	                if (response.error["reminder_name"] != "")
	                {
	                    var errorsReminderName = '<span class="help-block">*' + response.error["reminder_name"] + '</span>';
	                    $( '#form_reminder_name' ).html( errorsReminderName );

	                }
	                else
	                {
	                    var errorsReminderName = '';
	                    $( '#form_reminder_name' ).html( errorsReminderName );
	                }

	                if (response.error["before_year_end"] != "")
	                {
	                    var errorsBeforeYearEnd = '<span class="help-block">*' + response.error["before_year_end"] + '</span>';
	                    $( '#form_before_year_end' ).html( errorsBeforeYearEnd );

	                }
	                else
	                {
	                    var errorsBeforeYearEnd = '';
	                    $( '#form_before_year_end' ).html( errorsBeforeYearEnd );
	                }

	                if (response.error["before_due_date"] != "")
	                {
	                    var errorsBeforeDueDate = '<span class="help-block">*' + response.error["before_due_date"] + '</span>';
	                    $( '#form_before_due_date' ).html( errorsBeforeDueDate );

	                }
	                else
	                {
	                    var errorsBeforeDueDate = '';
	                    $( '#form_before_due_date' ).html( errorsBeforeDueDate );
	                }

	                /*if (response.error["send_to"] != "")
	                {
	                    var errorsSendTo = '<span class="help-block">*' + response.error["send_to"] + '</span>';
	                    $( '#form_send_to' ).html( errorsSendTo );

	                }
	                else
	                {
	                    var errorsSendTo = '';
	                    $( '#form_send_to' ).html( errorsSendTo );
	                }*/

	                if (response.error["start_on"] != "")
	                {
	                    var errorStartOn = '<span class="help-block">*' + response.error["start_on"] + '</span>';
	                    $( '#form_start_on' ).html( errorStartOn );

	                }
	                else
	                {
	                    var errorStartOn = '';
	                    $( '#form_start_on' ).html( errorStartOn );
	                }

	                if (response.error["reminder_document_content"] != "")
	                {
	                    var errorReminderDocumentContent = '<span class="help-block">*' + response.error["reminder_document_content"] + '</span>';
	                    $( '#form_reminder_document_content' ).html( errorReminderDocumentContent );

	                }
	                else
	                {
	                    var errorReminderDocumentContent = '';
	                    $( '#form_reminder_document_content' ).html( errorReminderDocumentContent );
	                }
	            }
	        }
	    });
	/*}
	else if (typeof before_year_end != 'number' && before_year_end.lenght >= 5)
	{
        var errorsBeforeYearEnd = 'Please 4 numeric character';
        $( '#form_before_year_end' ).html( errorsBeforeYearEnd );
	}
	else if (typeof before_due_date != 'number' && before_due_date.lenght >= 5)
	{
        var errorsBeforeDueDate = 'Please 4 numeric character';
        $( '#form_before_due_date' ).html( errorsBeforeDueDate );
	}*/
});

/*$(document).on('click',"#saveReminderDocument",function(e){
    $("#document_reminder").submit();
});*/