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
                                                    <tr>
                                                        <th>Former Name (if Any)</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="former_name_if_any" name="former_name_if_any" style="text-transform:uppercase" value="">
                                                            <div id="form_former_name_if_any"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Company ID</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_id" name="company_id" style="text-transform:uppercase" value="">
                                                            <div id="form_company_id"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Entity Type</th>
                                                        <td>
                                                            <select id="entity_type" class="form-control entity_type" style="text-align:right; width: 400px;" name="entity_type">
											                    <option value="0">Select Company Type</option>
                                                                <option value="1" selected="selected">EXEMPT PRIVATE COMPANY LIMITED BY SHARES</option>
                                                                <option value="2">PRIVATE COMPANY LIMITED BY SHARES</option>
                                                                <option value="3">PUBLIC COMPANY LIMITED BY SHARES</option>
                                                                <option value="4">EXEMPT PRIVATE COMPANY LIMITED BY GUARANTEE</option>
                                                                <option value="5">PRIVATE COMPANY LIMITED BY GUARANTEE</option>
                                                                <option value="6">PUBLIC COMPANY LIMITED BY GUARANTEE</option>
                                                                <option value="7">SOLE-PROPRIETORSHIP</option>
                                                                <option value="8">PARTNERSHIP</option>
                                                            </select>
                                                            <div id="form_entity_type"></div>
                                                        </td>               
                                                   </tr>
                                                   
                                                   <tr>
                                                        <th>Acra Uen</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="acra_uen" name="acra_uen" style="text-transform:uppercase" value="">
                                                            <div id="form_acra_uen"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Country</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="country" name="country" style="text-transform:uppercase" value="" required>
                                                            <div id="form_country"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Region</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="region" name="region" style="text-transform:uppercase" value="">
                                                            <div id="form_region"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Entity Status</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="entity_status" name="entity_status" style="text-transform:uppercase" value="">
                                                            <div id="form_entity_status"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Risk Assessment Rating</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="risk_assessment_rating" name="risk_assessment_rating" style="text-transform:uppercase" value="">
                                                            <div id="form_risk_assessment_rating"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Incorporation Date</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="incorporation_date" name="incorporation_date" style="text-transform:uppercase" value="">
                                                            <div id="form_incorporation_date"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Internal Css Status</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="internal_css_status" name="internal_css_status" style="text-transform:uppercase" value="">
                                                            <div id="form_internal_css_status"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>Articles Constitution</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="Articles_constitution" name="Articles_constitution" style="text-transform:uppercase" value="">
                                                            <div id="form_Articles_constitution"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>article_regulation_no</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="article_regulation_no" name="article_regulation_no" style="text-transform:uppercase" value="">
                                                            <div id="form_article_regulation_no"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>article_description</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="article_description" name="article_description" style="text-transform:uppercase" value="">
                                                            <div id="form_article_description"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>dormant_date</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="dormant_date" name="dormant_date" style="text-transform:uppercase" value="" required>
                                                            <div id="form_dormant_date"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>dissolved_struck_off_date</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="dissolved_struck_off_date" name="dissolved_struck_off_date" style="text-transform:uppercase" value="" required>
                                                            <div id="form_dissolved_struck_off_date"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>liquid_strike_off_date</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="liquid_strike_off_date" name="liquid_strike_off_date" style="text-transform:uppercase" value="" required>
                                                            <div id="form_liquid_strike_off_date"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>termination_date</th>
                                                        <td>
                                                            <input type="date" class="form-control input-xs" id="termination_date" name="termination_date" style="text-transform:uppercase" value="" required>
                                                            <div id="form_termination_date"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>common_seal</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="common_seal" name="common_seal" style="text-transform:uppercase" value="">
                                                            <div id="form_common_seal"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>company_stamp</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_stamp" name="company_stamp" style="text-transform:uppercase" value="">
                                                            <div id="form_company_stamp"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>statute_registrable_corporate_controller</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="statute_registrable_corporate_controller" name="statute_registrable_corporate_controller" style="text-transform:uppercase" value="" required>
                                                            <div id="form_statute_registrable_corporate_controller"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>incorporated</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="incorporated" name="incorporated" style="text-transform:uppercase" value="">
                                                            <div id="form_incorporated"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>ssic_code_activity_I</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="ssic_code_activity_I" name="ssic_code_activity_I" style="text-transform:uppercase" value="">
                                                            <div id="form_ssic_code_activity_I"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>default_ssic_description_I</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="default_ssic_description_I" name="default_ssic_description_I" style="text-transform:uppercase" value="">
                                                            <div id="form_default_ssic_description_I"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>user_described_activity_I</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="user_described_activity_I" name="user_described_activity_I" style="text-transform:uppercase" value="">
                                                            <div id="form_user_described_activity_I"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>ssic_code_activity_II</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="ssic_code_activity_II" name="ssic_code_activity_II" style="text-transform:uppercase" value="">
                                                            <div id="form_ssic_code_activity_II"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>default_ssic_description_II</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="default_ssic_description_II" name="default_ssic_description_II" style="text-transform:uppercase" value="">
                                                            <div id="form_default_ssic_description_II"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>user_described_activity_II</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="user_described_activity_II" name="user_described_activity_II" style="text-transform:uppercase" value="">
                                                            <div id="form_user_described_activity_II"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>website</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="website" name="website" style="text-transform:uppercase" value="">
                                                            <div id="form_website"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>company_phone_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_phone_1" name="company_phone_1" style="text-transform:uppercase" value="" required>
                                                            <div id="form_company_phone_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>company_phone_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_phone_2" name="company_phone_2" style="text-transform:uppercase" value="" required>
                                                            <div id="form_company_phone_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>fax</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="fax" name="fax" style="text-transform:uppercase" value="" required>
                                                            <div id="form_fax"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>company_email_address</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="company_email_address" name="company_email_address" style="text-transform:uppercase" value="" required>
                                                            <div id="form_company_email_address"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>remarks</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="remarks" name="remarks" style="text-transform:uppercase" value="">
                                                            <div id="form_remarks"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>additional_remarks</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="additional_remarks" name="additional_remarks" style="text-transform:uppercase" value="">
                                                            <div id="form_additional_remarks"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>default_address</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="default_address" name="default_address" style="text-transform:uppercase" value="">
                                                            <div id="form_default_address"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>block_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="block_0" name="block_0" style="text-transform:uppercase" value="">
                                                            <div id="form_block_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>street_name_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="street_name_0" name="street_name_0" style="text-transform:uppercase" value="">
                                                            <div id="form_street_name_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>building_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="building_0" name="building_0" style="text-transform:uppercase" value="">
                                                            <div id="form_building_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>level_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="level_0" name="level_0" style="text-transform:uppercase" value="">
                                                            <div id="form_level_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>unit_no_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="unit_no_0" name="unit_no_0" style="text-transform:uppercase" value="">
                                                            <div id="form_unit_no_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>country_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="country_0" name="country_0" style="text-transform:uppercase" value="">
                                                            <div id="form_country_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>state_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="state_0" name="state_0" style="text-transform:uppercase" value="">
                                                            <div id="form_state_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>city_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="city_0" name="city_0" style="text-transform:uppercase" value="">
                                                            <div id="form_city_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>postal_code_0</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="postal_code_0" name="postal_code_0" style="text-transform:uppercase" value="">
                                                            <div id="form_postal_code_0"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>block_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="block_1" name="block_1" style="text-transform:uppercase" value="">
                                                            <div id="form_block_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>street_name_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="street_name_1" name="street_name_1" style="text-transform:uppercase" value="">
                                                            <div id="form_street_name_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>building_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="building_1" name="building_1" style="text-transform:uppercase" value="">
                                                            <div id="form_building_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>level_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="level_1" name="level_1" style="text-transform:uppercase" value="">
                                                            <div id="form_level_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>unit_no_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="unit_no_1" name="unit_no_1" style="text-transform:uppercase" value="">
                                                            <div id="form_unit_no_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>country_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="country_1" name="country_1" style="text-transform:uppercase" value="">
                                                            <div id="form_country_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>state_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="state_1" name="state_1" style="text-transform:uppercase" value="">
                                                            <div id="form_state_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>city_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="city_1" name="city_1" style="text-transform:uppercase" value="">
                                                            <div id="form_city_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>postal_code_1</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="postal_code_1" name="postal_code_1" style="text-transform:uppercase" value="">
                                                            <div id="form_postal_code_1"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>block_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="block_2" name="block_2" style="text-transform:uppercase" value="">
                                                            <div id="form_block_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>street_name_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="street_name_2" name="street_name_2" style="text-transform:uppercase" value="">
                                                            <div id="form_street_name_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>building_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="building_2" name="building_2" style="text-transform:uppercase" value="">
                                                            <div id="form_building_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>level_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="level_2" name="level_2" style="text-transform:uppercase" value="">
                                                            <div id="form_level_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>unit_no_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="unit_no_2" name="unit_no_2" style="text-transform:uppercase" value="">
                                                            <div id="form_unit_no_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>country_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="country_2" name="country_2" style="text-transform:uppercase" value="">
                                                            <div id="form_country_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>state_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="state_2" name="state_2" style="text-transform:uppercase" value="">
                                                            <div id="form_state_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>city_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="city_2" name="city_2" style="text-transform:uppercase" value="">
                                                            <div id="form_city_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>postal_code_2</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="postal_code_2" name="postal_code_2" style="text-transform:uppercase" value="">
                                                            <div id="form_postal_code_2"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>address_line1_3</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="address_line1_3" name="address_line1_3" style="text-transform:uppercase" value="">
                                                            <div id="form_address_line1_3"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>address_line2_3</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="address_line2_3" name="address_line2_3" style="text-transform:uppercase" value="">
                                                            <div id="form_address_line2_3"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>block_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="block_4" name="block_4" style="text-transform:uppercase" value="">
                                                            <div id="form_block_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>street_name_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="street_name_4" name="street_name_4" style="text-transform:uppercase" value="">
                                                            <div id="form_street_name_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>building_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="building_4" name="building_4" style="text-transform:uppercase" value="">
                                                            <div id="form_building_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>level_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="level_4" name="level_4" style="text-transform:uppercase" value="">
                                                            <div id="form_level_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>unit_no_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="unit_no_4" name="unit_no_4" style="text-transform:uppercase" value="">
                                                            <div id="form_unit_no_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>country_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="country_4" name="country_4" style="text-transform:uppercase" value="">
                                                            <div id="form_country_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>state_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="state_4" name="state_4" style="text-transform:uppercase" value="">
                                                            <div id="form_state_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>city_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="city_4" name="city_4" style="text-transform:uppercase" value="">
                                                            <div id="form_city_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>postal_code_4</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="postal_code_4" name="postal_code_4" style="text-transform:uppercase" value="">
                                                            <div id="form_postal_code_4"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>block_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="block_5" name="block_5" style="text-transform:uppercase" value="">
                                                            <div id="form_block_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>street_name_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="street_name_5" name="street_name_5" style="text-transform:uppercase" value="">
                                                            <div id="form_street_name_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>building_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="building_5" name="building_5" style="text-transform:uppercase" value="">
                                                            <div id="form_building_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>level_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="level_5" name="level_5" style="text-transform:uppercase" value="">
                                                            <div id="form_level_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>unit_no_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="unit_no_5" name="unit_no_5" style="text-transform:uppercase" value="">
                                                            <div id="form_unit_no_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>country_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="country_5" name="country_5" style="text-transform:uppercase" value="">
                                                            <div id="form_country_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>state_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="state_5" name="state_5" style="text-transform:uppercase" value="">
                                                            <div id="form_state_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>city_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="city_5" name="city_5" style="text-transform:uppercase" value="">
                                                            <div id="form_city_5"></div>
                                                        </td>               
                                                   </tr>
                                                   <tr>
                                                        <th>postal_code_5</th>
                                                        <td>
                                                            <input type="text" class="form-control input-xs" id="postal_code_5" name="postal_code_5" style="text-transform:uppercase" value="">
                                                            <div id="form_postal_code_5"></div>
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