<div class="header_between_all_section">
<section class="panel">
	<div class="panel-body">
		<div class="col-md-12">
            <div id="modalLG" class="modal-block modal-block-lg" style="max-width: 100% ;margin: 0px auto;">
                <section class="panel" style="margin-bottom: 0px;">
                    <div class="panel-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <div class="tabs">
                                    <ul class="nav nav-tabs nav-justify" id="myTab">
                                        

                                        <li class="dropdown active">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                <span class="badge hidden-xs">1</span>
                                                GST
                                                <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="active"><a href="#jurisdiction_list" role="tab" data-toggle="tab">Jurisdiction List</a></li>
                                                <li><a href="#category_list" role="tab" data-toggle="tab">Category List</a></li>
                                            </ul>
                                        </li> 
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                <span class="badge hidden-xs">2</span>
                                                Payment
                                                <b class="caret"></b>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#payment_type_list" role="tab" data-toggle="tab">Payment Type</a></li>
                                                <!-- <li><a href="#bootstab" role="tab" data-toggle="tab">Bootstrap</a></li>
                                                <li><a href="#htmltab" role="tab" data-toggle="tab">HTML</a></li> -->
                                            </ul>
                                        </li> 
                                        <li class="settings_check_stat" id="li-system_settings" data-information="system_settings">
                                            <a href="#w2-system_settings" data-toggle="tab" class="text-center">
                                                <span class="badge hidden-xs">3</span>
                                                System Settings
                                            </a>
                                        </li>
                                        <!-- <li class="active settings_check_stat" id="li-jurisdiction" data-information="jurisdiction" >
                                            <a href="#w2-jurisdiction" data-toggle="tab" class="text-center ">
                                                <span class="badge hidden-xs">2</span>
                                                GST Settings
                                            </a>
                                        </li> -->
                                        <!-- <li class="our_firm_check_stat" id="li-ourService" data-information="ourService" >
                                            <a href="#w2-ourService" data-toggle="tab" class="text-center ">
                                                <span class="badge hidden-xs">3</span>
                                                Our Service
                                            </a>
                                        </li> -->
                                    </ul>
                                    <div class="tab-content">
                                        <div id="w2-system_settings" class="tab-pane">
                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <label>Expiration Date :</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span style="display: inline;">
                                                            <input class="setting_input" type="text" style="width: 200px;margin-right: 32px" value="<?= $user_info[0]->date_of_expiry ?>" disabled>
                                                            <button type="button" class="btn btn-primary">RENEW</button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <label>No of Users :</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span style="display: inline;">
                                                            
                                                            <input class="setting_input" type="text" style="width: 70px;margin-right: 10px" value="<?= $user_info[0]->no_of_user ?>" disabled>
                                                            <span>/</span>
                                                            <input class="setting_input" type="text" style="width: 70px;margin-left: 10px;margin-right: 60px" value="<?= $user_info[0]->total_no_of_user ?>" disabled>
                                                            <button type="button" class="btn btn-primary">RENEW</button>
                                                        </span>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <label>No of Clients :</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span style="display: inline;">
                                                            
                                                            <input class="setting_input" type="text" style="width: 70px;margin-right: 10px" value="<?= $user_info[0]->no_of_client ?>" disabled>
                                                            <span>/</span>
                                                            <input class="setting_input" type="text" style="width: 70px;margin-left: 10px;margin-right: 60px" value="<?= $user_info[0]->total_no_of_client ?>" disabled>
                                                            <button type="button" class="btn btn-primary">RENEW</button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                
                                                    <div class="col-sm-2">
                                                        <label>Storage :</label>
                                                    </div>
                                                    <div class="col-sm-8" style="float:left;margin-bottom:5px;">
                                                        <div class="input-group" style="width: 200px;text-align: center; " >
                                                            <span style="display: inline; font-size: 20px;">
                                                                &infin;
                                                                
                                                            </span>
                                                            
                                                        </div>

                                                    </div>
                                                
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-2">
                                                    <label>No of Firms :</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <div class="input-group">
                                                        <span style="display: inline;">
                                                            <input class="setting_input" type="text" style="width: 200px;margin-right: 32px" value="<?= $user_info[0]->no_of_firm ?>" disabled>
                                                            <button type="button" class="btn btn-primary">ADD</button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="jurisdiction_list" class="tab-pane active">
                                            <table class="table table-bordered table-striped table-condensed mb-none" id="jurisdiction_info_table">
                                                <div class="tr">
                                                    <div class="th" id="code" style="text-align: center;width:150px">Code</div>
                                                    <div class="th" id="jurisdiction" style="text-align: center;width:170px">Jurisdiction</div>
                                                    <a href="javascript: void(0);" class="th" rowspan=2 style="color: #D9A200;width:170px; outline: none !important;text-decoration: none;"><span id="jurisdiction_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Create Jurisdiction" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Jurisdiction</span></a>
                                                </div>
                                                <div class="tbody" id="body_jurisdiction_info">
                                                </div>
                                            </table>
                                        </div>
                                        <div id="category_list" class="tab-pane">
                                            <div class="col-sm-12">
                                                <a class="create_category amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="float: right; height:45px;font-weight:bold; text-decoration: none; z-index: 999; position: relative;" data-original-title="Create Category" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Category</a>
                                            </div>
                                            <table class="table table-bordered table-striped table-condensed mb-none gst_setting_table" id="datatable-gst-setting" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Category</th>
                                                        <th>Jurisdiction</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Rate (%)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_category_list">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="payment_type_list" class="tab-pane">
                                            <div class="col-sm-12">
                                                <a class="create_payment_type amber" href="javascript:void(0)" data-toggle="tooltip" data-trigger="hover" style="float: right; height:45px;font-weight:bold; text-decoration: none; z-index: 999; position: relative;" data-original-title="Create Payment Voucher Type" ><i class="fa fa-plus-circle amber" style="font-size:16px;height:45px;"></i> Create Payment Voucher Type</a>
                                            </div>
                                            <table class="table table-bordered table-striped table-condensed mb-none payment_type_table" id="datatable-payment-type-setting" style="width:100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 30px;">No</th>
                                                        <th>Payment Voucher Type</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody_payment_type_list">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
		</div>
	</div>
</section>
<div id="modal_category" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
    <div class="modal-dialog modal-lg" id="origin_juris">
        <div class="modal-content" id="jurisdiction_content">
            <header class="panel-heading">
                <h2 class="panel-title">Category</h2>
            </header>
            <div id="add_category_form"></div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="saveCategory" id="saveCategory">Save</button>
                <input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_category" value="Cancel">
            </div>
        </div>
    </div>
</div>

<div id="modal_payment_voucher_type" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height: 100% !important;">
    <div class="modal-dialog modal-lg" id="origin_juris">
        <div class="modal-content" id="jurisdiction_content">
            <header class="panel-heading">
                <h2 class="panel-title">Payment Voucher Type</h2>
            </header>
            <div id="add_payment_voucher_form">
                <form class="form_payment_voucher_type" id="form_payment_voucher_type" autocomplete="off">
                    <input type="hidden" id="payment_voucher_type_id" name="payment_voucher_type_id" value="">
                    <div class="form-group" style="margin-top: 20px">
                        <label class="col-xs-3">Payment Voucher Type: </label>
                        <div class="col-xs-6 div_autocomplete payment_voucher_type_div">
                            <input type="text" style="text-transform:uppercase;" class="form-control" id="payment_voucher_type" name="payment_voucher_type" value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary savePaymentVoucherType" name="savePaymentVoucherType" id="savePaymentVoucherType">Save</button>
                <input type="button" class="btn btn-default " data-dismiss="modal" name="cancel_payment_voucher_type" value="Cancel">
            </div>
        </div>
    </div>
</div>

<div class="hidden_form_category" style="display: none;">
    <form class="origin_form_category" autocomplete="off">
        <div class="panel-body">
            <input type="hidden" name="category_id" id="category_id" value=""/>
            <div class="form-group">
                <label class="col-sm-2" for="w2-DS1">Category:</label>
                <div class="col-sm-3 category_div">
                    <input type="text" name="category" class="form-control" value="" id="category" style="width: 300px;"/>
                </div>
            </div>
            <table class="table table-bordered table-striped table-condensed mb-none edit_gst_setting_table" style="width:100%;">
                <thead>
                    <tr>
                        <th style="width: 150px;">Jurisdiction</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th style="width: 80px;">Rate (%)</th>
                        <th style="width: 100px;"><a href="javascript: void(0);" style="color: #D9A200;outline: none !important;text-decoration: none;"><span id="rate_Add" data-toggle="tooltip" data-trigger="hover" data-original-title="Add Rate" style="font-size:14px;"><i class="fa fa-plus-circle"></i> Add Rate</span></a></th>
                    </tr>
                </thead>
                <tbody id="body_category_info">
                    <tr class="tr_juris">
                        <td><input type="hidden" name="gst_category_info_id[]" id="gst_category_info_id0" value=""/><div class="juris-input-group"><select class="form-control" id="jurisdiction0" name="jurisdiction[]"><option value='0'>Select Jurisdiction</option></select></div></td>
                        <td><div class="date-input-group"><div class="input-group"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="start_date form-control" id="start_date0" name="start_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></div></td>
                        <td><div class="input-group"><span class="input-group-addon"><i class="far fa-calendar-alt"></i></span><input type="text" class="end_date form-control" id="end_date0" name="end_date[]" data-date-format="dd/mm/yyyy" data-plugin-datepicker value=""></div></td>
                        <td><div class="input-group"><input type="text" name="rate[]" class="form-control" value="" id="rate0"/></div></td>
                        <td><input class="btn btn-primary delete_rate" type="button" id="delete_rate" value="Delete"/></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </form>
</div>

<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
</div>
<script>
    var jurisdiction_info = <?php echo json_encode(isset($jurisdiction_info) ? $jurisdiction_info : '');?>;
    var category_info = <?php echo json_encode(isset($category_info) ? $category_info : '');?>;
    var dropdown_jurisdiction_info, number_juris = 1, deleteCategoryTr = new Array();
    var payment_voucher_type = <?php echo json_encode(isset($payment_voucher_type) ? $payment_voucher_type : '');?>;

    $("#header_our_firm").removeClass("header_disabled");
    $("#header_manage_user").removeClass("header_disabled");
    $("#header_access_right").removeClass("header_disabled");
    $("#header_user_profile").removeClass("header_disabled");
    $("#header_setting").addClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");
</script>
<script src="themes/default/assets/js/admin_setting.js?v=30eee4fc8d1b59e4584b0d39edfa2082" /></script>