$("#document_type").live('change',function(){
	$("#document_others_name").val("");
	if($(this).val() == "Others")
	{
		$(".div_document_others_name").show();
	}
	else
	{
		$(".div_document_others_name").hide();
	}
});

$(document).on('change','[type=file]',function(){
    var filename = "";
    for(var i = 0; i < this.files.length; i++)
    {
    if(i == 0)
    {
        filename = this.files[i].name;
    }
    else
    {
        filename = filename + ", " + this.files[i].name;
    }
    }
    $(this).parent().find(".file_name").html(filename);
    $(this).parent().find(".hidden_attachment").val("");
});

$(document).on('click',"#update_company_document",function(e){
    var formData = new FormData($('form#company_document_form')[0]);
    $('#loadingmessage').show();
    $.ajax({
        type: 'POST', //$form.serialize()
        url: "masterclient/save_company_document",
        data: formData,
        dataType: 'json',
        // Tell jQuery not to process data or worry about content-type
        // You *must* include these options!
        // + '&user_name_text=' + $(".user_name option:selected").text()
        cache: false,
        contentType: false,
        processData: false,
        success: function(response){
            $('#loadingmessage').hide();
            var list_of_company_document = response["list_of_company_document"];
            refreshCompanyDocumentTable(list_of_company_document);

            $(".document_type_id").val("");
            $(".document_type").val("Constitution");
            $("#document_others_name").val("");
            $(".div_document_others_name").hide();
            $(".hidden_attachment").val("");
            $(".file_name").text("");
            $(".attachment").val("");
        }
    });
});

$(document).on('click','.editCompanyDocument',function(){
    $(".document_type_id").val($(this).data("document_id"));
    $(".document_type").val($(this).data("document_type"));
    //$(this).data('document_type');
    if($(this).data("document_type") == "Others")
    {
        $(".div_document_others_name").show();
        $("#document_others_name").val($(this).data("document_others_name"));
    }
    else
    {
        $(".div_document_others_name").hide();
        $(".document_others_name").val("");
    }

    $(".hidden_attachment").val(JSON.stringify($(this).data("array_filename")));
    $(".file_name").text($(this).data("string_filename"));
});

function deleteCompanyDocument(id)
{
    bootbox.confirm({
        message: "Do you want to delete this company document?",
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
                $.ajax({
                    type: 'POST', //$form.serialize()
                    url: "masterclient/delete_company_document",
                    data: {"company_document_id":id , "company_code":$("#company_document_form").find(".company_doc_company_code").val()},
                    dataType: 'json',
                    success: function(response){
                        $('#loadingmessage').hide();
                        var list_of_company_document = response["list_of_company_document"];
                        refreshCompanyDocumentTable(list_of_company_document);
                    }
                });
            }
        }
    });
}

function refreshCompanyDocumentTable(list_of_company_document)
{
    $('#datatable-company_document').DataTable().destroy();
    $(".company_document_table").remove();

    for(var f = 0; f < list_of_company_document.length; f++)
    {
        var file_result = JSON.parse(list_of_company_document[f]["attachment"]);
        var filename = "", string_filename = "";
        for(var i = 0; i < file_result.length; i++)
        {
            if(i == 0)
            {
                filename = '<a href="'+base_url+'uploads/company_document/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
                string_filename = file_result[i];
            }
            else
            {
                filename = filename + ", " + '<a href="'+base_url+'uploads/company_document/'+file_result[i]+'" target="_blank">'+file_result[i]+'</a>';
                string_filename = string_filename + ", " + file_result[i];
            }
        }
        var a = "";
            a += "<tr class='company_document_table'>";
            a += '<td><span style="height:45px;font-weight:bold;cursor: pointer;" class="amber editCompanyDocument" data-document_id = "'+list_of_company_document[f]['id']+'" data-document_type = "'+list_of_company_document[f]['document_type']+'" data-document_others_name = "'+list_of_company_document[f]['document_others_name']+'" data-string_filename = "'+string_filename+'" data-array_filename = '+list_of_company_document[f]["attachment"]+'>'+((list_of_company_document[f]['document_type'] != "Others")?list_of_company_document[f]['document_type']:list_of_company_document[f]['document_others_name'])+'</span></td>';
            a += '<td>'+filename+'</td>';
            a += '<td><button type="button" class="btn btn-primary" onclick="deleteCompanyDocument('+list_of_company_document[f]['id']+')">Delete</button></td>';
            a += "</tr>";

        $("#company_document_body").append(a);
    }
    $('#datatable-company_document').DataTable();
}