<div class="header_between_all_section">
    <section class="panel">
        <?php echo $breadcrumbs;?>
        <div class="panel-body">
            <section class="panel" id="wPerson">
                                            <form action="<?=base_url("teamworks/addcompanykeydateaction")?>" id="upload" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                    <header class="panel-heading">
                                        <h2 class="panel-title">Add Company Key Date</h2>
                                    </header>
                                    <div class="panel-body">

                                           <input type="hidden" name="token" value="4319793a345422bd9f7aa52e802250ae">
                                                <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">
                                                    <input type="hidden" class="form-control input-sm" name="close_page" value="">
                                                    <input type="hidden" class="form-control input-sm" name="field_type" value="individual">
                                                    <tbody>
                                                    <tr>
                                                        <th>Registration No</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="registration_no" name="registration_no" style="text-transform:uppercase" required="" value="">
                                                            <div id="form_registration_no"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Type of Event</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="type_of_event" name="type_of_event" style="text-transform:uppercase" required="" value="">
                                                            <div id="form_type_of_event"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Year</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="year" name="year" style="text-transform:uppercase" required="" value="">
                                                            <div id="form_year"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Actual FYE</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="actual_fye" name="actual_fye" style="text-transform:uppercase" required="" value="">
                                                            <div id="form_actual_fye"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>FYE Range From</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="fye_range_from" name="fye_range_from" style="text-transform:uppercase" value="">
                                                            <div id="form_fye_range_from"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>FYE Range To</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="fye_range_to" name="fye_range_to" style="text-transform:uppercase" value="">
                                                            <div id="form_fye_range_to"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Held Date 1</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="held_date_1" name="held_date_1" style="text-transform:uppercase" value="">
                                                            <div id="form_held_date_1"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Filling Date 1</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="filling_date_1" name="filling_date_1" style="text-transform:uppercase" value="">
                                                            <div id="form_filling_date_1"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Held Date 2</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="held_date_2" name="held_date_2" style="text-transform:uppercase" value="">
                                                            <div id="form_held_date_2"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Filling Date 2</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="filling_date_2" name="filling_date_2" style="text-transform:uppercase" value="">
                                                            <div id="form_filling_date_2"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Held Date 3</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="held_date_3" name="held_date_3" style="text-transform:uppercase" value="">
                                                            <div id="form_held_date_3"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Filling Date 3</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="filling_date_3" name="filling_date_3" style="text-transform:uppercase" value="">
                                                            <div id="form_filling_date_3"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Held Date 4</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="held_date_4" name="held_date_4" style="text-transform:uppercase" value="">
                                                            <div id="form_held_date_4"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Filling Date 4</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="filling_date_4" name="filling_date_4" style="text-transform:uppercase" value="">
                                                            <div id="form_filling_date_4"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Held Date 5</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="held_date_5" name="held_date_5" style="text-transform:uppercase" value="">
                                                            <div id="form_held_date_5"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Filling Date 5</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="filling_date_5" name="filling_date_5" style="text-transform:uppercase" value="">
                                                            <div id="form_filling_date_5"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Held Date 6</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="held_date_6" name="held_date_6" style="text-transform:uppercase" value="">
                                                            <div id="form_held_date_6"></div>
                                                        </td>               
                                                    </tr>
                                                    
                                                    <tr>
                                                        <th>Filling Date 6</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="filling_date_6" name="filling_date_6" style="text-transform:uppercase" value="">
                                                            <div id="form_filling_date_6"></div>
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