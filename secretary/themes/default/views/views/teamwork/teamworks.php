<div class="header_between_all_section">
    <section class="panel">
        <header class="panel-heading">
            <div class="panel-actions">
                <a class="amber" href="<?=base_url("teamworks/export")?>"><i class="fa fa-users amber" style="font-size:16px;height:45px;"></i> Import to Teamwork</a>
            </div>
            <h2></h2>
        </header>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed mb-none" id="datatable-teamwork" style="width: 100%">
                <thead>
                    <tr>
                        <th style="text-align: center;width: 100px">#</th>
                        <th style="text-align: center;">Registration No.</th>
                        <th style="text-align: center;">Company</th>
                        <th style="text-align: center;width: 100px">Actions</th>
                        <th style="text-align: center;width: 150px">Teamwork Api</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Company</h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">
                    <input type="hidden" class="form-control input-sm" name="close_page" value="">
                    <input type="hidden" class="form-control input-sm" name="field_type" value="individual">
                    <tbody id="content">
                        <tr>
                            <th>Registration No.</th>
                            <td>
                                <input readonly value="" type="text" class="form-control input-xs" id="registration_no" name="registration_no" style="text-transform:uppercase" required="" value="">
                                <div id="form_registration_no"></div>
                            </td>               
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save changes</button>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var companies = []

    function openedit(id){
        $('#myModal').modal('show') 
        var company = companies[id]

        console.log(company)

        var content = [];


        jQuery.each(company, function(index, item) {
            // do something with `item` (or `this` is also `item` if you like)
            content.push(`<tr>
                            <th>`+index+`</th>
                            <td>
                                <input type="text" class="form-control input-xs" id="`+index+`" name="`+index+`" style="text-transform:uppercase" value="`+item+`">
                            </td>               
                        </tr>`)
        });

        $('#content').html(content.join())

        $('#saveBtn').attr('onclick','return saveEdit()');
    }

    function saveEdit(){

        const data = companies[0];
        var pushData = {} 
        jQuery.each(data, function(index, item) {
            pushData[index] = $('input[name="'+index+'"]').val()
        })

        $.ajax({
        "url":"<?=base_url("teamworks/editCompanies")?>",
        "method":"POST",
        "dataType":"JSON",
        "data":pushData,
        success:function(data){
            init()
            $('#myModal').modal('hide')
        },
        error: function(data) {
            console.log(data)
        },
    });
    }

    function deleteCompanies(registration_no){
        $.ajax({
        "url":"<?=base_url("teamworks/deletecompanies")?>",
        "method":"POST",
        "dataType":"JSON",
        "data":{
            registration_no:registration_no
        },
        success:function(data){
            init()
        },
        error: function(data) {
            console.log(data)
        },
    });
    }

    function viewAndImport(registration_no){
        $.ajax({
            "url":"<?=base_url("teamworks/getCompanies")?>",
            "method":"POST",
            "dataType":"JSON",
            "data":{
                registration_no:registration_no
            },
            success:function(data){
                // init()
                console.log(data)
            },
            error: function(data) {
                console.log(data)
            },
        });
    }

    // function importIndividual(registration_no){
    //     $.ajax({
    //         "url":"<?=base_url("teamworks/importindividual")?>",
    //         "method":"POST",
    //         "dataType":"JSON",
    //         "data":{
    //             registration_no:registration_no
    //         },
    //         success:function(data){
    //             // init()
    //             console.log(data)
    //         },
    //         error: function(data) {
    //             console.log(data)
    //         },
    //     });
    // }

    // function importCorporate(registration_no){
    //     $.ajax({
    //         "url":"<?=base_url("teamworks/importcorporate")?>",
    //         "method":"POST",
    //         "dataType":"JSON",
    //         "data":{
    //             registration_no:registration_no
    //         },
    //         success:function(data){
    //             // init()
    //             console.log(data)
    //         },
    //         error: function(data) {
    //             console.log(data)
    //         },
    //     });
    // }

    function init(){
        $.ajax({
            "url":"<?=base_url("teamworks/getCompanies")?>",
            "method":"GET",
            "dataType":"JSON",
            "data":{},
            success:function(data){
                var table = $('#datatable-teamwork')
                var html = []
                companies = data.data;

                console.log(companies)

                for (var i = 0; i < companies.length; i++) { 
                    var v = companies[i];
                    html.push(`<tr> 
                            <td style="text-align: center;width: 100px">`+parseFloat(i+1)+`</td>
                            <td style="text-align: center;">`+v.registration_no+`</td>
                            <td style="text-align: left;">Name:<a href="javascript:void(0)" onclick="return openedit(`+i+`)">`+v.entity_name+`</a></td>
                            <td style="text-align: center;width: 100px">
                                <button class="btn btn-primary" onclick="return deleteCompanies('`+v.registration_no+`')">Delete</button>
                            </td>
                            <td style="text-align: center;width: 100px">
                                <a href="<?=base_url("teamworks/companyexport/")?>${v.registration_no}"><button class="btn btn-primary" type="button">View & Import</button></a>
                            </td>
                        </tr>`)
                }


                if ( $.fn.DataTable.isDataTable('#datatable-teamwork') ) {
                    $('#datatable-teamwork').DataTable().destroy();
                    $('#datatable-teamwork tbody').empty();
                }
                
                table.find('tbody').html(html.join(""))

                var tableID = $('#datatable-teamwork').DataTable({
                });
            },
            error: function(data) {
                console.log(data)
            },
        });
    }

    init()

</script>