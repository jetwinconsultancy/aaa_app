/*$('#pending_document_form').formValidation({
    framework: 'bootstrap',
    icon: {
        
    },
    // This option will not ignore invisible fields which belong to inactive panels
    //excluded: ':disabled',
    //excluded: [':disabled', ':hidden', ':not(:visible)'],
    fields: {
        client_name: {
            row: '.document-input-group',
            validators: {
                notEmpty: {
                    message: 'The Client Name field is required.'
                }
            }
        },
        document_name: {
            row: '.document-input-group',
            validators: {
                notEmpty: {
                    message: 'The Document Name field is required.'
                }
            }
        },
        document_transaction_date: {
            row: '.document-input-group',
            validators: {
                notEmpty: {
                    message: 'The Transaction Date field is required.'
                }
            }
        },
        pending_document_content: {
            row: '.document-input-group',
            validators: {
                notEmpty: {
                    message: 'The Toggle field is required.'
                }
            }
        }
    }
});*/
var getUrl = window.location;
var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1] + "/documents";
//console.log(baseUrl);

var options = {

	url: function(phrase) {
		return "documents/clientSearch";
	},

	getValue: function(element) {
		/*$("client_id").val(element.id);*/
		console.log(element);
		return element.name;
	},

	ajaxSettings: {
		dataType: "json",
		method: "POST",
		data: {
		  dataType: "json"
		}
	},

	preparePostData: function(data) {
		data.phrase = $("#client_name").val();
		return data;
	},

	list: {
		onSelectItemEvent: function() {
			var client_id = $("#client_name").getSelectedItemData().id;
			console.log(client_id);
			$("#client_id").val(client_id).trigger("change");
		}	
	},

	requestDelay: 400
};

$("#client_name").easyAutocomplete(options);

var today_date = formatDateFunc(new Date());

$('.document_transaction_date').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

if(!pending_document)
{
    $('.document_transaction_date').val(today_date);
}

function formatDateFunc(date) {
    //console.log(date);
  var monthNames = [
    "01", "02", "03",
    "04", "05", "06", "07",
    "08", "09", "10",
    "11", "12"
  ];

  var day = date.getDate();
  //console.log(day.length);
  if(day.toString().length==1)  
  {
    day="0"+day;
  }
    
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + '/' + monthNames[monthIndex] + '/' + year;
}

toastr.options = {
  "positionClass": "toast-bottom-right"
}

var state_document_date_checkbox = true;
$("[name='hidden_document_date_checkbox']").val(1);

if(pending_document)
{
    if(pending_document[0]['document_date_checkbox'] == 0)
    {
        state_document_date_checkbox = false;
        $("[name='hidden_document_date_checkbox']").val(pending_document[0]['document_date_checkbox']);
        $(".document_transaction_date").attr("disabled", true);
        $("#document_transaction_date").hide();
        $( '#form_document_transaction_date' ).html("");
    }
    else
    {
        state_document_date_checkbox = true;
        $("[name='hidden_document_date_checkbox']").val(pending_document[0]['document_date_checkbox']);
        $(".document_transaction_date").attr("disabled", false);
        $("#document_transaction_date").show();
        $( '#form_document_transaction_date' ).html("");
    }
}

$("[name='document_date_checkbox']").bootstrapSwitch({
    state: state_document_date_checkbox,
    size: 'normal',
    onColor: 'primary',
    onText: 'UNHIDE',
    offText: 'HIDE',
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
$("[name='document_date_checkbox']").on('switchChange.bootstrapSwitch', function(event, state) {
    //console.log(this); // DOM element
    //console.log(event); // jQuery event
    console.log(state); // true | false

    if(state == true)
    {
        //$("#gst_value").val("");
        
        $("#document_transaction_date").show();
        $(".document_transaction_date").attr("disabled", false);
        $( '#form_document_transaction_date' ).html("");
        $("[name='hidden_document_date_checkbox']").val(1);
        // /$("[name='gst_value']").attr("value", "");

    }
    else
    {
        //$('#gst_value').attr("value", "");
        //$("#gst_date").val("");
        $("#document_transaction_date").hide();
        $(".document_transaction_date").attr("disabled", true);
        $( '#form_document_transaction_date' ).html("");
        $("[name='hidden_document_date_checkbox']").val(0);
    }
});
//height: 240mm !important; border-bottom: 1px solid red;
tinymce.init({
    selector: ".tinymce",
    //height: 450,
    content_style: "body {width: 210mm !important;}",
    mode : "exact",
    elements : "document_tinymce",
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
        editor.on('init', function () {
             //this.setContent('The init function knows on which editor its called - this is for ' + editor.id);
            if(access_right_document_module == "read" || access_right_pending_module == "read" || access_right_all_module == "read")
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
	        console.log('Render panel', api.element());

	      },
	      onshow: function (api) {
	        console.log('Show panel', api.element());
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

	        $('.dis-tags li').click(function () {
			   tinymce.get("pending_document_tinymce").execCommand('mceInsertContent', false, '<span class="myclass mceNonEditable">{{'+$(this).text()+'}}</span>');
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

$("#client_name").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_client_name").html( "" );
});
$("#document_name").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_pending_document_name").html( "" );
});
$(".document_transaction_date").live('change',function(){
	$(this).parent().parent().parent().parent().find("DIV#form_document_transaction_date").html( "" );
});
/*$("textarea#pending_document_tinymce").keyup(function(){
	console.log($(this).parent().parent().parent().parent());
	$(this).parent().parent().parent().parent().find("DIV#form_pending_document_content").html( "" );
});*/
function tinyMceChange(ed) {
    console.debug('Editor contents was modified. Contents: ' + ed.getContent());
    $("DIV#form_pending_document_content").html( "" );
}

$(document).on("submit", '#pending_document_form',function(e){
    e.preventDefault();
    tinyMCE.triggerSave(); 
    var form = $('#pending_document_form');
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
        url: "documents/add_pending_document",
        type: "POST",
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
            $('#loadingmessage').hide();
            if (response.Status === 1) {
				toastr.success(response.message, response.title);
                window.location.href = baseUrl;
            }
            else if (response.Status === 2)
            {
            	
            	toastr.error(response.message, response.title);
            }
            else
            {
            	toastr.error(response.message, response.title);
            	if (response.error["client_name"] != "")
                {
                    var errorsClientName = '<span class="help-block">*' + response.error["client_name"] + '</span>';
                    $( '#form_client_name' ).html( errorsClientName );

                }
                else
                {
                    var errorsClientName = '';
                    $( '#form_client_name' ).html( errorsClientName );
                }

                if (response.error["document_name"] != "")
                {
                    var errorsDocumentName = '<span class="help-block">*' + response.error["document_name"] + '</span>';
                    $( '#form_pending_document_name' ).html( errorsDocumentName );

                }
                else
                {
                    var errorsDocumentName = '';
                    $( '#form_pending_document_name' ).html( errorsDocumentName );
                }

                if (response.error["document_transaction_date"] != "")
                {
                    var errorsDocumentTransactionDate = '<span class="help-block">*' + response.error["document_transaction_date"] + '</span>';
                    $( '#form_document_transaction_date' ).html( errorsDocumentTransactionDate );

                }
                else
                {
                    var errorsDocumentTransactionDate = '';
                    $( '#form_document_transaction_date' ).html( errorsDocumentTransactionDate );
                }

                if (response.error["pending_document_content"] != "")
                {
                    var errorsPendingDocumentContent = '<span class="help-block">*' + response.error["pending_document_content"] + '</span>';
                    $( '#form_pending_document_content' ).html( errorsPendingDocumentContent );

                }
                else
                {
                    var errorsPendingDocumentContent = '';
                    $( '#form_pending_document_content' ).html( errorsPendingDocumentContent );
                }
            }
        }
    });
});

$(document).on('click',"#savePendingDocument",function(e){
    $("#pending_document_form").submit();
});

if(access_right_document_module == "read" || access_right_pending_module == "read" || access_right_all_module == "read")
{
    $('button').attr("disabled", true);
    $('input').attr("disabled", true);
    $(".document_transaction_date").prop('disabled', true);
}

