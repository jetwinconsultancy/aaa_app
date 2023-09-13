var initialPreviewArray = []; 
var initialPreviewConfigArray = [];
var upload_doc_link = "/secretary/documents/uploadDocumentFile";

var today_date = formatDateFunc(new Date());

$('.document_file_received_on').datepicker({ 
    dateFormat:'dd/mm/yyyy',
    autoclose: true,
});

$('.document_file_received_on').val(today_date);
//$(".document_file_received_on").attr("disabled", true);

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
//console.log(pending_document_info['type']);
if(pending_document_info['type'] === "trans")
{
  upload_doc_link = "/secretary/documents/uploadDocumentFile/trans";
}
else
{
  upload_doc_link = "/secretary/documents/uploadDocumentFile";
}

$(document).on("submit", '#pending_document_file_form',function(e){
    e.preventDefault();
    var form = $('#pending_document_file_form');
    $(".document_file_received_on").attr("disabled", false);
    $('#loadingmessage').show();
    $.ajax({ //Upload common input
        url: "documents/insert_pending_document_file",
        type: "POST",
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
            $('#loadingmessage').hide();
            if (response.Status === 1) {
                /*toastr.success(response.message, response.title);*/
                
                $('#multiple_pending_document_file').fileinput('upload');
            }
        }
    });
});

$(document).on('click',"#savePendingDocumentFile",function(e){
    $("#pending_document_file_form").submit();
});

if(pending_document_files != null)
  {
    for (var i = 0; i < pending_document_files.length; i++) {
      
      var url = base_url + "uploads/pending_document_file/";
      var fileArray = pending_document_files[i].split(',');
      //console.log(fileArray[0]);
      initialPreviewArray.push( url + fileArray[1] );
      var file_type = fileArray[1].substring(fileArray[1].lastIndexOf('.'));
      //console.log(file_type);
        if(file_type == ".pdf")
        {
        initialPreviewConfigArray.push({
          type: "pdf",
            caption: fileArray[1],
            url: "/secretary/documents/deleteDocumentFile/" + fileArray[0],
            width: "120px",
            key: i+1
        });
      }
      else
      {
        initialPreviewConfigArray.push({
            caption: fileArray[1],
            url: "/secretary/documents/deleteDocumentFile/" + fileArray[0],
            width: "120px",
            key: i+1
        });
      }
    }
  }

$("#multiple_pending_document_file").fileinput({
      theme: 'fa',
      uploadUrl: upload_doc_link, // you must set a valid URL here else you will get an error
      uploadAsync: false,
      browseClass: "btn btn-primary",
      fileType: "any",
      showCaption: false,
      showUpload: false,
      showRemove: false,
      fileActionSettings: {
                      showRemove: true,
                      showUpload: false,
                      showZoom: true,
                      showDrag: true,
                  },
      previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
      overwriteInitial: false,
      initialPreviewAsData: true,
      initialPreviewDownloadUrl: base_url + 'uploads/pending_document_file/{filename}',
      initialPreview: initialPreviewArray,
    initialPreviewConfig: initialPreviewConfigArray,
    //deleteUrl: "/dot/personprofile/deleteFile",
    /*maxFileSize: 20000048,
    maxImageWidth: 1000,
    maxImageHeight: 1500,
    resizePreference: 'height',
    resizeImage: true,*/
    purifyHtml: true // this by default purifies HTML data for preview
      /*uploadExtraData: { 
        officer_id: $('input[name="officer_id"]').val() 
      }*/
      /*width:auto;height:auto;max-width:100%;max-height:100%;*/

  }).on('filesorted', function(e, params) {
    console.log('File sorted params', params);
}).on('filebatchuploadsuccess', function(event, data, previewId, index) {
  /*if($("#close_page").val() == 1)
  {
    window.close();
  }
  else
  {*/
    window.location.href = base_url + "documents";
    window.opener.location.reload(true);
    toastr.success("Information Updated", "Success");
 // }
  
    //console.log(data);
}).on('fileuploaderror', function(event, data, msg) {
  window.location.href = base_url + "documents";
  window.opener.location.reload(true);
  toastr.success("Information Updated", "Success");
  //toastr.error("Error", "Error");
});