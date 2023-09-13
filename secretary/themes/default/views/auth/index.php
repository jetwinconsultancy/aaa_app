<div class="header_between_all_section">
    <section class="panel">
        <div class="panel-body">
            <div class="modal-wrapper">
                <div class="modal-text">
                    <div class="tabs">
                        
                        <ul class="nav nav-tabs nav-justify" id="myTab">
                            <li class="active users_check_state" id="li-userList" data-information="userList">
                                <a href="#w2-userList" data-toggle="tab" class="text-center">
                                    <span class="badge hidden-xs">1</span>
                                    Users
                                </a>
                            </li>
                            <li class="users_check_state" id="li-rulesList" data-information="rulesList" style="display: none">
                                <a href="#w2-rulesList" data-toggle="tab" class="text-center ">
                                    <span class="badge hidden-xs">2</span>
                                    Rules
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="w2-userList" class="tab-pane active">
                                <header class="panel-heading" style="padding: 28px;">
                                    <div class="panel-actions" style="height:80px">            
                                        <a class="create_users amber" href="#" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create User" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:85px;"></i> Create User</a>
                                    </div>
                                    <h2></h2>
                                </header>
                        
                                <div class="panel-body">
                                    <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                                        echo form_open_multipart("auth/users", $attrib);
                                        
                                    ?>
                                        <div class="col-md-12">
                                            <div class="col-md-5">
                                                    <input type="text" class="form-control input-sm" id="w2-username" name="search" placeholder="Search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>">
                                            </div>
                                            <div class="col-md-4 search_group_button">
                                                <input type="submit" class="btn btn-primary" value="Search"/>
                                                <a href="auth/users" class="btn btn-primary">Show All User</a>
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
                                                                <th style="width:50px;"></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                $i = 1;
                                                                
                                                                if(isset($user))
                                                                {
                                                                    foreach($user as $p)
                                                                    {
                                                                        echo '<tr>';
                                                                        echo '<td style="word-break:break-all;">';
                                                                        echo "<a href='".site_url('auth/profile/'.$p->id.'')."' data-name='".$p->first_name."' style='cursor:pointer;'>".ucwords(substr($p->first_name,0,23))."<span id='h".$i."' style='display:none;cursor:pointer'>".substr($p->first_name,23,strlen($p->first_name))."</span></a>";
                                                                        
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
                                                                        <button type="button" class="btn btn-primary delete_user" onclick="delete_user(this)">Delete</button>
                                                                        
                                                                        </td>';
                                                                        echo '</tr>';

                                                                        $i++;
                                                                    }
                                                                }
                                                            ?>

                                                        </tbody>
                                                    </table>
                                                    <br/>
                                                </div>                                              
                                            </div>
                                    <?= form_close();?>
                                </div>
                            </div>
                            <div id="w2-rulesList" class="tab-pane">
                                <div>
                                    <table class="table table-bordered table-striped table-condensed mb-none" id="rules_info_table">
                                        <thead>
                                            <div class="tr">
                                                <div class="th" id="department" style="text-align: center;width:20%">Department</div>
                                                <div class="th" id="type" style="text-align: center;width:20%">Type</div>
                                                <div class="th" style="text-align: center;width: 50%" id="description">Description</div>
                                                <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="rules_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Rules Info" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Rules</span></a>
                                            </div>
                                            
                                        </thead>
                                        <div class="tbody" id="body_rules_info">
                                            

                                        </div>
                                        
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
    var department = [];

    $("#header_our_firm").removeClass("header_disabled");
    $("#header_manage_user").addClass("header_disabled");
    $("#header_access_right").removeClass("header_disabled");
    $("#header_client_access").removeClass("header_disabled");
    $("#header_user_profile").removeClass("header_disabled");
    $("#header_email_history").removeClass("header_disabled");
    $("#header_setting").removeClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");

    $(document).ready(function () {
        $('.create_users').click(function (e) {
            e.preventDefault();
            if(no_of_user == total_no_of_user)
            {
                bootbox.alert("Cannot exceed the total number of users.", function() {
                });
            }
            else
            {
                window.location.href = '<?= base_url();?>auth/create_user';
            }
            
        });
    });

    toastr.options = {
      "positionClass": "toast-bottom-right"
    }

    $.ajax({
        type: "GET",
        url: "masterclient/get_department",
        async: false,
        dataType: "json",
        success: function(data){
            department = data['result'];
        }
    });

    function delete_user(element)
    {
        var tr = jQuery(element).parent().parent();
        var user_id = tr.find('input[name="user_id"]').val();
        bootbox.confirm("Are you confirm delete this user?", function (result) {
            if (result) 
            {
                if(user_id != undefined)
                {
                    $('#loadingmessage').show();
                    $.ajax({ //Upload common input
                        url: "auth/delete_user",
                        type: "POST",
                        data: {"user_id": user_id},
                        dataType: 'json',
                        success: function (response) {
                            $('#loadingmessage').hide();
                            toastr.success(response.message, response.title);
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
    });

    $("[name='user_switch']").change(function() {
        var checkbox = $(this);
        var checked = this.checked;
        var user_id = $(this).parent().parent().parent().find("#user_id").val();
        var confirmation_sentence;

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
                    checkbox.prop('checked', false);
                }
                else
                {
                    checkbox.prop('checked', true);
                }
            }
        });
    });
</script>
<script src="themes/default/assets/js/rules_info.js?v=40eee4fc8d1b59e4584b0d39edfa2082" /></script>