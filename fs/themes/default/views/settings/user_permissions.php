<style>
    .table td:first-child {
        font-weight: bold;
    }

    label {
        margin-right: 10px;
    }

    table .collapse.in {
        display:table-row;
    }

    td input[type="radio"] {
        float: left;
        margin: 0 auto;
        width: 100%;
    }

    
</style>
<div class="header_between_all_section">
<div class="box" style="margin-bottom: 30px;">
    <!-- <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-folder-open"></i><?= lang('group_permissions'); ?></h2>
    </div> -->
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <!-- <p class="introtext"><?= lang("set_permissions"); ?></p> -->

                <!-- <table class="table table-responsive table-hover">
                  <thead>
                        <tr><th>Column</th><th>Column</th><th>Column</th><th>Column</th></tr>
                    </thead>
                    <tbody>
                        <tr class="clickable" data-toggle="collapse" id="row1" data-target=".row1">
                            <td><i class="glyphicon glyphicon-plus"></i></td>
                            <td>data</td>
                            <td>data</td>  
                            <td>data</td>
                        </tr>
                        <tr class="collapse row1">
                            <td>- child row</td>
                            <td>data</td>
                            <td>data</td>  
                            <td>data</td>
                        </tr>
                        <tr class="collapse row1">
                            <td>- child row</td>
                            <td>data</td>
                            <td>data</td>  
                            <td>data</td>
                        </tr>
                        <tr class="clickable" data-toggle="collapse" id="row2" data-target=".row2">
                            <td><i class="glyphicon glyphicon-plus"></i></td>
                            <td>data</td>
                            <td>data</td>  
                            <td>data</td>
                        </tr>
                        <tr class="collapse row2">
                            <td>- child row</td>
                            <td>data 2</td>
                            <td>data 2</td>  
                            <td>data 2</td>
                        </tr>
                        <tr class="collapse row2">
                            <td>- child row</td>
                            <td>data 2</td>
                            <td>data 2</td>  
                            <td>data 2</td>
                        </tr>
                    </tbody>
                </table> -->

                <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'access_right_form');
                        echo form_open("system_settings/save_access_right", $attrib); ?>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="w2-username">Firm: </label>
                            <div class="col-sm-7">
                                <select id="firm" class="form-control firm" style="text-align:right; width: 300px;" name="firm">
                                    <option value="0">Select Firm</option>
                                </select>
<!--                                 <div id="form_company_type"></div> -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="w2-username">User: </label>
                            <div class="col-sm-7">
                                <select id="user" class="form-control user" style="text-align:right; width: 300px;" name="user">
                                    <option value="0">Select User</option>
                                </select>
<!--                                 <div id="form_company_type"></div> -->
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">

                                <thead>
                                <!-- <tr>
                                    <th colspan="5"
                                        class="text-center">Access Right</th>
                                        <?php echo $group->description . ' ( ' . $group->name . ' ) ' . $this->lang->line("group_permissions"); ?> 
                                </tr> -->
                                <tr>
                                    <th rowspan="2" class="text-center" style="vertical-align: middle !important;"><?= lang("module_name"); ?></th>
                                    <th colspan="3" class="text-center"><?= lang("permissions"); ?></th>
                                </tr>
                                <tr>
                                    <th class="text-center">Full</th>
                                    <th class="text-center">Read</th>
                                    <th class="text-center">None</th>
                                    <!-- <th class="text-center"><?= lang("delete"); ?></th> -->
                                    <!-- <th class="text-center"><?= lang("misc"); ?></th> -->
                                </tr>
                                </thead>
                                <tbody>
                                <!-- <tr class="clickable" data-toggle="collapse" id="row1" data-target=".row1">
                                    <td><i class="glyphicon glyphicon-plus"><?= lang("products"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-index" <?php echo $p->{'products-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-add" <?php echo $p->{'products-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-edit" <?php echo $p->{'products-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-delete" <?php echo $p->{'products-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="products-cost" class="checkbox"
                                               name="products-cost" <?php echo $p->{'products-cost'} ? "checked" : ''; ?>><label
                                            for="products-cost" class="padding05"><?= lang('product_cost') ?></label>
                                        <input type="checkbox" value="1" id="products-price" class="checkbox"
                                               name="products-price" <?php echo $p->{'products-price'} ? "checked" : ''; ?>><label
                                            for="products-price" class="padding05"><?= lang('product_price') ?></label>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td>- Sub Product</td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-index" <?php echo $p->{'products-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-add" <?php echo $p->{'products-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-edit" <?php echo $p->{'products-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="products-delete" <?php echo $p->{'products-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="products-cost" class="checkbox"
                                               name="products-cost" <?php echo $p->{'products-cost'} ? "checked" : ''; ?>><label
                                            for="products-cost" class="padding05"><?= lang('product_cost') ?></label>
                                        <input type="checkbox" value="1" id="products-price" class="checkbox"
                                               name="products-price" <?php echo $p->{'products-price'} ? "checked" : ''; ?>><label
                                            for="products-price" class="padding05"><?= lang('product_price') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("sales"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-index" <?php echo $p->{'sales-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add" <?php echo $p->{'sales-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit" <?php echo $p->{'sales-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete" <?php echo $p->{'sales-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="sales-email" class="checkbox"
                                               name="sales-email" <?php echo $p->{'sales-email'} ? "checked" : ''; ?>><label
                                            for="sales-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="sales-pdf" class="checkbox"
                                               name="sales-pdf" <?php echo $p->{'sales-pdf'} ? "checked" : ''; ?>><label
                                            for="sales-pdf" class="padding05"><?= lang('pdf') ?></label>
                                        <?php if (POS) { ?>
                                            <input type="checkbox" value="1" id="pos-index" class="checkbox"
                                                   name="pos-index" <?php echo $p->{'pos-index'} ? "checked" : ''; ?>>
                                            <label for="pos-index" class="padding05"><?= lang('pos') ?></label>
                                        <?php } ?>
                                        <input type="checkbox" value="1" id="sales-payments" class="checkbox"
                                               name="sales-payments" <?php echo $p->{'sales-payments'} ? "checked" : ''; ?>><label
                                            for="sales-payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" id="sales-return_sales" class="checkbox"
                                               name="sales-return_sales" <?php echo $p->{'sales-return_sales'} ? "checked" : ''; ?>><label
                                            for="sales-return_sales"
                                            class="padding05"><?= lang('return_sales') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("deliveries"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-deliveries" <?php echo $p->{'sales-deliveries'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add_delivery" <?php echo $p->{'sales-add_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit_delivery" <?php echo $p->{'sales-edit_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete_delivery" <?php echo $p->{'sales-delete_delivery'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="sales-email" class="checkbox" name="sales-email_delivery" <?php echo $p->{'sales-email_delivery'} ? "checked" : ''; ?>><label for="sales-email_delivery" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="sales-pdf" class="checkbox"
                                               name="sales-pdf_delivery" <?php echo $p->{'sales-pdf_delivery'} ? "checked" : ''; ?>><label
                                            for="sales-pdf_delivery" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= lang("gift_cards"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-gift_cards" <?php echo $p->{'sales-gift_cards'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-add_gift_card" <?php echo $p->{'sales-add_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-edit_gift_card" <?php echo $p->{'sales-edit_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="sales-delete_gift_card" <?php echo $p->{'sales-delete_gift_card'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>

                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("quotes"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-index" <?php echo $p->{'quotes-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-add" <?php echo $p->{'quotes-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-edit" <?php echo $p->{'quotes-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="quotes-delete" <?php echo $p->{'quotes-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="quotes-email" class="checkbox"
                                               name="quotes-email" <?php echo $p->{'quotes-email'} ? "checked" : ''; ?>><label
                                            for="quotes-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="quotes-pdf" class="checkbox"
                                               name="quotes-pdf" <?php echo $p->{'quotes-pdf'} ? "checked" : ''; ?>><label
                                            for="quotes-pdf" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("purchases"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-index" <?php echo $p->{'purchases-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-add" <?php echo $p->{'purchases-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-edit" <?php echo $p->{'purchases-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="purchases-delete" <?php echo $p->{'purchases-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="purchases-email" class="checkbox"
                                               name="purchases-email" <?php echo $p->{'purchases-email'} ? "checked" : ''; ?>><label
                                            for="purchases-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="purchases-pdf" class="checkbox"
                                               name="purchases-pdf" <?php echo $p->{'purchases-pdf'} ? "checked" : ''; ?>><label
                                            for="purchases-pdf" class="padding05"><?= lang('pdf') ?></label>
                                        <input type="checkbox" value="1" id="purchases-payments" class="checkbox"
                                               name="purchases-payments" <?php echo $p->{'purchases-payments'} ? "checked" : ''; ?>><label
                                            for="purchases-payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" id="purchases-expenses" class="checkbox"
                                               name="purchases-expenses" <?php echo $p->{'purchases-expenses'} ? "checked" : ''; ?>><label
                                            for="purchases-expenses" class="padding05"><?= lang('expenses') ?></label>
                                    </td>
                                </tr>

                                <tr>
                                    <td><?= lang("transfers"); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-index" <?php echo $p->{'transfers-index'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-add" <?php echo $p->{'transfers-add'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-edit" <?php echo $p->{'transfers-edit'} ? "checked" : ''; ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="transfers-delete" <?php echo $p->{'transfers-delete'} ? "checked" : ''; ?>>
                                    </td>
                                    <td>
                                        <input type="checkbox" value="1" id="transfers-email" class="checkbox"
                                               name="transfers-email" <?php echo $p->{'transfers-email'} ? "checked" : ''; ?>><label
                                            for="transfers-email" class="padding05"><?= lang('email') ?></label>
                                        <input type="checkbox" value="1" id="transfers-pdf" class="checkbox"
                                               name="transfers-pdf" <?php echo $p->{'transfers-pdf'} ? "checked" : ''; ?>><label
                                            for="transfers-pdf" class="padding05"><?= lang('pdf') ?></label>
                                    </td>
                                </tr> -->

                                <tr>
                                    <td class="clickable" data-toggle="collapse" id="row1" data-target=".row1"><i class="glyphicon glyphicon-chevron-right"></i> Client</td>
                                    <td>
                                        <input type="radio" class="cl-chk radio client_module_full" name="client_module" value = "full" <?php echo ($p->{'client_module'} == "full") ? "checked" : ''; ?>/>
                                        <!-- <input type="checkbox" value="1" class="checkbox cl-chk"
                                               name="customers-index" <?php echo $p->{'customers-index'} ? "checked" : ''; ?>> -->
                                    </td>
                                    <td>
                                        <input type="radio" class="cl-chk radio client_module_read" name="client_module" value = "read" <?php echo ($p->{'client_module'} == "read") ? "checked" : ''; ?>/>
                                        <!-- <input type="checkbox" value="1" class="checkbox cl-chk"
                                               name="customers-add" <?php echo $p->{'customers-add'} ? "checked" : ''; ?>> -->
                                    </td>
                                    <td>
                                        <input type="radio" class="cl-chk radio client_module_none" name="client_module" value = "none" <?php echo ($p->{'client_module'} == "none") ? "checked" : ''; ?>/>
                                        <!-- <input type="checkbox" value="1" class="checkbox cl-chk"
                                               name="customers-edit" <?php echo $p->{'customers-edit'} ? "checked" : ''; ?>> -->
                                    </td>
                                    <!-- <td class="text-center">
                                        <input type="checkbox" value="1" class="checkbox"
                                               name="customers-delete" <?php echo $p->{'customers-delete'} ? "checked" : ''; ?>>
                                    </td> -->
                                    <!-- <td>
                                    </td> -->
                                </tr>
                                
                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Company Info</td>
                                    <td>
                                        <input type="radio" class="radio company_info_module_full" name="company_info_module" value = "full" <?php echo $p->{'company_info_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio company_info_module_read" name="company_info_module" value = "read" <?php echo $p->{'company_info_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio company_info_module_none" name="company_info_module" value = "none" <?php echo $p->{'company_info_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Officer</td>
                                    <td>
                                        <input type="radio" class="radio officer_module_full" name="officer_module" value = "full" <?php echo $p->{'officer_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio officer_module_read" name="officer_module" value = "read" <?php echo $p->{'officer_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio officer_module_none" name="officer_module" value = "none" <?php echo $p->{'officer_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Member</td>
                                    <td>
                                        <input type="radio" class="radio member_module_full" name="member_module" value = "full" <?php echo $p->{'member_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio member_module_read" name="member_module" value = "read" <?php echo $p->{'member_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio member_module_none" name="member_module" value = "none" <?php echo $p->{'member_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Controller</td>
                                    <td>
                                        <input type="radio" class="radio controller_module_full" name="controller_module" value = "full" <?php echo $p->{'controller_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio controller_module_read" name="controller_module" value = "read" <?php echo $p->{'controller_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio controller_module_none" name="controller_module" value = "none" <?php echo $p->{'controller_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Charges</td>
                                    <td>
                                        <input type="radio" class="radio charges_module_full" name="charges_module" value = "full" <?php echo $p->{'charges_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio charges_module_read" name="charges_module" value = "read" <?php echo $p->{'charges_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio charges_module_none" name="charges_module" value = "none" <?php echo $p->{'charges_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Filing</td>
                                    <td>
                                        <input type="radio" class="radio filing_module_full" name="filing_module" value = "full" <?php echo $p->{'filing_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio filing_module_read" name="filing_module" value = "read" <?php echo $p->{'filing_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio filing_module_none" name="filing_module" value = "none" <?php echo $p->{'filing_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1">
                                    <td style="text-indent: 50px">Register</td>
                                    <td>
                                        <input type="radio" class="radio register_module_full" name="register_module" value = "full" <?php echo $p->{'register_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio register_module_read" name="register_module" value = "read" <?php echo $p->{'register_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio register_module_none" name="register_module" value = "none" <?php echo $p->{'register_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row1" style="border-bottom: 3px solid #dddddd;">
                                    <td style="text-indent: 50px">Setup</td>
                                    <td>
                                        <input type="radio" class="radio setup_module_full" name="setup_module" value = "full" <?php echo $p->{'setup_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio setup_module_read" name="setup_module" value = "read" <?php echo $p->{'setup_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio setup_module_none" name="setup_module" value = "none" <?php echo $p->{'setup_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr style="border-bottom: 2px solid #dddddd;">
                                    <td>Person</td>
                                    <td>
                                        <input type="radio" class="radio" name="person_module" value = "full" <?php echo $p->{'person_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="person_module" value = "read" <?php echo $p->{'person_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="person_module" value = "none" <?php echo $p->{'person_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="clickable" data-toggle="collapse" id="row2" data-target=".row2"><i class="glyphicon glyphicon-chevron-right"></i> Document</td>
                                    <td>
                                        <input type="radio" class="cl-chk radio" name="document_module" value = "full" <?php echo $p->{'document_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="cl-chk radio" name="document_module" value = "read" <?php echo $p->{'document_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="cl-chk radio" name="document_module" value = "none" <?php echo $p->{'document_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row2">
                                    <td style="text-indent: 50px">Pending</td>
                                    <td>
                                        <input type="radio" class="radio" name="pending_module" value = "full" <?php echo $p->{'pending_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="pending_module" value = "read" <?php echo $p->{'pending_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="pending_module" value = "none" <?php echo $p->{'pending_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row2">
                                    <td style="text-indent: 50px">All</td>
                                    <td>
                                        <input type="radio" class="radio" name="all_module" value = "full" <?php echo $p->{'all_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="all_module" value = "read" <?php echo $p->{'all_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="all_module" value = "none" <?php echo $p->{'all_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row2">
                                    <td style="text-indent: 50px">Master</td>
                                    <td>
                                        <input type="radio" class="radio" name="master_module" value = "full" <?php echo $p->{'master_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="master_module" value = "read" <?php echo $p->{'master_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="master_module" value = "none" <?php echo $p->{'master_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row2" style="border-bottom: 2px solid #dddddd;">
                                    <td style="text-indent: 50px">Reminder</td>
                                    <td>
                                        <input type="radio" class="radio" name="reminder_module" value = "full" <?php echo $p->{'reminder_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="reminder_module" value = "read" <?php echo $p->{'reminder_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="reminder_module" value = "none" <?php echo $p->{'reminder_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr style="border-bottom: 2px solid #dddddd;">
                                    <td>Report</td>
                                    <td>
                                        <input type="radio" class="radio" name="report_module" value = "full" <?php echo $p->{'report_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="report_module" value = "read" <?php echo $p->{'report_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="report_module" value = "none" <?php echo $p->{'report_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="clickable" data-toggle="collapse" id="row3" data-target=".row3"><i class="glyphicon glyphicon-chevron-right"></i> Billing</td>
                                    <td>
                                        <input type="radio" class="cl-chk radio" name="billing_module" value = "full" <?php echo $p->{'billing_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="cl-chk radio" name="billing_module" value = "read" <?php echo $p->{'billing_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="cl-chk radio" name="billing_module" value = "none" <?php echo $p->{'billing_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row3">
                                    <td style="text-indent: 50px">Unpaid</td>
                                    <td>
                                        <input type="radio" class="radio" name="unpaid_module" value = "full" <?php echo $p->{'unpaid_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="unpaid_module" value = "read" <?php echo $p->{'unpaid_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="unpaid_module" value = "none" <?php echo $p->{'unpaid_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row3">
                                    <td style="text-indent: 50px">Paid</td>
                                    <td>
                                        <input type="radio" class="radio" name="paid_module" value = "full" <?php echo $p->{'paid_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="paid_module" value = "read" <?php echo $p->{'paid_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="paid_module" value = "none" <?php echo $p->{'paid_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row3">
                                    <td style="text-indent: 50px">Receipt</td>
                                    <td>
                                        <input type="radio" class="radio" name="receipt_module" value = "full" <?php echo $p->{'receipt_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="receipt_module" value = "read" <?php echo $p->{'receipt_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="receipt_module" value = "none" <?php echo $p->{'receipt_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                <tr class="collapse row3" style="border-bottom: 2px solid #dddddd;">
                                    <td style="text-indent: 50px">Template</td>
                                    <td>
                                        <input type="radio" class="radio" name="template_module" value = "full" <?php echo $p->{'template_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="template_module" value = "read" <?php echo $p->{'template_module'} ? "checked" : ''; ?>/>
                                    </td>
                                    <td>
                                        <input type="radio" class="radio" name="template_module" value = "none" <?php echo $p->{'template_module'} ? "checked" : ''; ?>/>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        <!-- <div class="table-responsive">
                            <table cellpadding="0" cellspacing="0" border="0"
                                   class="table table-bordered table-hover table-striped" style="margin-bottom: 5px;">

                                <thead>
                                <tr>
                                    <th><?= lang("reports"); ?>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" value="1" class="checkbox" id="product_quantity_alerts"
                                               name="reports-quantity_alerts" <?php echo $p->{'reports-quantity_alerts'} ? "checked" : ''; ?>><label
                                            for="product_quantity_alerts"
                                            class="padding05"><?= lang('product_quantity_alerts') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="Product_expiry_alerts"
                                               name="reports-expiry_alerts" <?php echo $p->{'reports-expiry_alerts'} ? "checked" : ''; ?>><label
                                            for="Product_expiry_alerts"
                                            class="padding05"><?= lang('product_expiry_alerts') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="products"
                                               name="reports-products" <?php echo $p->{'reports-products'} ? "checked" : ''; ?>><label
                                            for="products" class="padding05"><?= lang('products') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="daily_sales"
                                               name="reports-daily_sales" <?php echo $p->{'reports-daily_sales'} ? "checked" : ''; ?>><label
                                            for="daily_sales" class="padding05"><?= lang('daily_sales') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="monthly_sales"
                                               name="reports-monthly_sales" <?php echo $p->{'reports-monthly_sales'} ? "checked" : ''; ?>><label
                                            for="monthly_sales" class="padding05"><?= lang('monthly_sales') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="payments"
                                               name="reports-payments" <?php echo $p->{'reports-payments'} ? "checked" : ''; ?>><label
                                            for="payments" class="padding05"><?= lang('payments') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="purchases"
                                               name="reports-purchases" <?php echo $p->{'reports-purchases'} ? "checked" : ''; ?>><label
                                            for="purchases" class="padding05"><?= lang('purchases') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="customers"
                                               name="reports-customers" <?php echo $p->{'reports-customers'} ? "checked" : ''; ?>><label
                                            for="customers" class="padding05"><?= lang('customers') ?></label>
                                        <input type="checkbox" value="1" class="checkbox" id="suppliers"
                                               name="reports-suppliers" <?php echo $p->{'reports-suppliers'} ? "checked" : ''; ?>><label
                                            for="suppliers" class="padding05"><?= lang('suppliers') ?></label>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </div> -->
                        <!-- <div class="form-actions">
                            <button type="submit" class="btn btn-primary" style="float: right"><?=lang('Save')?></button>
                        </div> -->
                        <div class="col-md-12 text-right" style="margin-top: 10px;">
                            <button type="submit" class="btn btn-primary"><?=lang('Save')?></button>
                            <!-- <a href="http://localhost/secretary/welcome" class="btn btn-default">Cancel</a> -->
                            <a href="<?= base_url();?>welcome/" class="btn btn-default">Cancel</a>
                        </div>
                        <?php echo form_close(); ?>

                    <!-- } else {
                        echo $this->lang->line("group_x_allowed");
                    }
                } else {
                    echo $this->lang->line("group_x_allowed");
                }  -->


            </div>
        </div>
    </div>
</div>
</div>
<div class="loading" id='loadingmessage' style='display:none'>Loading&#8230;</div>
<script type="text/javascript">


    /*$(document).on("click", '.clickable', function ()
    {
        $(this).toggleClass('glyphicon-chevron-right glyphicon-chevron-down')
        $($(this).attr("data-target")).collapse("toggle");
    });*/

    $("#header_our_firm").removeClass("header_disabled");
    $("#header_manage_user").removeClass("header_disabled");
    $("#header_access_right").addClass("header_disabled");
    $("#header_user_profile").removeClass("header_disabled");
    $("#header_setting").removeClass("header_disabled");
    $("#header_dashboard").removeClass("header_disabled");
    $("#header_client").removeClass("header_disabled");
    $("#header_person").removeClass("header_disabled");
    $("#header_document").removeClass("header_disabled");
    $("#header_report").removeClass("header_disabled");
    $("#header_billings").removeClass("header_disabled");

    $('#access_right_form').formValidation({
        framework: 'bootstrap',

        fields: {
            firm: {
                validators: {
                    callback: {
                        message: 'The Firm field is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('firm').val();
                            //console.log(options);
                            return (options != null && options != "0");
                        }
                    }
                }
            },
            user: {
                validators: {
                    callback: {
                        message: 'The user field is required',
                        callback: function(value, validator, $field) {
                            var options = validator.getFieldElements('user').val();
                            //console.log(options);
                            return (options != null && options != "0");
                        }
                    }
                }
            }
        }
    });

    var f1 = new Firm();

    function ajaxCall() {
        this.send = function(data, url, method, success, type) {
            type = type||'json';
            //console.log(data);
            var successRes = function(data) {
                success(data);
            };

            var errorRes = function(e) {
              //console.log(e);
              alert("Error found \nError Code: "+e.status+" \nError Message: "+e.statusText);
            };
            $.ajax({
                url: url,
                type: method,
                data: data,
                success: successRes,
                error: errorRes,
                dataType: type,
                timeout: 60000
            });

        }

    }

    function Firm() {
        var base_url = window.location.origin;  
        var call = new ajaxCall();
        var pathArray = location.href.split( '/' );
        var protocol = pathArray[0];
        var host = pathArray[2];
        var folder = pathArray[3];

        this.getAccessRight = function(user, firm) {

            
            var url = base_url+"/"+folder+"/"+'system_settings/getAccessRight';
            var method = "post";
            var data = {"user_id": user, "firm_id": firm};
            $("#loadingmessage").show();
            call.send(data, url, method, function(data) {
                if(data.tp == 1){
                    var user_firm_info = data['result'];
                    var access_right = user_firm_info["access_right"];
                    //console.log(access_right);

                    
                    if(access_right["client_module"] == "")
                    {
                        

                        $('input[name=client_module]').attr('disabled', true);
                        $(".row1").collapse("toggle");
                        $("#row1").parent().find(".cl-chk").hide();
                        $('.row1').addClass('in');
                        /*$(".row1").show("toggle");
                        $(".row2").show("toggle");
                        $(".row3").show("toggle");*/
                       
                        $("#row1").parent().find(".glyphicon").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
                        $('input[name=client_module][value="full"]').prop("checked",true);
                        if(($("#row1").parent()[0]["innerText"]).replace(/\s/g,'') == "Client")
                        {
                            $("input[name=company_info_module]").attr('disabled', false);
                            $("input[name=officer_module]").attr('disabled', false);
                            $("input[name=member_module]").attr('disabled', false);
                            $("input[name=controller_module]").attr('disabled', false);
                            $("input[name=charges_module]").attr('disabled', false);
                            $("input[name=filing_module]").attr('disabled', false);
                            $("input[name=register_module]").attr('disabled', false);
                            $("input[name=setup_module]").attr('disabled', false);
                        }

                        $("input[name=company_info_module][value='"+access_right["company_info_module"]+"']").prop("checked",true);
                        $("input[name=officer_module][value='"+access_right["officer_module"]+"']").prop("checked",true);
                        $("input[name=member_module][value='"+access_right["member_module"]+"']").prop("checked",true);
                        $("input[name=controller_module][value='"+access_right["controller_module"]+"']").prop("checked",true);
                        $("input[name=charges_module][value='"+access_right["charges_module"]+"']").prop("checked",true);
                        $("input[name=filing_module][value='"+access_right["filing_module"]+"']").prop("checked",true);
                        $("input[name=register_module][value='"+access_right["register_module"]+"']").prop("checked",true);
                        $("input[name=setup_module][value='"+access_right["setup_module"]+"']").prop("checked",true);

                        if(access_right["company_info_module"] == "none")
                        {
                            $(".officer_module_full").attr('disabled',true);
                            $(".member_module_full").attr('disabled',true);
                            $(".controller_module_full").attr('disabled',true);
                            $(".charges_module_full").attr('disabled',true);
                            $(".filing_module_full").attr('disabled',true);
                            $(".register_module_full").attr('disabled',true);
                            $(".setup_module_full").attr('disabled',true);
                        }
                    }
                    else
                    {
                        /*$('input[name=client_module]').attr('disabled', false);
                        $(".row1").collapse("toggle");
                        $("#row1").parent().find(".cl-chk").show();
                        if(document.getElementsByName('client_module').disabled == false)
                        {*/
                            /*$(".row1").hide("toggle");
                            $(".row2").hide("toggle");
                            $(".row3").hide("toggle");*/
                            $('.row1').removeClass('in');
                            $("input[name=company_info_module]").attr('disabled', true);
                            $("input[name=officer_module]").attr('disabled', true);
                            $("input[name=member_module]").attr('disabled', true);
                            $("input[name=controller_module]").attr('disabled', true);
                            $("input[name=charges_module]").attr('disabled', true);
                            $("input[name=filing_module]").attr('disabled', true);
                            $("input[name=register_module]").attr('disabled', true);
                            $("input[name=setup_module]").attr('disabled', true);
                        //}
                    }
                    
                    if(access_right["client_module"] == "full")
                    {
                        $("input[name=client_module][value='full']").prop("checked",true);

                        $("input[name=company_info_module][value='full']").prop("checked",true);
                        $("input[name=officer_module][value='full']").prop("checked",true);
                        $("input[name=member_module][value='full']").prop("checked",true);
                        $("input[name=controller_module][value='full']").prop("checked",true);
                        $("input[name=charges_module][value='full']").prop("checked",true);
                        $("input[name=filing_module][value='full']").prop("checked",true);
                        $("input[name=register_module][value='full']").prop("checked",true);
                        $("input[name=setup_module][value='full']").prop("checked",true);
                    }
                    else if(access_right["client_module"] == "read")
                    {
                        $("input[name=client_module][value='read']").prop("checked",true);

                        $("input[name=company_info_module][value='read']").prop("checked",true);
                        $("input[name=officer_module][value='read']").prop("checked",true);
                        $("input[name=member_module][value='read']").prop("checked",true);
                        $("input[name=controller_module][value='read']").prop("checked",true);
                        $("input[name=charges_module][value='read']").prop("checked",true);
                        $("input[name=filing_module][value='read']").prop("checked",true);
                        $("input[name=register_module][value='read']").prop("checked",true);
                        $("input[name=setup_module][value='read']").prop("checked",true);
                    }
                    else if(access_right["client_module"] == "none")
                    {
                        $("input[name=client_module][value='none']").prop("checked",true);

                        $("input[name=company_info_module][value='none']").prop("checked",true);
                        $("input[name=officer_module][value='none']").prop("checked",true);
                        $("input[name=member_module][value='none']").prop("checked",true);
                        $("input[name=controller_module][value='none']").prop("checked",true);
                        $("input[name=charges_module][value='none']").prop("checked",true);
                        $("input[name=filing_module][value='none']").prop("checked",true);
                        $("input[name=register_module][value='none']").prop("checked",true);
                        $("input[name=setup_module][value='none']").prop("checked",true);
                    }

                    if(access_right["person_module"] == "full")
                    {
                        $("input[name=person_module][value='full']").prop("checked",true);
                    }
                    else if(access_right["person_module"] == "read")
                    {
                        $("input[name=person_module][value='read']").prop("checked",true);
                    }
                    else if(access_right["person_module"] == "none")
                    {
                        $("input[name=person_module][value='none']").prop("checked",true);
                    }

                    if(access_right["document_module"] == "")
                    {
                        /*$(".row1").show("toggle");
                        $(".row2").show("toggle");
                        $(".row3").show("toggle");*/

                        $('input[name=document_module]').attr('disabled', true);
                        $(".row2").collapse("toggle");
                        $("#row2").parent().find(".cl-chk").hide();
                        $('.row2').addClass('in');

                        $("#row2").parent().find(".glyphicon").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
                        $('input[name=document_module][value="full"]').prop("checked",true);
                        if(($("#row2").parent()[0]["innerText"]).replace(/\s/g,'') == "Document")
                        {
                            $("input[name=pending_module]").attr('disabled', false);
                            $("input[name=all_module]").attr('disabled', false);
                            $("input[name=master_module]").attr('disabled', false);
                            $("input[name=reminder_module]").attr('disabled', false);
                        }

                        $("input[name=pending_module][value='"+access_right["pending_module"]+"']").prop("checked",true);
                        $("input[name=all_module][value='"+access_right["all_module"]+"']").prop("checked",true);
                        $("input[name=master_module][value='"+access_right["master_module"]+"']").prop("checked",true);
                        $("input[name=reminder_module][value='"+access_right["reminder_module"]+"']").prop("checked",true);
                    }
                    else
                    {
                        /*$('input[name=document_module]').attr('disabled', false);
                        $(".row2").collapse("toggle");
                        $("#row2").parent().find(".cl-chk").show();
                        if(document.getElementsByName('document_module').disabled == false)
                        {*/
                            /*$(".row1").hide("toggle");
                            $(".row2").hide("toggle");
                            $(".row3").hide("toggle");*/
                            $('.row2').removeClass('in');
                            $("input[name=pending_module]").attr('disabled', true);
                            $("input[name=all_module]").attr('disabled', true);
                            $("input[name=master_module]").attr('disabled', true);
                            $("input[name=reminder_module]").attr('disabled', true);
                        //}
                    }

                    if(access_right["document_module"] == "full")
                    {
                        $("input[name=document_module][value='full']").prop("checked",true);

                        $("input[name=pending_module][value='full']").prop("checked",true);
                        $("input[name=all_module][value='full']").prop("checked",true);
                        $("input[name=master_module][value='full']").prop("checked",true);
                        $("input[name=reminder_module][value='full']").prop("checked",true);
                    }
                    else if(access_right["document_module"] == "read")
                    {
                        $("input[name=document_module][value='read']").prop("checked",true);

                        $("input[name=pending_module][value='read']").prop("checked",true);
                        $("input[name=all_module][value='read']").prop("checked",true);
                        $("input[name=master_module][value='read']").prop("checked",true);
                        $("input[name=reminder_module][value='read']").prop("checked",true);
                    }
                    else if(access_right["document_module"] == "none")
                    {
                        $("input[name=document_module][value='none']").prop("checked",true);

                        $("input[name=pending_module][value='none']").prop("checked",true);
                        $("input[name=all_module][value='none']").prop("checked",true);
                        $("input[name=master_module][value='none']").prop("checked",true);
                        $("input[name=reminder_module][value='none']").prop("checked",true);
                    }

                    if(access_right["report_module"] == "full")
                    {
                        $("input[name=report_module][value='full']").prop("checked",true);
                    }
                    else if(access_right["report_module"] == "read")
                    {
                        $("input[name=report_module][value='read']").prop("checked",true);
                    }
                    else if(access_right["report_module"] == "none")
                    {
                        $("input[name=report_module][value='none']").prop("checked",true);
                    }

                    if(access_right["billing_module"] == "")
                    {
                        /*$(".row1").show("toggle");
                        $(".row2").show("toggle");
                        $(".row3").show("toggle");*/

                        $('input[name=billing_module]').attr('disabled', true);
                        $(".row3").collapse("toggle");
                        $("#row3").parent().find(".cl-chk").hide();
                        $('.row3').addClass('in');

                        $("#row3").parent().find(".glyphicon").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
                        $('input[name=billing_module][value="full"]').prop("checked",true);
                        if(($("#row3").parent()[0]["innerText"]).replace(/\s/g,'') == "Billing")
                        {
                            $("input[name=unpaid_module]").attr('disabled', false);
                            $("input[name=paid_module]").attr('disabled', false);
                            $("input[name=receipt_module]").attr('disabled', false);
                            $("input[name=template_module]").attr('disabled', false);
                        }

                        $("input[name=unpaid_module][value='"+access_right["unpaid_module"]+"']").prop("checked",true);
                        $("input[name=paid_module][value='"+access_right["paid_module"]+"']").prop("checked",true);
                        $("input[name=receipt_module][value='"+access_right["receipt_module"]+"']").prop("checked",true);
                        $("input[name=template_module][value='"+access_right["template_module"]+"']").prop("checked",true);
                    }
                    else
                    {
                        /*$('input[name=billing_module]').attr('disabled', false);
                        $(".row3").collapse("toggle");
                        $("#row3").parent().find(".cl-chk").show();
                        if(document.getElementsByName('billing_module').disabled == false)
                        {*/
                            /*$(".row1").hide("toggle");
                            $(".row2").hide("toggle");
                            $(".row3").hide("toggle");*/
                            $('.row3').removeClass('in');
                            $("input[name=unpaid_module]").attr('disabled', true);
                            $("input[name=paid_module]").attr('disabled', true);
                            $("input[name=receipt_module]").attr('disabled', true);
                            $("input[name=template_module]").attr('disabled', true);
                        //}
                    }

                    if(access_right["billing_module"] == "full")
                    {
                        $("input[name=billing_module][value='full']").prop("checked",true);

                        $("input[name=unpaid_module][value='full']").prop("checked",true);
                        $("input[name=paid_module][value='full']").prop("checked",true);
                        $("input[name=receipt_module][value='full']").prop("checked",true);
                        $("input[name=template_module][value='full']").prop("checked",true);
                    }
                    else if(access_right["billing_module"] == "read")
                    {
                        $("input[name=billing_module][value='read']").prop("checked",true);

                        $("input[name=unpaid_module][value='read']").prop("checked",true);
                        $("input[name=paid_module][value='read']").prop("checked",true);
                        $("input[name=receipt_module][value='read']").prop("checked",true);
                        $("input[name=template_module][value='read']").prop("checked",true);
                    }
                    else if(access_right["billing_module"] == "none")
                    {
                        $("input[name=billing_module][value='none']").prop("checked",true);

                        $("input[name=unpaid_module][value='none']").prop("checked",true);
                        $("input[name=paid_module][value='none']").prop("checked",true);
                        $("input[name=receipt_module][value='none']").prop("checked",true);
                        $("input[name=template_module][value='none']").prop("checked",true);
                    }

                    $("#loadingmessage").hide();
                }
                else{
                    alert(data.msg);
                }
            }); 
        };

        this.getUser = function(firm) {

            
            var url = base_url+"/"+folder+"/"+'system_settings/getUser';
            var method = "post";
            var data = {"firm_id": firm};
            $("#loadingmessage").show();
            $('.user').find("option:eq(0)").html("Please wait..");
            call.send(data, url, method, function(data) {
                $('.user').find("option:eq(0)").html("Select User");
                if(data.tp == 1){
                    /*if(data['result'].length == 0)
                    {
                        $(".director_signature_2").attr("disabled", "disabled");
                        $(".director_signature_2 option:gt(0)").remove();
                        $('.form_director_signature_2').removeClass("has-error");
                        $('.form_director_signature_2').removeClass("has-success");
                        $('.form_director_signature_2 .help-block').hide();
                    }
                    else
                    {*/
                    if(data['result'].length != 0)
                    {
                        $(".user option:gt(0)").remove(); 

                        $.each(data['result'], function(key, val) {
                            var option = $('<option />');
                            option.attr('value', key).text(val);
                            if(data.user != null && key == data.user)
                            {
                                option.attr('selected', 'selected');
                            }
                            $('.user').append(option);
                            $("#loadingmessage").hide();
                        });
                    }
                    else
                    {
                        $(".user option:gt(0)").remove();
                        $("#loadingmessage").hide();
                    }
                    //}
                    
                    //$(".nationality").prop("disabled",false);
                }
                else{
                    alert(data.msg);
                }
            }); 
        };

        this.getFirm = function() {
            var url = base_url+"/"+folder+"/"+'system_settings/getFirm';
            var method = "get";
            var data = {};
            $("#loadingmessage").show();
            $('.firm').find("option:eq(0)").html("Please wait..");
            call.send(data, url, method, function(data) {
                //console.log(data);
                $('.firm').find("option:eq(0)").html("Select Firm");
                //console.log(data);
                if(data.tp == 1){
                    $.each(data['result'], function(key, val) {
                        var option = $('<option />');
                        option.attr('value', key).text(val);
                        if(data.firm != null && key == data.firm)
                        {
                            option.attr('selected', 'selected');
                        }
                        $('.firm').append(option);
                    });
                    $("#loadingmessage").hide();
                    //$(".nationality").prop("disabled",false);
                }
                else{
                    alert(data.msg);
                }
            }); 
        };

    }

    $(function() {
        var f = new Firm();

        f.getFirm();
    });

    $(".firm").change(function(ev) {
        var firm = $(this).val();
        if(firm != '' && firm != 0){
            f1.getUser(firm);
            $('.row1').removeClass('in');
            $('.row2').removeClass('in');
            $('.row3').removeClass('in');

            $(".cl-chk").show();
            $("#row1").parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $("#row2").parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $("#row3").parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $('input[name=client_module]').attr('disabled', false);
            $('input[name=document_module]').attr('disabled', false);
            $('input[name=billing_module]').attr('disabled', false);
            $("input[type='radio']").prop("checked",false);

            /*$(".director_signature_2").removeAttr("disabled");

            $('.form_director_signature_2').addClass("has-error");
            $('.form_director_signature_2').removeClass("has-success");
            $('.form_director_signature_2 .help-block').show();*/
        }
        //else{
            /*$(".director_signature_2").attr("disabled", "disabled");
            $(".director_signature_2 option:gt(0)").remove();
            $('.form_director_signature_2').removeClass("has-error");
            $('.form_director_signature_2').removeClass("has-success");
            $('.form_director_signature_2 .help-block').hide();*/
        //}
    });

    $(".user").change(function(ev) {
        var user = $(this).val();
        var firm = $("#firm").val();
        if(user != '' && user != 0){
            f1.getAccessRight(user,firm);
            /*$(".row1").hide("toggle");
            $(".row2").hide("toggle");
            $(".row3").hide("toggle");*/
            $('tr').on('shown.bs.collapse', function(){
                $(this).prev('tr').find(".glyphicon-chevron-right").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
            }).on('hidden.bs.collapse', function(){
                $(this).prev('tr').find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            });
            $(".cl-chk").show();
            $("#row1").parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $("#row2").parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $("#row3").parent().find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $('input[name=client_module]').attr('disabled', false);
            $('input[name=document_module]').attr('disabled', false);
            $('input[name=billing_module]').attr('disabled', false);
            $("input[type='radio']").prop("checked",false);
        }
    });


    $('input[name=client_module]').click(function() {
        if($('input[name=client_module]:checked').val() == "full")
        {
            $("input[name=company_info_module][value='full']").prop("checked",true);
            $("input[name=officer_module][value='full']").prop("checked",true);
            $("input[name=member_module][value='full']").prop("checked",true);
            $("input[name=controller_module][value='full']").prop("checked",true);
            $("input[name=charges_module][value='full']").prop("checked",true);
            $("input[name=filing_module][value='full']").prop("checked",true);
            $("input[name=register_module][value='full']").prop("checked",true);
            $("input[name=setup_module][value='full']").prop("checked",true);
        }
        else if($('input[name=client_module]:checked').val() == "read")
        {
            $("input[name=company_info_module][value='read']").prop("checked",true);
            $("input[name=officer_module][value='read']").prop("checked",true);
            $("input[name=member_module][value='read']").prop("checked",true);
            $("input[name=controller_module][value='read']").prop("checked",true);
            $("input[name=charges_module][value='read']").prop("checked",true);
            $("input[name=filing_module][value='read']").prop("checked",true);
            $("input[name=register_module][value='read']").prop("checked",true);
            $("input[name=setup_module][value='read']").prop("checked",true);
        }
        else if($('input[name=client_module]:checked').val() == "none")
        {
            $("input[name=company_info_module][value='none']").prop("checked",true);
            $("input[name=officer_module][value='none']").prop("checked",true);
            $("input[name=member_module][value='none']").prop("checked",true);
            $("input[name=controller_module][value='none']").prop("checked",true);
            $("input[name=charges_module][value='none']").prop("checked",true);
            $("input[name=filing_module][value='none']").prop("checked",true);
            $("input[name=register_module][value='none']").prop("checked",true);
            $("input[name=setup_module][value='none']").prop("checked",true);

        }
    });

    $('input[name=document_module]').click(function() {
        if($('input[name=document_module]:checked').val() == "full")
        {
            $("input[name=pending_module][value='full']").prop("checked",true);
            $("input[name=all_module][value='full']").prop("checked",true);
            $("input[name=master_module][value='full']").prop("checked",true);
            $("input[name=reminder_module][value='full']").prop("checked",true);
        }
        else if($('input[name=document_module]:checked').val() == "read")
        {
            $("input[name=pending_module][value='read']").prop("checked",true);
            $("input[name=all_module][value='read']").prop("checked",true);
            $("input[name=master_module][value='read']").prop("checked",true);
            $("input[name=reminder_module][value='read']").prop("checked",true);
        }
        else if($('input[name=document_module]:checked').val() == "none")
        {
            $("input[name=pending_module][value='none']").prop("checked",true);
            $("input[name=all_module][value='none']").prop("checked",true);
            $("input[name=master_module][value='none']").prop("checked",true);
            $("input[name=reminder_module][value='none']").prop("checked",true);
        }
    });

    $('input[name=billing_module]').click(function() {
        if($('input[name=billing_module]:checked').val() == "full")
        {
            $("input[name=unpaid_module][value='full']").prop("checked",true);
            $("input[name=paid_module][value='full']").prop("checked",true);
            $("input[name=receipt_module][value='full']").prop("checked",true);
            $("input[name=template_module][value='full']").prop("checked",true);
        }
        else if($('input[name=billing_module]:checked').val() == "read")
        {
            $("input[name=unpaid_module][value='read']").prop("checked",true);
            $("input[name=paid_module][value='read']").prop("checked",true);
            $("input[name=receipt_module][value='read']").prop("checked",true);
            $("input[name=template_module][value='read']").prop("checked",true);
        }
        else if($('input[name=billing_module]:checked').val() == "none")
        {
            $("input[name=unpaid_module][value='none']").prop("checked",true);
            $("input[name=paid_module][value='none']").prop("checked",true);
            $("input[name=receipt_module][value='none']").prop("checked",true);
            $("input[name=template_module][value='none']").prop("checked",true);
        }
    });

    $(".company_info_module_none").click(function() {
        //console.log($(".officer_module_full"));
        $(".officer_module_full").attr('disabled',true);
        $(".member_module_full").attr('disabled',true);
        $(".controller_module_full").attr('disabled',true);
        $(".charges_module_full").attr('disabled',true);
        $(".filing_module_full").attr('disabled',true);
        $(".register_module_full").attr('disabled',true);
        $(".setup_module_full").attr('disabled',true);

        $("input[name=company_info_module][value='full']").prop("checked",false);
        $("input[name=officer_module][value='full']").prop("checked",false);
        $("input[name=member_module][value='full']").prop("checked",false);
        $("input[name=controller_module][value='full']").prop("checked",false);
        $("input[name=charges_module][value='full']").prop("checked",false);
        $("input[name=filing_module][value='full']").prop("checked",false);
        $("input[name=register_module][value='full']").prop("checked",false);
        $("input[name=setup_module][value='full']").prop("checked",false);
    });

    $(".company_info_module_full, .company_info_module_read").click(function() {
        //console.log($(".officer_module_full"));
        $(".officer_module_full").attr('disabled',false);
        $(".member_module_full").attr('disabled',false);
        $(".controller_module_full").attr('disabled',false);
        $(".charges_module_full").attr('disabled',false);
        $(".filing_module_full").attr('disabled',false);
        $(".register_module_full").attr('disabled',false);
        $(".setup_module_full").attr('disabled',false);
    });

    $(document).ready(function () {
        $(".clickable").on("click", function () {
          if($(this).find(".glyphicon").hasClass("glyphicon-chevron-down")) {
            $(this).find(".glyphicon").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
            $(this).parent().find(".cl-chk").show();
            /*$(".row1").hide("toggle");
            $(".row2").hide("toggle");
            $(".row3").hide("toggle");*/
            $(this).parent().find('input[name=client_module]').attr('disabled', false);

            if(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Client")
            {
                $("input[name=company_info_module]").attr('disabled', true);
                $("input[name=officer_module]").attr('disabled', true);
                $("input[name=member_module]").attr('disabled', true);
                $("input[name=controller_module]").attr('disabled', true);
                $("input[name=charges_module]").attr('disabled', true);
                $("input[name=filing_module]").attr('disabled', true);
                $("input[name=register_module]").attr('disabled', true);
                $("input[name=setup_module]").attr('disabled', true);


            }

            $(this).parent().find('input[name=document_module]').attr('disabled', false);

            if(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Document")
            {
                $("input[name=pending_module]").attr('disabled', true);
                $("input[name=all_module]").attr('disabled', true);
                $("input[name=master_module]").attr('disabled', true);
                $("input[name=reminder_module]").attr('disabled', true);
            }

            $(this).parent().find('input[name=billing_module]').attr('disabled', false);
            //console.log(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Billing");
            if(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Billing")
            {
                $("input[name=unpaid_module]").attr('disabled', true);
                $("input[name=paid_module]").attr('disabled', true);
                $("input[name=receipt_module]").attr('disabled', true);
                $("input[name=template_module]").attr('disabled', true);
            }

            // $("#cl-row").removeAttribute("colspan");
          } else {
            $(this).find(".glyphicon").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
            $(this).parent().find(".cl-chk").hide();
            /*$(".row1").show("toggle");
            $(".row2").show("toggle");
            $(".row3").show("toggle");*/
            $(this).parent().find('input[name=client_module]').attr('disabled', true);

            if(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Client")
            {
                $("input[name=company_info_module]").attr('disabled', false);
                $("input[name=officer_module]").attr('disabled', false);
                $("input[name=member_module]").attr('disabled', false);
                $("input[name=controller_module]").attr('disabled', false);
                $("input[name=charges_module]").attr('disabled', false);
                $("input[name=filing_module]").attr('disabled', false);
                $("input[name=register_module]").attr('disabled', false);
                $("input[name=setup_module]").attr('disabled', false);

                if($('input[name=client_module]:checked').val() == "none")
                {
                    $(".officer_module_full").attr('disabled',true);
                    $(".member_module_full").attr('disabled',true);
                    $(".controller_module_full").attr('disabled',true);
                    $(".charges_module_full").attr('disabled',true);
                    $(".filing_module_full").attr('disabled',true);
                    $(".register_module_full").attr('disabled',true);
                    $(".setup_module_full").attr('disabled',true);
                }
            }

            $(this).parent().find('input[name=document_module]').attr('disabled', true);

            if(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Document")
            {
                $("input[name=pending_module]").attr('disabled', false);
                $("input[name=all_module]").attr('disabled', false);
                $("input[name=master_module]").attr('disabled', false);
                $("input[name=reminder_module]").attr('disabled', false);
            }

            $(this).parent().find('input[name=billing_module]').attr('disabled', true);

            if(($(this).parent()[0]["innerText"]).replace(/\s/g,'') == "Billing")
            {
                $("input[name=unpaid_module]").attr('disabled', false);
                $("input[name=paid_module]").attr('disabled', false);
                $("input[name=receipt_module]").attr('disabled', false);
                $("input[name=template_module]").attr('disabled', false);
            }
            // $("#cl-row").attr("colspan","4");
            
          }
        });
        /*$('tr').on('shown.bs.collapse', function(){
            $(this).prev('tr').find(".glyphicon-chevron-right").removeClass("glyphicon-chevron-right").addClass("glyphicon-chevron-down");
        }).on('hidden.bs.collapse', function(){
            $(this).prev('tr').find(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-right");
        });*/
    });
</script>
