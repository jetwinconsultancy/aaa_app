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
    toolbar: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | mybutton",
    autoresize_min_height: 300,
  	autoresize_max_height: 500,
    setup: function (editor) {
        //editor.on('init', function () {
             //this.setContent('The init function knows on which editor its called - this is for ' + editor.id);
        //});
        editor.on('init', function () {
             //this.setContent('The init function knows on which editor its called - this is for ' + editor.id);
            if(access_right_document_module == "read" || access_right_master_module == "read")
            {
                tinymce.activeEditor.setMode('readonly');
            }
        });
        editor.addButton('mybutton', {
            text: 'Loop',
            type: 'menubutton',
            fixedWidth: true,
            icon: 'icon dashicons-menu',
            menu: [
                    {
                        text: 'Includes',
                        onclick: function() {
                            editor.focus();
                            jQuery(tinymce.activeEditor.selection.getNode()).parent("tr").addClass("loop");
                            //console.log(jQuery(tinymce.activeEditor.selection.getNode()).parent("tr").addClass("loop"));

                        }
                    },
                    {
                        text: 'Excludes',
                        onclick: function() {
                            editor.focus();
                            jQuery(tinymce.activeEditor.selection.getNode()).parent("tr").removeClass("loop");
                            //tinyMCE.activeEditor.dom.removeClass(tinyMCE.activeEditor.dom.select('tr'), 'UnorderedList');
                        }
                    }
               ]
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
			   tinymce.get("document_tinymce").execCommand('mceInsertContent', false, '<span class="myclass mceNonEditable">{{'+$(this).text()+'}}</span>');
			   return false
			});


	      },
	      onhide: function (api) {
	        console.log('Hide panel', api.element());
	      }
	    });


    } 
});/*.then(function(editors) {
   //what to do after editors init

/*console.log($("div.mce-sidebar-panel.mce-container.mce-stack-layout-item.mce-first"));
console.log($("iframe#document_tinymce_ifr").find('.dis-tags li'));
	// used to set the drag/drop of the tags
$('.dis-tags li').click(function () {
	console.log("ininin");
   //$("iframe#document_tinymce_ifr").insertContent($(this).text());
   tinymce.get("document_tinymce").execCommand('mceInsertContent', false, '<span>'+$(this).text()+'</span>');
   return false
});

   jQuery("#keywords").find("li").each(function(){jQuery(this).draggable(
	{ 
	    helper:'clone', 
	    start: function(event, ui){
	        jQuery(this).fadeTo('fast', 0.5);}, 
	        stop: function(event, ui) { 
	            jQuery(this).fadeTo(0, 1); 
	            } 
	        });
	    });

	
	//console.log($("div#mceu_43"));
	$("iframe#document_tinymce_ifr").droppable({ 
	    drop: function(event, ui) { 
	        alert('dropped'); //NOW FIRES!
	        //Dynamically add content
	        tinymce.get("document_tinymce").execCommand('mceInsertContent', false, '<span>'+ui.draggable.html()+'</span>');
	    } 
	});*/
//});


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
    url: "documents/get_billing_info_service",
    data: {},
    dataType: "json",
    async:false,
    success: function(data){
        console.log(data);
        $("#document_toggle #triggered_by").find("option:eq(0)").html("Select Service");
        if(data.tp == 1){
            $.each(data['result'], function(key, val) {
                var option = $('<option />');
                option.attr('value', key).text(val);
                if(document_master)
                {
                	if(document_master[0]["triggered_by"] != null && key == document_master[0]["triggered_by"])
	                {
	                    option.attr('selected', 'selected');
	                }
                }
                
                $("#document_toggle #triggered_by").append(option);
            });
/*            $("#form"+i+" #service option").filter(function()
            {
                return $.inArray($(this).val(),data.selected_query)>-1;
            }).attr("disabled","disabled");  

            $('select[name="service['+i+']"] option').filter(function()
            {
                return $(this).val() === data.selected_service;
            }).attr("disabled", false);*/
        }
        else{
            alert(data.msg);
        }  
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

$(document).on('submit', '#document_toggle', function (e) {

	e.preventDefault();
    var form = $('#document_toggle');
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
        url: "documents/add_document_toggle",
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
                if (response.error["document_name"] != "")
                {
                    var errorsDocumentName = '<span class="help-block">*' + response.error["document_name"] + '</span>';
                    $( '#form_document_name' ).html( errorsDocumentName );

                }
                else
                {
                    var errorsDocumentName = '';
                    $( '#form_document_name' ).html( errorsDocumentName );
                }

                if (response.error["triggered_by"] != "")
                {
                    var errorsTriggeredBy = '<span class="help-block">*' + response.error["triggered_by"] + '</span>';
                    $( '#form_triggered_by' ).html( errorsTriggeredBy );

                }
                else
                {
                    var errorsTriggeredBy = '';
                    $( '#form_triggered_by' ).html( errorsTriggeredBy );
                }

                if (response.error["document_content"] != "")
                {
                    var errorsDocumentContent = '<span class="help-block">*' + response.error["document_content"] + '</span>';
                    $( '#form_document_content' ).html( errorsDocumentContent );

                }
                else
                {
                    var errorsDocumentContent = '';
                    $( '#form_document_content' ).html( errorsDocumentContent );
                }
            }
        }
    });
});