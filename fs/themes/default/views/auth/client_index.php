<div class="header_between_all_section">
<section class="panel" style="margin-bottom: 35px">
    <header class="panel-heading">
        <div class="panel-actions" style="height:80px">
                                    
            <!-- a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a-->
            <!--a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a-->
            <a class="create_access_client_users amber" href="#" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Access Client User" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create User</a>
        </div>
        <h2></h2>
            
    </header>
    <div class="panel-body">
        <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
            echo form_open_multipart("auth/client", $attrib);
            
        ?>
                <div class="col-md-12">
                    <!-- <div class="col-md-2">
                        <select class="form-control input-sm" name="type">
                            <option value="all" <?=$type == "all"?'selected':'';?>>All</option>                                 
                            <option value="individual" <?=$type == "individual"?'selected':'';?>>Individual</option>
                            <option value="company" <?=$type == "company"?'selected':'';?>>Company</option>
                            
                        </select>
                    </div> -->
                    <div class="col-md-5">
                            <input type="text" class="form-control input-sm" id="w2-username" name="search" placeholder="Search" value="<?=$_POST['search']?$_POST['search']:'';?>">
                    </div>
                    <div class="col-md-4 search_group_button">
                        <input type="submit" class="btn btn-primary" value="Search"/>
                        <a href="auth/client" class="btn btn-primary">Show All User</a>
                    </div>

                        <div class="col-md-12" id="div_firm" style="margin-top:10px;">
                            <table class="table table-bordered table-striped table-condensed mb-none" id="datatable-default" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th style="width:200px;"><?php echo lang('first_name'); ?></th>
                                        <th style="width:200px;"><?php echo lang('last_name'); ?></th>
                                        <th style="width:200px;"><?php echo lang('email_address'); ?></th>
                                        <th style="width:40px;"><?php echo lang('group'); ?></th>
                                        <th style="width:40px;"><?php echo lang('status'); ?></th>
                                        <th style="width:50px;"><!-- <?php echo lang('actions'); ?> --></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 1;
                                        foreach($user as $p)
                                        {
                                            //echo '';
                                            echo '<tr>';
                                            echo '<td style="word-break:break-all;">';
                                            echo "<a href='".site_url('auth/profile/'.$p->id.'/access_client')."' data-name='".$p->first_name."' style='cursor:pointer;'>".ucwords(substr($p->first_name,0,23))."<span id='h".$i."' style='display:none;cursor:pointer'>".substr($p->first_name,23,strlen($p->first_name))."</span></a>";
                                            
                                            if(strlen($p->first_name) > 23)
                                            {
                                                echo '<a class="tonggle_readmore_users" data-id="h'.$i.'">...</a>';
                                            }
                                            echo '</td>';
                                            echo '<td style="word-break:break-all;">';
                                            echo "".ucwords(substr($p->last_name,0,23))."<span id='v".$i."' style='display:none;cursor:pointer'>".substr($p->last_name,23,strlen($p->last_name))."</span>";
                                            
                                            if(strlen($p->last_name) > 23)
                                            {
                                                echo '<a class="tonggle_readmore_users" data-id="v'.$i.'">...</a>';
                                            }
                                            echo '</td>';
                                            echo '<td style="word-break:break-all;">';
                                            echo "".(substr($p->email,0,23))."<span id='c".$i."' style='display:none;cursor:pointer'>".substr($p->email,23,strlen($p->email))."</span>";
                                            
                                            if(strlen($p->email) > 23)
                                            {
                                                echo '<a class="tonggle_readmore_users" data-id="c'.$i.'">...</a>';
                                            }
                                            echo '</td>';
                                            echo '<td>'.$p->description.'</td>';
                                            echo '<td><label class="switch"><input name="user_switch" class="user_switch" type="checkbox" '.(($p->active)?"checked":"").'><span class="slider round"></span></label><input type="hidden" id="user_id" name="user_id" value="'.$p->id.'"></td>';
                                            echo '<td>
                                            <button type="button" class="btn btn-primary delete_client_user" onclick="delete_client_user(this)">Delete</button>
                                            
                                            </td>';
                                            /*<button type="button" class="btn btn-primary suspend_user" onclick="suspend_user(this)">SUSPEND</button>*/
                                            /*<a href="'.site_url('auth/profile/'.$p->id.'') . '" class="btn btn-sm btn-info tip" title="'. lang("edit_user").'" type="button">
                                            <i class="fa fa-edit"></i> EDIT</a><button class="btn btn-sm btn-warning" type="button"><i class="fa fa-trash-o"></i> DELETE</button>
                                            <button class="btn btn-sm btn-warning" type="button"><i class="fa fa-ban"></i> SUSPEND</button>*/

                                            echo '</tr>';

                                            $i++;
                                        }
                                    ?>

                                </tbody>
                            </table>
                            <br/>
                        </div>                                              
                </div>
        <?= form_close();?>
    </div>


    <!-- end: page -->
    
</section>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<style>
    #div_firm .datatables-header {
        display:none;
    }
</style>

<script type="text/javascript">
    var no_of_user = <?php echo json_encode($no_of_user) ?>;
    var total_no_of_user = <?php echo json_encode($total_no_of_user) ?>;

    $("#header_our_firm").removeClass("header_disabled");
    $("#header_manage_user").removeClass("header_disabled");
    $("#header_access_right").removeClass("header_disabled");
    $("#header_client_access").addClass("header_disabled");
    $("#header_user_profile").removeClass("header_disabled");
    $("#header_setting").removeClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");

    $(document).ready(function () {
        $('.create_access_client_users').click(function (e) {
            e.preventDefault();
            //console.log(no_of_user);
            //console.log(total_no_of_user);
            // if(no_of_user == total_no_of_user)
            // {
            //     bootbox.alert("Cannot exceed the total number of users.", function() {
            //       //Example.show("Hello world callback");
            //     });
            // }
            // else
            // {
                window.location.href = '<?= base_url();?>auth/create_access_client_user';
            //}
            
        });
    });

    toastr.options = {
      "positionClass": "toast-bottom-right"
    }

    function delete_client_user(element)
    {
        var tr = jQuery(element).parent().parent();
        var user_id = tr.find('input[name="user_id"]').val();
        //console.log(user_id);
        /*console.log(tr);
        console.log(each_customer_id);*/
        bootbox.confirm("Are you confirm delete this user?", function (result) {
            if (result) 
            {
                if(user_id != undefined)
                {
                    $('#loadingmessage').show();
                    $.ajax({ //Upload common input
                        url: "auth/delete_client_user",
                        type: "POST",
                        data: {"user_id": user_id},
                        dataType: 'json',
                        success: function (response) {
                            //console.log(response);
                            $('#loadingmessage').hide();

                            toastr.success(response.message, response.title);
                            //activity_data = response.activity_data;

                  
                        }
                    });
                }
                tr.remove();
            }
        });
    }

    $(document).on('click','.tonggle_readmore_users',function (event){
        event.preventDefault();
        $id = $(this).data('id');
        

        if($("#"+$id).css('display') == 'none')
        {
            $("#"+$id).show();
        }
        else
        {
            $("#"+$id).hide();
        }
        //console.log($("#"+$id).show());
    });

    $("[name='user_switch']").change(function() {
        /*console.log(this.checked);
        console.log($(this).parent().parent().parent().find("#firm_id").val());*/
        var checkbox = $(this);
        var checked = this.checked;
        var user_id = $(this).parent().parent().parent().find("#user_id").val();
        var confirmation_sentence;
        //var checkbox_checked = $('input[name="firm_switch"]:checked');
        if(checked)
        {
            confirmation_sentence = "Are you confirm activate this user?";
        }
        else
        {
            confirmation_sentence = "Are you confirm deactivate this user?";
            
        }

        bootbox.confirm(confirmation_sentence, function (result) {
            if (result) 
            {
                
                

                $.ajax({
                    type: "POST",
                    url: "auth/check_status",
                    data: {"checked":checked, "user_id": user_id}, // <--- THIS IS THE CHANGE
                    dataType: "json",
                    success: function(response){
                        if(response.Status == 1)
                        {
                            toastr.success(response.message, response.title);
                        }
                    }
                });
            }
            else
            {
                if(checked)
                {
                    //console.log(checkbox);
                    checkbox.prop('checked', false);
                }
                else
                {
                    checkbox.prop('checked', true);
                }
                //return false;
            }
        });
    });
</script>