<div class="header_between_all_section">
    <section class="panel">
        <?php echo $breadcrumbs;?>
        <div class="panel-body">
            <section class="panel" id="wPerson">
                                            <form action="<?=base_url("teamworks/addcompany")?>" id="upload" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                    <header class="panel-heading">
                                        <h2 class="panel-title">Create Company</h2>
                                    </header>
                                    <div class="panel-body">

                                           <input type="hidden" name="token" value="4319793a345422bd9f7aa52e802250ae">
                                                <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">
                                                    <input type="hidden" class="form-control input-sm" name="close_page" value="">
                                                    <input type="hidden" class="form-control input-sm" name="field_type" value="individual">
                                                    <tbody>
                                                    <tr>
                                                        <th>Registration No.</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_registration_Num" name="company_registration_Num" style="text-transform:uppercase" required="" value="">
                                                            <div id="form_company_registration_Num"></div>
                                                        </td>               
                                                    </tr>
                                                    <tr>
                                                        <th>Company Name</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_name" name="company_name" style="text-transform:uppercase" required="" value="">
                                                            <div id="form_company_name"></div>
                                                        </td>               
                                                    </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                    <footer class="panel-footer">
                                            <div class="row">
                                                <div class="col-md-12 text-right">
                                                    <input type="submit" class="btn btn-primary" value="Save" id="save">
                                                <a href="<?=base_url("teamworks")?>" class="btn btn-default">Cancel</a>
                                                </div>
                                            </div>
                                        </footer>
                                        </form>
                                </section>
        </div>
    </section>
</div>


<script type="text/javascript">
    $.ajax({
        "url":"<?=base_url("teamworks/getCompanies")?>",
        "method":"GET",
        "dataType":"JSON",
        "data":{},
        success:function(data){
            var table = $('#datatable-teamwork')
            var html = []

            for (var i = 0; i < data.data.length; i++) { 
                var v = data.data[i];
                html.push(`<tr>
                        <td style="text-align: center;width: 100px">`+parseFloat(i+1)+`</td>
                        <td style="text-align: center;">`+v.company_registration_Num+`</td>
                        <td style="text-align: center;"><a href="#">`+v.company_name+`</a></td>
                        <td style="text-align: center;width: 100px">
                            <button class="btn btn-primary">Delete</button>
                        </td>
                    </tr>`)
            }

            table.find('tbody').html(html.join(""))

            $('#datatable-teamwork').DataTable({
                dom: 'lrt'
            });
        },
        error: function(data) {
            console.log(data)
        },
    });
</script>