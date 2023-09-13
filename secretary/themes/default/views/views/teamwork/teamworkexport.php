<style>

    .popup-container {

        width: 100%;

        height: 100vh;

        background-color: #0000009e;

        text-align: center;

        padding: 30px 0;

        top: 0;

        right: 0;

        bottom: 0;

        left: 0;

        position: fixed;

        z-index: 99999;

        overflow: auto;

        display: none;

    }



    .popup {

        width: 60%;

        background-color: #fff;

        padding: 10px;

        margin: 0 auto;

        display: none;

    }



    .disable-btn {

        pointer-events: none;

        cursor: not-allowed;

    }

</style>



<div class="header_between_all_section">

    <section class="panel">

        <header class="panel-heading">

        </header>

        <div class="panel-body">

            <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">

                <tbody>

                <tr>

                    <th>Companies</th>

                    <td>

                        <select id="company-selectbox" class="form-control company" style="text-align:right; width: 400px;" name="company">

                            <?php

                            if (!isset($reg_no)) {

                            ?>

                            <option value="">Select one ...</option>

                            <?php

                            }

                            ?>

                            <?php

                            foreach ($clients as $key => $value) {

                                # code...

                                ?>

                                <option value="<?= $value->company_code ?>" ><?= $value->company_name ?></option>

                                <?php

                            }

                            ?>

                        </select>

                        <div id="form_company"></div>

                    </td>               

                </tr>

                </tbody>

            </table>



            <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="margin-top: 20px; display: table;">

                <thead>

                    <tr>

                        <?php

                            if (!isset($reg_no)) {

                        ?>

                        <th>1.1</th>

                        <?php

                            }

                        ?>

                        <th>2.1</th>

                        <th>3.1</th>

                        <th>4.1</th>

                    </tr>

                </thead>

                <tbody>

                    <tr>

                        <?php

                            if (!isset($reg_no)) {

                        ?>

                        <td>

                            <input type="button" class="btn btn-primary disable-btn" value="View & Import" id="view_1_1" />

                            <!-- <input type="button" class="btn btn-primary disable-btn" value="Import" id="import_1_1" /> -->

                        </td>

                        <?php

                            }

                        ?>

                        <td>

                            <input type="button" class="btn btn-primary disable-btn" value="View & Import" id="view_2_1" />

                            <!-- <input type="button" class="btn btn-primary disable-btn" value="Import" id="import_2_1" /> -->

                        </td>

                        <td>

                            <table class="table table-bordered table-striped table-condensed mb-none" id="officerList" style="border: 0px">

                                <!-- <tr style="border: 0px">

                                    <td style="border: 0px">Director 1</th>

                                    <td style="border: 0px">

                                        <input type="button" class="btn btn-primary disable-btn" value="View & Import" id="view_3_1" />

                                    </td>

                                </tr>

                                <tr style="border: 0px">

                                    <td style="border: 0px">Director 2</th>

                                    <td style="border: 0px">

                                        <input type="button" class="btn btn-primary disable-btn" value="View & Import" id="view_3_1" />

                                    </td>

                                </tr> -->

                            </table>

                            

                        </td>

                        <td>

                            <table class="table table-bordered table-striped table-condensed mb-none" id="corporateList" style="border: 0px">

                                <!-- <tr style="border: 0px">

                                    <td style="border: 0px">Corporate</td>

                                    <td>Corporate</td>

                                    <td style="border: 0px">

                                        <input type="button" data-officer-type="corporate" class="btn btn-primary view_4_1 disable-btn" value="View & Import" />

                                    </td>

                                </tr> -->

                            </table>

                            

                            <!-- <input type="button" class="btn btn-primary disable-btn" value="Import" id="import_4_1" /> -->

                        </td>

                    </tr>

                </tbody>

            </table>



        </div>

    </section>

</div>



<div class="popup-container">

    <div class="popup popup_1_1">

        <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">

            <tbody>

                <tr class="sticky">

                    <td colspan="2">

                        <input type="button" class="btn btn-primary" value="Import to Teamwork" id="import_1_1" />

                        <input type="button" class="btn btn-primary close-popup" value="Cancel" />

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    <div class="popup popup_2_1">

        <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">

            <tbody>

                <tr class="sticky">

                    <td colspan="2">

                        <input type="button" class="btn btn-primary" value="Import to Teamwork" id="import_2_1" />

                        <input type="button" class="btn btn-primary close-popup" value="Cancel" />

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    <div class="popup popup_3_1">

        <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">

            <tbody>

                <tr class="sticky">

                    <td colspan="2">

                        <input type="button" class="btn btn-primary" value="Import to Teamwork" id="import_3_1" />

                        <input type="button" class="btn btn-primary close-popup" value="Cancel" />

                    </td>

                </tr>

            </tbody>

        </table>

    </div>



    <div class="popup popup_4_1">

        <table class="table table-bordered table-striped table-condensed mb-none" id="tr_individual_edit" style="display: table;">

            <tbody>

                <tr class="sticky">

                    <td colspan="2">

                        <input type="button" class="btn btn-primary" value="Import to Teamwork" id="import_4_1" />

                        <input type="button" class="btn btn-primary close-popup" value="Cancel" />

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>



<script type="text/javascript">

    let teamwork;

    let clients = <?php echo json_encode(isset($clients)?$clients:'');?>;

    let clientOfficers = [];

    let client = {};

    let clientOfficer = {};

    let chooseCode = "";



    let reg_no = <?php echo json_encode(isset($reg_no)?$reg_no:'');?>;

    if (reg_no != "") {

        setTimeout(() => {

            client = clients;

            chooseCode = clients[0].company_code;



            teamwork = new Teamwork(client);

            

            getClientOfficers(chooseCode);

        }, 100)

    }



    $(document).ready(function() {

        $(document).on("change", "#company-selectbox", function() {

            let code = $(this).val();

            chooseCode = code;

            client = $.grep(clients, function(v) {

                return v.company_code === code;

            });

            teamwork = new Teamwork(client);



            getClientOfficers(code);

        })



        $(document).on("click", "#view_1_1", function() {

            teamwork.viewForm("popup_1_1");

        });



        $(document).on("click", "#import_1_1", function() {

            teamwork.importApi11();

        });



        $(document).on("click", "#view_2_1", function() {

            teamwork.viewForm("popup_2_1");

        });



        $(document).on("click", "#import_2_1", function() {

            teamwork.importApi21();

        });



        $(document).on("click", ".view_3_1", function() {

            if ($(this).attr("data-officer-type") == "shareHolder") {

                teamwork.viewForm("popup_3_1", {type: "shareHolder", data: [clientOfficers["shareHolder"][$(this).attr("data-officerid")]]});

            } else 

            if ($(this).attr("data-officer-type") == "contactInfo") {

                teamwork.viewForm("popup_3_1", {type: "contactInfo", data: []});

            } else 

            if ($(this).attr("data-officer-type") == "controller") {

                teamwork.viewForm("popup_3_1", {type: "controller", data: [clientOfficers["controller"][$(this).attr("data-officerid")]]});

            } else {

                let clickId = parseInt($(this).attr("data-officerid"));

                clientOfficer = $.grep(clientOfficers["clientOfficers"], function(v) {

                    return parseInt(v.officer_id) == clickId;

                });

                teamwork.viewForm("popup_3_1", {type: "officer", data: clientOfficer});

            }

            

        });



        $(document).on("click", "#import_3_1", function() {

            teamwork.importApi31();

        });



        $(document).on("click", ".view_4_1", function() {

            let clickId = parseInt($(this).attr("data-officerid"));

            

            if ($(this).attr("data-officer-type") == "auditor") {

                clientOfficer = $.grep(clientOfficers["clientOfficers"], function(v) {

                    return parseInt(v.officer_id) == clickId;

                });

                teamwork.viewForm("popup_4_1", {type: "auditor", data: clientOfficer});

            } else

            if ($(this).attr("data-officer-type") == "controller") {

                clientOfficer = clientOfficers["controller"][clickId];

                teamwork.viewForm("popup_4_1", {type: "controller", data: [clientOfficer]});

            } else {

                console.log(clickId);

                clientOfficer = clientOfficers["shareHolder"][clickId];

                console.log(clientOfficer);

                teamwork.viewForm("popup_4_1", {type: "shareHolder", data: [clientOfficer]});

            }

        });



        $(document).on("click", "#import_4_1", function() {

            teamwork.importApi41();

        });



        $(document).on("click", ".close-popup", function() {

            $(".popup-container").hide();

            $(".popup").hide();

        });

        

    })



    function getClientOfficers(code) {

        var pushData = {} 

        pushData["company_code"] = code;

        

        $.ajax({

            "url":"<?=base_url("teamworks/getClientOfficer")?>",

            "method":"POST",

            "dataType":"JSON",

            "data":pushData,

            success:function(data){

                clientOfficers = data // ["clientOfficers"]

                teamwork.bindRowsFor31(data);

                // console.log(data)

            },

            error: function(data) {

                console.log(data)

            },

        });



        $(".disable-btn").removeClass("disable-btn");

    }



    class Teamwork {

        constructor(client) {

            this.client = client;

            this.clientOfficer = [];

            this.shareHolder = [];

            this.contactInfo = [];

            this.controller = [];

            this.data_1_1 = {};

            this.data_2_1 = {};

            this.data_3_1 = {};

            this.data_4_1 = {};

        }



        viewForm(form, data = null) { // data = null, type = null) {

            $(".popup-container").show();

            $("."+form).show();

            if (data != null) {

                if (data.type == "shareHolder") {

                    this.shareHolder = data.data;

                } else if (data.type == "controller") {

                    this.controller = data.data;

                } else {

                    console.log(data.data)

                    this.clientOfficer = data.data;

                }

            }

            if (data != null) {

                if  (data.type == "shareHolder" || data.type == "controller" || data.type == "contactInfo" || data.type == "officer") {

                    this.constructData(form, data.type);

                } else {

                    if (form == "popup_4_1" && data.type == "auditor") {

                        this.constructData(form, data.type);

                    } else {

                        this.constructData(form, data.type);

                    }

                }

            } else {

                this.constructData(form);

            }

            

            this.bindForm(form);

        }



        date_format(date) {

            if (date != null && date != "null" && date != "") {

                date = new Date(date);

            } else {
                return "";
                // date = new Date();

            }



            let d = (date.getDate() < 10) ? "0"+date.getDate() : date.getDate();

            let m =  date.getMonth();

            m += 1;  // JavaScript months are 0-11

            if (m < 10) {

                m = "0"+m;

            }

            let y = date.getFullYear();

            return d + "/" + m + "/" + y;

        }



        ValidateEmail(email) {

            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)) {

                return true;

            }

            return false;

        }



        constructData(form, type = null) {

            console.log(this.client);

            let todayDate = new Date();

            let formattedDate = this.date_format(todayDate);

            switch (form) {

                case "popup_1_1":
                    let activity1 = (this.client[0].activity1).split("-");
                    let activity2 = (this.client[0].activity2).split("-");

                    let clientStatus = "";
                    if (this.client[0].status == "1" || this.client[0].status == "5") {
                        clientStatus = "Live";
                    } else
                    if (this.client[0].status == "6") {
                        clientStatus = "Strike off";
                    } else
                    if (this.client[0].status == "7") {
                        clientStatus = "Liquidated";
                    }

                    this.data_1_1 = {

                        "ccs_client": 1,

                        "entity_name": this.client[0].company_name,

                        "former_name_if_any": "",

                        "company_id": this.client[0].client_code,

                        "entity_type": 2,

                        "registration_no": this.client[0].registration_no,

                        "acra_uen": this.client[0].registration_no,

                        "country": this.client[0].client_country_of_incorporation,

                        "region": 0,

                        "entity_status": clientStatus,

                        "risk_assessment_rating": 1,

                        "incorporation_date": this.client[0].incorporation_date,

                        "internal_css_status": 2,

                        "Articles_constitution": 1,

                        "article_regulation_no": "",

                        "article_description": "",

                        "dormant_date": "",

                        "dissolved_struck_off_date": "",

                        "liquid_strike_off_date": "",

                        "termination_date": "",

                        "common_seal": 2,

                        "company_stamp": 2,

                        "statute_registrable_corporate_controller": "",

                        "incorporated": "",

                        "ssic_code_activity_I": ((activity1[0].trim()).includes(" ")) ? ((activity1[0].trim()).split(" ")[1]) : (activity1[0].trim()),

                        "default_ssic_description_I": activity1[1],

                        "user_described_activity_I": this.client[0].description1,

                        "ssic_code_activity_II": ((activity2[0].trim()).includes(" ")) ? ((activity2[0].trim()).split(" ")[1]) : (activity2[0].trim()),

                        "default_ssic_description_II": activity2[1],

                        "user_described_activity_II": this.client[0].description2,

                        "website": "",

                        "company_phone_1": "",

                        "company_phone_2": "",

                        "fax": "",

                        "company_email_address": "",

                        "remarks": "",

                        "additional_remarks": "",

                        "default_address": "Default Address",

                        "block_0": "",

                        "street_name_0": this.client[0].street_name,

                        "building_0": this.client[0].building_name,

                        "level_0": this.client[0].unit_no1,

                        "unit_no_0": this.client[0].unit_no2,

                        "country_0": this.client[0].client_country_of_incorporation,

                        "state_0": " ",

                        "city_0": " ",

                        "postal_code_0": this.client[0].postal_code,

                        "block_1": "",

                        "street_name_1": this.client[0].street_name,

                        "building_1": this.client[0].building_name,

                        "level_1": this.client[0].unit_no1,

                        "unit_no_1": this.client[0].unit_no2,

                        "country_1": this.client[0].client_country_of_incorporation,

                        "state_1": " ",

                        "city_1": " ",

                        "postal_code_1": this.client[0].postal_code,

                        "block_2": "",

                        "street_name_2": this.client[0].street_name,

                        "building_2": this.client[0].building_name,

                        "level_2": this.client[0].unit_no1,

                        "unit_no_2": this.client[0].unit_no2,

                        "country_2": this.client[0].client_country_of_incorporation,

                        "state_2": " ",

                        "city_2": " ",

                        "postal_code_2": this.client[0].postal_code,

                        "address_line1_3": "",

                        "address_line2_3": "",

                        "block_4": "",

                        "street_name_4": this.client[0].street_name,

                        "building_4": this.client[0].building_name,

                        "level_4": this.client[0].unit_no1,

                        "unit_no_4": this.client[0].unit_no2,

                        "country_4": this.client[0].client_country_of_incorporation,

                        "state_4": " ",

                        "city_4": " ",

                        "postal_code_4": this.client[0].postal_code,

                        "block_5": "",

                        "street_name_5": this.client[0].street_name,

                        "building_5": this.client[0].building_name,

                        "level_5": this.client[0].unit_no1,

                        "unit_no_5": this.client[0].unit_no2,

                        "country_5": this.client[0].client_country_of_incorporation,

                        "state_5": " ",

                        "city_5": " ",

                        "postal_code_5": this.client[0].postal_code

                    }

                    break;

                case "popup_2_1":

                    console.log(this.client);

                    let yearDate = this.date_format(this.client[0].year_end);

                    let fye_range_from = (this.client[0].financial_year_period1 != "") ? this.date_format(this.client[0].financial_year_period1) : "";

                    let fye_range_to = (this.client[0].financial_year_period2 != "") ? this.date_format(this.client[0].financial_year_period2) : "";

                    this.data_2_1 = {

                        "registration_no": this.client[0].registration_no,

                        "type_of_event": "1,2",

                        "year": (yearDate != "") ? yearDate.substr(yearDate.length - 4) : "",

                        "actual_fye": yearDate,

                        "fye_range_from": fye_range_from,

                        "fye_range_to": fye_range_to,

                        "held_date_1": "",

                        "filling_date_1": "",

                        "held_date_2": "",

                        "filling_date_2": "",

                        "held_date_3": "",

                        "filling_date_3": "",

                        "held_date_4": "",

                        "filling_date_4": "",

                        "held_date_5": "",

                        "filling_date_5": "",

                        "held_date_6": "",

                        "filling_date_6": ""

                    }

                    break;

                case "popup_3_1":

                    let identification_type = 7;

                    if (type == "shareHolder") {

                        console.log("this.shareHolder -> ", this.shareHolder[0])

                        this.shareHolder[0].identification_type = (this.shareHolder[0].identification_type != "" && this.shareHolder[0].identification_type != null) ? (this.shareHolder[0].identification_type).toLowerCase().replaceAll(" ", "") : "";

                        switch (this.shareHolder[0].identification_type) {

                            case "nric(singaporecitizen)":

                                identification_type = 1;

                                break;

                            case "nric(pr)":

                                identification_type = 2;

                                break;

                            case "nirc":

                                identification_type = 3;

                                break;

                            case "finnumber":

                                identification_type = 4;

                                break;

                            case "passport/others":

                                identification_type = 5;

                                break;

                            case "nricmalaysia":

                                identification_type = 6;

                                break;

                            default:

                                identification_type = 1;

                                break;

                        }

                        this.data_3_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": 2,

                            "individual_name": (this.shareHolder[0].name == null) ? this.shareHolder[0].client_company_name : this.shareHolder[0].name,

                            "risk_assessment_rating": "",

                            "individual_former_name": "",

                            "gender": "",

                            "alias": "",

                            "date_of_birth": this.date_format(this.shareHolder[0].date_of_birth),

                            "country_of_birth": "",

                            "nationality": "",

                            "status": "",

                            "additional_notes": "",

                            "identification_type": identification_type,

                            "id_no": this.shareHolder[0].identification_no,

                            "id_issued_country": "",

                            "id_expiry_date": "",

                            "id_issued_date": "",

                            "identification_type2": "",

                            "id_no2": "",

                            "id_issued_country2": "",

                            "id_expiry_date2": "",

                            "id_issued_date2": "",

                            "preferred_contact_mode": "",

                            "email_address": "",

                            "skype_id": "",

                            "mobile_number": "",

                            "telephone_number": "",

                            "fax": "",

                            "default_address": "",

                            "block_0": "",

                            "street_name_0": "",

                            "building_0": "",

                            "level_0": "",

                            "unit_no_0": "",

                            "country_0": "",

                            "state_0": "",

                            "city_0": "",

                            "postal_code_0": "",

                            "address_line1_1": this.shareHolder[0].foreign_address1,

                            "address_line2_1": this.shareHolder[0].foreign_address2,

                            "block_2": "",

                            "street_name_2": "",

                            "building_2": "",

                            "level_2": "",

                            "unit_no_2": "",

                            "country_2": "",

                            "state_2": "",

                            "city_2": "",

                            "postal_code_2": "",

                            "address_line1_3": this.shareHolder[0].foreign_address1,

                            "address_line2_3": this.shareHolder[0].foreign_address2,

                            "date_of_appointment_1": "",

                            "date_of_cessation_1": "",

                            "date_of_appointment_3": "",

                            "date_of_cessation_3": "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": "",

                            "date_of_cessation_5": "",

                            "date_of_appointment_6": "",

                            "date_of_cessation_6": "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": "",

                            "date_of_cessation_8": "",

                            "date_of_appointment_9": "",

                            "date_of_cessation_9": "",

                            "date_of_appointment_10": "",

                            "date_of_cessation_10": "",

                            "date_of_appointment_11": "",

                            "date_of_cessation_11": "",

                            "date_of_appointment_12": "",

                            "date_of_cessation_12": ""

                        }

                    } else 

                    if (type == "contactInfo") {
                        console.log(this.contactInfo);
                        this.data_3_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": 8,

                            "individual_name": this.contactInfo[0].name,

                            "risk_assessment_rating": "",

                            "individual_former_name": "",

                            "gender": "",

                            "alias": "",

                            "date_of_birth": "",

                            "country_of_birth": "",

                            "nationality": "",

                            "status": "",

                            "additional_notes": "",

                            "identification_type": identification_type,

                            "id_no": 0,

                            "id_issued_country": "",

                            "id_expiry_date": "",

                            "id_issued_date": "",

                            "identification_type2": "",

                            "id_no2": "",

                            "id_issued_country2": "",

                            "id_expiry_date2": "",

                            "id_issued_date2": "",

                            "preferred_contact_mode": "",

                            "email_address": this.contactInfo[0].email,

                            "skype_id": "",

                            "mobile_number": (this.contactInfo[0].phone != "" && this.contactInfo[0].phone != null) ? (this.contactInfo[0].phone).replace("+65", "+65-") : this.contactInfo[0].phone,

                            "telephone_number": "",

                            "fax": "",

                            "default_address": "",

                            "block_0": "",

                            "street_name_0": "",

                            "building_0": "",

                            "level_0": "",

                            "unit_no_0": "",

                            "country_0": "",

                            "state_0": "",

                            "city_0": "",

                            "postal_code_0": "",

                            "address_line1_1": "",

                            "address_line2_1": "",

                            "block_2": "",

                            "street_name_2": "",

                            "building_2": "",

                            "level_2": "",

                            "unit_no_2": "",

                            "country_2": "",

                            "state_2": "",

                            "city_2": "",

                            "postal_code_2": "",

                            "address_line1_3": "",

                            "address_line2_3": "",

                            "date_of_appointment_1": "",

                            "date_of_cessation_1": "",

                            "date_of_appointment_3": "",

                            "date_of_cessation_3": "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": "",

                            "date_of_cessation_5": "",

                            "date_of_appointment_6": "",

                            "date_of_cessation_6": "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": (this.contactInfo[0].date_of_appointment != "") ? this.contactInfo[0].date_of_appointment : "",

                            "date_of_cessation_8": (this.contactInfo[0].date_of_cessation != "") ? this.contactInfo[0].date_of_cessation : "",

                            "date_of_appointment_9": "",

                            "date_of_cessation_9": "",

                            "date_of_appointment_10": "",

                            "date_of_cessation_10": "",

                            "date_of_appointment_11": "",

                            "date_of_cessation_11": "",

                            "date_of_appointment_12": "",

                            "date_of_cessation_12": ""

                        }

                    } else

                    if (type == "controller") {

                        this.controller[0].identification_type = (this.controller[0].identification_type).toLowerCase().replaceAll(" ", "");

                        switch (this.controller[0].identification_type) {

                            case "nric(singaporecitizen)":

                                identification_type = 1;

                                break;

                            case "nric(pr)":

                                identification_type = 2;

                                break;

                            case "nirc":

                                identification_type = 3;

                                break;

                            case "finnumber":

                                identification_type = 4;

                                break;

                            case "passport/others":

                                identification_type = 5;

                                break;

                            case "nricmalaysia":

                                identification_type = 6;

                                break;

                            default:

                                identification_type = 1;

                                break;

                        }

                        this.data_3_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": 10,

                            "individual_name": this.controller[0].name,

                            "risk_assessment_rating": "",

                            "individual_former_name": "",

                            "gender": "",

                            "alias": this.controller[0].alias,

                            "date_of_birth": this.date_format(this.controller[0].date_of_birth),

                            "country_of_birth": "",

                            "nationality": this.controller[0].officer_nationality_name,

                            "status": "",

                            "additional_notes": "",

                            "identification_type": identification_type,

                            "id_no": this.controller[0].identification_no,

                            "id_issued_country": "",

                            "id_expiry_date": "",

                            "id_issued_date": "",

                            "identification_type2": "",

                            "id_no2": "",

                            "id_issued_country2": "",

                            "id_expiry_date2": "",

                            "id_issued_date2": "",

                            "preferred_contact_mode": "",

                            "email_address": "",

                            "skype_id": "",

                            "mobile_number": "",

                            "telephone_number": "",

                            "fax": "",

                            "default_address": "",

                            "block_0": "",

                            "street_name_0": this.controller[0].street_name1,

                            "building_0": this.controller[0].building_name1,

                            "level_0": "",

                            "unit_no_0": (this.controller[0].unit_no1 == null) ? "" : this.controller[0].unit_no1,

                            "country_0": "",

                            "state_0": "",

                            "city_0": "",

                            "postal_code_0": this.controller[0].postal_code1,

                            "address_line1_1": this.controller[0].foreign_address1,

                            "address_line2_1": this.controller[0].foreign_address2,

                            "block_2": "",

                            "street_name_2": this.controller[0].street_name2,

                            "building_2": this.controller[0].building_name2,

                            "level_2": "",

                            "unit_no_2": (this.controller[0].unit_no2 == null) ? "" : this.controller[0].unit_no2,

                            "country_2": "",

                            "state_2": "",

                            "city_2": "",

                            "postal_code_2": this.controller[0].postal_code2,

                            "address_line1_3": this.controller[0].foreign_address2,

                            "address_line2_3": this.controller[0].foreign_address3,

                            "date_of_appointment_1": "",

                            "date_of_cessation_1": "", // this.date_format(this.controller[0].date_of_cessation),

                            "date_of_appointment_3": "",

                            "date_of_cessation_3": "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": "",

                            "date_of_cessation_5": "",

                            "date_of_appointment_6": "",

                            "date_of_cessation_6": "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": "",

                            "date_of_cessation_8": "",

                            "date_of_appointment_9": "",

                            "date_of_cessation_9": "",

                            "date_of_appointment_10": (this.controller[0].date_of_appointment != "") ? this.controller[0].date_of_appointment : "",

                            "date_of_cessation_10": (this.controller[0].date_of_cessation != "") ? this.controller[0].date_of_cessation : "",

                            "date_of_appointment_11": "",

                            "date_of_cessation_11": "",

                            "date_of_appointment_12": "",

                            "date_of_cessation_12": ""

                        }

                    } else {

                        this.clientOfficer[0].identification_type = (this.clientOfficer[0].identification_type).toLowerCase().replaceAll(" ", "");

                        switch (this.clientOfficer[0].identification_type) {

                            case "nric(singaporecitizen)":

                                identification_type = 1;

                                break;

                            case "nric(pr)":

                                identification_type = 2;

                                break;

                            case "nirc":

                                identification_type = 3;

                                break;

                            case "finnumber":

                                identification_type = 4;

                                break;

                            case "passport/others":

                                identification_type = 5;

                                break;

                            case "nricmalaysia":

                                identification_type = 6;

                                break;

                            default:

                                identification_type = 1;

                                break;

                        }



                        let positionId = 0;

                        switch (this.clientOfficer[0].position) {

                            case "1":

                                positionId = 1;

                                break;

                            case "2":

                                positionId = 5;

                                break;

                            case "3":

                                positionId = 6;

                                break;

                            case "4":

                                positionId = 3;

                                break;

                            // case "5":

                            //     positionId = 0;

                            //     break;

                            default:

                                break;

                        }

                        this.data_3_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": parseInt(positionId),

                            "individual_name": this.clientOfficer[0].name,

                            "risk_assessment_rating": "",

                            "individual_former_name": "",

                            "gender": "",

                            "alias": this.clientOfficer[0].alias,

                            "date_of_birth": this.date_format(this.clientOfficer[0].date_of_birth),

                            "country_of_birth": "",

                            "nationality": this.clientOfficer[0].nationality_text,

                            "status": "",

                            "additional_notes": "",

                            "identification_type": identification_type,

                            "id_no": this.clientOfficer[0].identification_no,

                            "id_issued_country": "",

                            "id_expiry_date": "",

                            "id_issued_date": "",

                            "identification_type2": "",

                            "id_no2": "",

                            "id_issued_country2": "",

                            "id_expiry_date2": "",

                            "id_issued_date2": "",

                            "preferred_contact_mode": "",

                            "email_address": this.ValidateEmail(this.clientOfficer[0].email) ? this.clientOfficer[0].email : "",

                            "skype_id": "",

                            "mobile_number": (this.clientOfficer[0].mobile_no != "") ? (this.clientOfficer[0].mobile_no).replace((this.clientOfficer[0].mobile_no).slice(0, 3), (this.clientOfficer[0].mobile_no).slice(0, 3)+"-") : (this.clientOfficer[0].mobile_no != false) ? this.clientOfficer[0].mobile_no : "",

                            "telephone_number": "",

                            "fax": "",

                            "default_address": "",

                            "block_0": "",

                            "street_name_0": this.clientOfficer[0].street_name1,

                            "building_0": this.clientOfficer[0].building_name1,

                            "level_0": "",

                            "unit_no_0": this.clientOfficer[0].unit_no1,

                            "country_0": "",

                            "state_0": "",

                            "city_0": "",

                            "postal_code_0": this.clientOfficer[0].postal_code1,

                            "address_line1_1": this.clientOfficer[0].foreign_address1,

                            "address_line2_1": this.clientOfficer[0].foreign_address2,

                            "block_2": "",

                            "street_name_2": this.clientOfficer[0].street_name2,

                            "building_2": this.clientOfficer[0].building_name2,

                            "level_2": "",

                            "unit_no_2": this.clientOfficer[0].unit_no2,

                            "country_2": "",

                            "state_2": "",

                            "city_2": "",

                            "postal_code_2": this.clientOfficer[0].postal_code2,

                            "address_line1_3": this.clientOfficer[0].foreign_address2,

                            "address_line2_3": this.clientOfficer[0].foreign_address3,

                            "date_of_appointment_1": (this.clientOfficer[0].position == "1") ? this.clientOfficer[0].date_of_appointment : "",

                            "date_of_cessation_1": (this.clientOfficer[0].position == "1") ? (this.clientOfficer[0].date_of_cessation != "") ? this.clientOfficer[0].date_of_cessation : "" : "",

                            "date_of_appointment_3": (this.clientOfficer[0].position == "4") ? this.clientOfficer[0].date_of_appointment : "",

                            "date_of_cessation_3": (this.clientOfficer[0].position == "4") ? (this.clientOfficer[0].date_of_cessation != "") ? this.clientOfficer[0].date_of_cessation : "" : "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": (this.clientOfficer[0].position == "2") ? this.clientOfficer[0].date_of_appointment : "",

                            "date_of_cessation_5": (this.clientOfficer[0].position == "2") ? (this.clientOfficer[0].date_of_cessation != "") ? this.clientOfficer[0].date_of_cessation : "" : "",

                            "date_of_appointment_6": (this.clientOfficer[0].position == "3") ? this.clientOfficer[0].date_of_appointment : "",

                            "date_of_cessation_6": (this.clientOfficer[0].position == "3") ? (this.clientOfficer[0].date_of_cessation != "") ? this.clientOfficer[0].date_of_cessation : "" : "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": "",

                            "date_of_cessation_8": "",

                            "date_of_appointment_9": "",

                            "date_of_cessation_9": "",

                            "date_of_appointment_10": "",

                            "date_of_cessation_10": "",

                            "date_of_appointment_11": "",

                            "date_of_cessation_11": "",

                            "date_of_appointment_12": "",

                            "date_of_cessation_12": ""

                        }

                    }

                    break;

                case "popup_4_1":

                    if (type == "auditor") {

                        this.data_4_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": 3,

                            "corporate_registration_no": this.clientOfficer[0].register_no,

                            "entity_name": this.clientOfficer[0].company_name,

                            "date_of_appointment_1": "",

                            "date_of_cessation_1": "",

                            "date_of_appointment_3": (this.clientOfficer[0].date_of_appointment != "") ? this.clientOfficer[0].date_of_appointment : "",

                            "date_of_cessation_3": (this.clientOfficer[0].date_of_cessation != "") ? this.clientOfficer[0].date_of_cessation : "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": "",

                            "date_of_cessation_5": "",

                            "date_of_appointment_6": "",

                            "date_of_cessation_6": "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": ""

                        }

                    } else

                    if (type == "controller") {

                        this.data_4_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": 6,

                            "corporate_registration_no": (this.controller[0].register_no != null) ? this.controller[0].register_no : this.controller[0].registration_no,

                            "entity_name": (this.controller[0].officer_company_company_name != null) ? this.controller[0].officer_company_company_name : this.controller[0].client_company_name,

                            "date_of_appointment_1": "",

                            "date_of_cessation_1": "",

                            "date_of_appointment_3": "",

                            "date_of_cessation_3": "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": "",

                            "date_of_cessation_5": "",

                            "date_of_appointment_6": (this.controller[0].date_of_entry != "") ? this.controller[0].date_of_entry : "",

                            "date_of_cessation_6": (this.controller[0].date_of_cessation != "") ? this.controller[0].date_of_cessation : "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": ""

                        }

                    } else {

                        this.data_4_1 = {

                            "registration_no": this.client[0].registration_no,

                            "official": 2,

                            "corporate_registration_no": this.shareHolder[0].register_no,

                            "entity_name": this.shareHolder[0].company_name,

                            "date_of_appointment_1": "",

                            "date_of_cessation_1": "",

                            "date_of_appointment_3": "",

                            "date_of_cessation_3": "",

                            "date_of_appointment_4": "",

                            "date_of_cessation_4": "",

                            "date_of_appointment_5": "",

                            "date_of_cessation_5": "",

                            "date_of_appointment_6": "",

                            "date_of_cessation_6": "",

                            "date_of_appointment_7": "",

                            "date_of_cessation_7": "",

                            "date_of_appointment_8": ""

                        }

                    }

                    break;

                default:
                    break;
            }

        }



        bindForm(form) {

            $("."+form+" table tbody tr:not(.sticky)").remove();

            switch (form) {

                case "popup_1_1":

                    $("."+form+" table tbody").prepend(

                        `<tr>

                            <th>CCS Client</th>

                            <td>

                                <label>${this.data_1_1.ccs_client}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Entity Name</th>

                            <td>

                                <label>${this.data_1_1.entity_name}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Former Name If Any</th>

                            <td>

                                <label>${this.data_1_1.former_name_if_any}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Company Id</th>

                            <td>

                                <label>${this.data_1_1.company_id}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Entity Type</th>

                            <td>

                                <label>${this.data_1_1.entity_type}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Registration No</th>

                            <td>

                                <label>${this.data_1_1.registration_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Acra Uen</th>

                            <td>

                                <label>${this.data_1_1.acra_uen}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country</th>

                            <td>

                                <label>${this.data_1_1.country}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Region</th>

                            <td>

                                <label>${this.data_1_1.region}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Entity Status</th>

                            <td>

                                <label>${this.data_1_1.entity_status}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Risk Assessment Rating</th>

                            <td>

                                <label>${this.data_1_1.risk_assessment_rating}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Incorporation Date</th>

                            <td>

                                <label>${this.data_1_1.incorporation_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Internal CSS Status</th>

                            <td>

                                <label>${this.data_1_1.internal_css_status}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Articles Constitution</th>

                            <td>

                                <label>${this.data_1_1.Articles_constitution}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Article Regulation No</th>

                            <td>

                                <label>${this.data_1_1.article_regulation_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Article Description</th>

                            <td>

                                <label>${this.data_1_1.article_description}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Dormant Date</th>

                            <td>

                                <label>${this.data_1_1.dormant_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Dissolved Struck Off Date</th>

                            <td>

                                <label>${this.data_1_1.dissolved_struck_off_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Liquid Strike Off Date</th>

                            <td>

                                <label>${this.data_1_1.liquid_strike_off_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Termination Date</th>

                            <td>

                                <label>${this.data_1_1.termination_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Common Seal</th>

                            <td>

                                <label>${this.data_1_1.common_seal}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Company Stamp</th>

                            <td>

                                <label>${this.data_1_1.company_stamp}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Statute Registrable Corporate Controller</th>

                            <td>

                                <label>${this.data_1_1.statute_registrable_corporate_controller}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Incorporated</th>

                            <td>

                                <label>${this.data_1_1.incorporated}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>SSIC Code Activity I</th>

                            <td>

                                <label>${this.data_1_1.ssic_code_activity_I}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Default SSIC Description I</th>

                            <td>

                                <label>${this.data_1_1.default_ssic_description_I}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>User Described Activity I</th>

                            <td>

                                <label>${this.data_1_1.user_described_activity_I}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>SSIC Code Activity II</th>

                            <td>

                                <label>${this.data_1_1.ssic_code_activity_II}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Default SSIC Description II</th>

                            <td>

                                <label>${this.data_1_1.default_ssic_description_II}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>User Described Activity II</th>

                            <td>

                                <label>${this.data_1_1.user_described_activity_II}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Website</th>

                            <td>

                                <label>${this.data_1_1.website}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Company Phone 1</th>

                            <td>

                                <label>${this.data_1_1.company_phone_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Company Phone 2</th>

                            <td>

                                <label>${this.data_1_1.company_phone_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Fax</th>

                            <td>

                                <label>${this.data_1_1.fax}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Company Email Address</th>

                            <td>

                                <label>${this.data_1_1.company_email_address}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Remarks</th>

                            <td>

                                <label>${this.data_1_1.remarks}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Additional Remarks</th>

                            <td>

                                <label>${this.data_1_1.additional_remarks}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Default Address</th>

                            <td>

                                <label>${this.data_1_1.default_address}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 0</th>

                            <td>

                                <label>${this.data_1_1.block_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 0</th>

                            <td>

                                <label>${this.data_1_1.street_name_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 0</th>

                            <td>

                                <label>${this.data_1_1.building_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 0</th>

                            <td>

                                <label>${this.data_1_1.level_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 0</th>

                            <td>

                                <label>${this.data_1_1.unit_no_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 0</th>

                            <td>

                                <label>${this.data_1_1.country_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 0</th>

                            <td>

                                <label>${this.data_1_1.state_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 0</th>

                            <td>

                                <label>${this.data_1_1.city_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 0</th>

                            <td>

                                <label>${this.data_1_1.postal_code_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 1</th>

                            <td>

                                <label>${this.data_1_1.block_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 1</th>

                            <td>

                                <label>${this.data_1_1.street_name_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 1</th>

                            <td>

                                <label>${this.data_1_1.building_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 1</th>

                            <td>

                                <label>${this.data_1_1.level_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 1</th>

                            <td>

                                <label>${this.data_1_1.unit_no_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 1</th>

                            <td>

                                <label>${this.data_1_1.country_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 1</th>

                            <td>

                                <label>${this.data_1_1.state_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 1</th>

                            <td>

                                <label>${this.data_1_1.city_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 1</th>

                            <td>

                                <label>${this.data_1_1.postal_code_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 2</th>

                            <td>

                                <label>${this.data_1_1.block_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 2</th>

                            <td>

                                <label>${this.data_1_1.street_name_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 2</th>

                            <td>

                                <label>${this.data_1_1.building_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 2</th>

                            <td>

                                <label>${this.data_1_1.level_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 2</th>

                            <td>

                                <label>${this.data_1_1.unit_no_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 2</th>

                            <td>

                                <label>${this.data_1_1.country_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 2</th>

                            <td>

                                <label>${this.data_1_1.street_name_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 2</th>

                            <td>

                                <label>${this.data_1_1.city_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 2</th>

                            <td>

                                <label>${this.data_1_1.postal_code_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Address Line 1 3</th>

                            <td>

                                <label>${this.data_1_1.address_line1_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Address Line 2 3</th>

                            <td>

                                <label>${this.data_1_1.address_line2_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 4</th>

                            <td>

                                <label>${this.data_1_1.block_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 4</th>

                            <td>

                                <label>${this.data_1_1.street_name_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 4</th>

                            <td>

                                <label>${this.data_1_1.building_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 4</th>

                            <td>

                                <label>${this.data_1_1.level_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 4</th>

                            <td>

                                <label>${this.data_1_1.unit_no_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 4</th>

                            <td>

                                <label>${this.data_1_1.country_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 4</th>

                            <td>

                                <label>${this.data_1_1.state_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 4</th>

                            <td>

                                <label>${this.data_1_1.city_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 4</th>

                            <td>

                                <label>${this.data_1_1.postal_code_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 5</th>

                            <td>

                                <label>${this.data_1_1.block_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 5</th>

                            <td>

                                <label>${this.data_1_1.street_name_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 5</th>

                            <td>

                                <label>${this.data_1_1.building_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 5</th>

                            <td>

                                <label>${this.data_1_1.level_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 5</th>

                            <td>

                                <label>${this.data_1_1.unit_no_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 5</th>

                            <td>

                                <label>${this.data_1_1.country_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 5</th>

                            <td>

                                <label>${this.data_1_1.state_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 5</th>

                            <td>

                                <label>${this.data_1_1.city_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 5</th>

                            <td>

                                <label>${this.data_1_1.postal_code_5}</label>

                            </td>               

                        </tr>

                    `);

                    break;

                case "popup_2_1":

                    $("."+form+" table tbody").prepend(`

                        <tr>

                            <th>Registration No</th>

                            <td>

                                <label>${this.data_2_1.registration_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Type of Event</th>

                            <td>

                                <label>${this.data_2_1.type_of_event}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Year</th>

                            <td>

                                <label>${this.data_2_1.year}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Actual FYE</th>

                            <td>

                                <label>${this.data_2_1.actual_fye}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>FYE Range From</th>

                            <td>

                                <label>${this.data_2_1.fye_range_from}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>FYE Range To</th>

                            <td>

                                <label>${this.data_2_1.fye_range_to}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Held Date 1</th>

                            <td>

                                <label>${this.data_2_1.held_date_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Filling Date 1</th>

                            <td>

                                <label>${this.data_2_1.filling_date_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Held Date 2</th>

                            <td>

                                <label>${this.data_2_1.held_date_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Filling Date 2</th>

                            <td>

                                <label>${this.data_2_1.filling_date_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Held Date 3</th>

                            <td>

                                <label>${this.data_2_1.held_date_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Filling Date 3</th>

                            <td>

                                <label>${this.data_2_1.filling_date_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Held Date 4</th>

                            <td>

                                <label>${this.data_2_1.held_date_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Filling Date 4</th>

                            <td>

                                <label>${this.data_2_1.filling_date_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Held Date 5</th>

                            <td>

                                <label>${this.data_2_1.held_date_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Filling Date 5</th>

                            <td>

                                <label>${this.data_2_1.filling_date_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Held Date 6</th>

                            <td>

                                <label>${this.data_2_1.held_date_6}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Filling Date 6</th>

                            <td>

                                <label>${this.data_2_1.filling_date_6}</label>

                            </td>               

                        </tr>

                    `)

                    break;

                case "popup_3_1":

                    $("."+form+" table tbody").prepend(`

                        <tr>

                            <th>Registration No</th>

                            <td>

                                <label>${this.data_3_1.registration_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Official</th>

                            <td>

                                <label>${this.data_3_1.official}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Individual Name</th>

                            <td>

                                <label>${this.data_3_1.individual_name}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Risk Assessment Rating</th>

                            <td>

                                <label>${this.data_3_1.risk_assessment_rating}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Individual Former Name</th>

                            <td>

                                <label>${this.data_3_1.individual_former_name}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Gender</th>

                            <td>

                                <label>${this.data_3_1.gender}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Alias</th>

                            <td>

                                <label>${this.data_3_1.alias}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Birth</th>

                            <td>

                                <label>${this.data_3_1.date_of_birth}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country of Birth</th>

                            <td>

                                <label>${this.data_3_1.country_of_birth}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Nationality</th>

                            <td>

                                <label>${this.data_3_1.nationality}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Status</th>

                            <td>

                                <label>${this.data_3_1.status}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Additional Notes</th>

                            <td>

                                <label>${this.data_3_1.additional_notes}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Identification Type</th>

                            <td>

                                <label>${this.data_3_1.identification_type}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id No</th>

                            <td>

                                <label>${this.data_3_1.id_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id Issued Country</th>

                            <td>

                                <label>${this.data_3_1.id_issued_country}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id Expiry Date</th>

                            <td>

                                <label>${this.data_3_1.id_expiry_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id Issued Date</th>

                            <td>

                                <label>${this.data_3_1.id_issued_date}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Identification Type 2</th>

                            <td>

                                <label>${this.data_3_1.identification_type2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id No 2</th>

                            <td>

                                <label>${this.data_3_1.id_no2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id Issued Country 2</th>

                            <td>

                                <label>${this.data_3_1.id_issued_country2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id Expiry Date 2</th>

                            <td>

                                <label>${this.data_3_1.id_expiry_date2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Id Issued Date 2</th>

                            <td>

                                <label>${this.data_3_1.id_issued_date2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Preferred Contact Mode</th>

                            <td>

                                <label>${this.data_3_1.preferred_contact_mode}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Email Address</th>

                            <td>

                                <label>${this.data_3_1.email_address}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Skype Id</th>

                            <td>

                                <label>${this.data_3_1.skype_id}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Mobile Number</th>

                            <td>

                                <label>${this.data_3_1.mobile_number}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Telephone Number</th>

                            <td>

                                <label>${this.data_3_1.telephone_number}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Fax</th>

                            <td>

                                <label>${this.data_3_1.fax}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Default Address</th>

                            <td>

                                <label>${this.data_3_1.default_address}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 0</th>

                            <td>

                                <label>${this.data_3_1.block_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 0</th>

                            <td>

                                <label>${this.data_3_1.street_name_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 0</th>

                            <td>

                                <label>${this.data_3_1.building_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 0</th>

                            <td>

                                <label>${this.data_3_1.level_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 0</th>

                            <td>

                                <label>${this.data_3_1.unit_no_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 0</th>

                            <td>

                                <label>${this.data_3_1.country_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 0</th>

                            <td>

                                <label>${this.data_3_1.state_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 0</th>

                            <td>

                                <label>${this.data_3_1.city_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 0</th>

                            <td>

                                <label>${this.data_3_1.postal_code_0}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Address Line 1 1</th>

                            <td>

                                <label>${this.data_3_1.address_line1_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Address Line 2 1</th>

                            <td>

                                <label>${this.data_3_1.address_line2_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Block 2</th>

                            <td>

                                <label>${this.data_3_1.block_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Street Name 2</th>

                            <td>

                                <label>${this.data_3_1.street_name_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Building 2</th>

                            <td>

                                <label>${this.data_3_1.building_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Level 2</th>

                            <td>

                                <label>${this.data_3_1.level_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Unit No 2</th>

                            <td>

                                <label>${this.data_3_1.unit_no_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Country 2</th>

                            <td>

                                <label>${this.data_3_1.country_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>State 2</th>

                            <td>

                                <label>${this.data_3_1.state_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>City 2</th>

                            <td>

                                <label>${this.data_3_1.city_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Postal Code 2</th>

                            <td>

                                <label>${this.data_3_1.postal_code_2}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Address line 1 3</th>

                            <td>

                                <label>${this.data_3_1.address_line1_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Address Line 2 3</th>

                            <td>

                                <label>${this.data_3_1.address_line2_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 1</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 1</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 3</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 3</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 4</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 4</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 5</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 5</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 6</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_6}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 6</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_6}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 7</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_7}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 7</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_7}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 8</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_8}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 8</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_8}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 9</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_9}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 9</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_9}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 10</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_10}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 10</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_10}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 11</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_11}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 11</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_11}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 12</th>

                            <td>

                                <label>${this.data_3_1.date_of_appointment_12}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Cessation 12</th>

                            <td>

                                <label>${this.data_3_1.date_of_cessation_12}</label>

                            </td>               

                        </tr>

                    `)

                    break;

                case "popup_4_1":

                    $("."+form+" table tbody").prepend(`

                        <tr>

                            <th>Registration No</th>

                            <td>

                                <label>${this.data_4_1.registration_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>official</th>

                            <td>

                                <label>${this.data_4_1.official}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Corporate Registration No</th>

                            <td>

                                <label>${this.data_4_1.corporate_registration_no}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Entity Name</th>

                            <td>

                                <label>${this.data_4_1.entity_name}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 1</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of cessation 1</th>

                            <td>

                                <label>${this.data_4_1.date_of_cessation_1}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 3</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of cessation 3</th>

                            <td>

                                <label>${this.data_4_1.date_of_cessation_3}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 4</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of cessation 4</th>

                            <td>

                                <label>${this.data_4_1.date_of_cessation_4}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 5</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of cessation 5</th>

                            <td>

                                <label>${this.data_4_1.date_of_cessation_5}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 6</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_6}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of cessation 6</th>

                            <td>

                                <label>${this.data_4_1.date_of_cessation_6}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 7</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_7}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of cessation 7</th>

                            <td>

                                <label>${this.data_4_1.date_of_cessation_7}</label>

                            </td>               

                        </tr>

                        <tr>

                            <th>Date of Appointment 8</th>

                            <td>

                                <label>${this.data_4_1.date_of_appointment_8}</label>

                            </td>               

                        </tr>

                    `)

                    break;

                default:

                    break;

            }

        }





        bindRowsFor31(data) {

            console.log(data);

            $("#officerList tr").remove();

            $("#corporateList tr.can-remove").remove();

            if (typeof data["clientOfficers"] != "undefined") {

                (data["clientOfficers"]).forEach(element => {

                    let positionLabel = "";

                    switch (element.position) {

                        case "1":

                            positionLabel = "Director";

                            break;

                        case "2":

                            positionLabel = "CEO";

                            break;

                        case "3":

                            positionLabel = "Manager";

                            break;

                        case "4":

                            positionLabel = "Secretary";

                            break;

                        case "5":

                            positionLabel = "Auditor";

                            break;

                        default:

                            break;

                    }

                    if (positionLabel == "Auditor") {

                        $("#corporateList").append(`<tr class="can-remove" style="border: 0px">

                                <td style="border: 0px">${element.company_name}</td>

                                <td style="border: 0px">${positionLabel}</td>

                                <td style="border: 0px">

                                    <input type="button" class="btn btn-primary view_4_1" data-officer-type="auditor" data-officerid="${element.officer_id}" value="View & Import" />

                                </td>

                            </tr>`)

                    } else {

                        $("#officerList").append(`<tr style="border: 0px">

                                <td style="border: 0px">${element.name}</td>

                                <td style="border: 0px">${positionLabel}</td>

                                <td style="border: 0px">

                                    <input type="button" class="btn btn-primary view_3_1" data-officer-type="officer" data-officerid="${element.officer_id}" value="View & Import" />

                                </td>

                            </tr>`)

                    }

                });

            }



            if (typeof data["shareHolder"] != "undefined") {

                // this.shareHolder = data["shareHolder"];

                (data["shareHolder"]).forEach((element, index) => {

                    if (element.field_type == "company") {

                        $("#corporateList").append(`<tr class="can-remove" style="border: 0px">

                            <td style="border: 0px">${element.company_name}</td>

                            <td style="border: 0px">Share Holder</td>

                            <td style="border: 0px">

                                <input type="button" class="btn btn-primary view_4_1" data-officer-type="shareHolder" data-officerid="${index}" value="View & Import" />

                            </td>

                        </tr>`)

                    } else {

                        $("#officerList").append(`<tr style="border: 0px">

                            <td style="border: 0px">${(element.name == null) ? element.client_company_name : element.name}</td>

                            <td style="border: 0px">Share Holder</td>

                            <td style="border: 0px">

                                <input type="button" class="btn btn-primary view_3_1" data-officer-type="shareHolder" data-officerid="${index}" value="View & Import" />

                            </td>

                        </tr>`)

                    }

                });

            }

            

            if (typeof data["contactInfo"] != "undefined") {

            this.contactInfo = data["contactInfo"];

                (data["contactInfo"]).forEach(element => {

                    $("#officerList").append(`<tr style="border: 0px">

                        <td style="border: 0px">${element.name}</td>

                        <td style="border: 0px">Contact Person</td>

                        <td style="border: 0px">

                            <input type="button" class="btn btn-primary view_3_1" data-officer-type="contactInfo" data-officerid="${element.client_contact_info_id}" value="View & Import" />

                        </td>

                    </tr>`)

                });

            }



            if (data["controller"] != false) {

                // this.controller = data["controller"];

                (data["controller"]).forEach((element, index) => {

                    if (element.client_controller_field_type == "company" || element.client_controller_field_type == "client") {

                        $("#corporateList").append(`<tr class="can-remove" style="border: 0px">

                            <td style="border: 0px">${(element.officer_company_company_name != null) ? element.officer_company_company_name : element.client_company_name}</td>

                            <td style="border: 0px">Controller</td>

                            <td style="border: 0px">

                                <input type="button" class="btn btn-primary view_4_1" data-officer-type="controller" data-officerid="${index}" value="View & Import" />

                            </td>

                        </tr>`)

                    } else {

                        $("#officerList").append(`<tr style="border: 0px">

                            <td style="border: 0px">${(element.name != null) ? element.name : element.client_company_name }</td>

                            <td style="border: 0px">Controller</td>

                            <td style="border: 0px">

                                <input type="button" class="btn btn-primary view_3_1" data-officer-type="controller" data-officerid="${index}" value="View & Import" />

                            </td>

                        </tr>`)

                    }

                    

                });

            }

        }



        importApi11() {

            var form = new FormData();

            form.append("ccs_client", this.data_1_1.ccs_client);

            form.append("entity_name", this.data_1_1.entity_name);

            form.append("former_name_if_any", this.data_1_1.former_name_if_any);

            form.append("company_id", this.data_1_1.company_id);

            form.append("entity_type", this.data_1_1.entity_type);

            form.append("registration_no", this.data_1_1.registration_no);

            form.append("acra_uen", this.data_1_1.acra_uen);

            form.append("country", this.data_1_1.country);

            form.append("region", this.data_1_1.region);

            form.append("entity_status", this.data_1_1.entity_status);

            form.append("risk_assessment_rating", this.data_1_1.risk_assessment_rating);

            form.append("incorporation_date", this.data_1_1.incorporation_date);

            form.append("internal_css_status", this.data_1_1.internal_css_status);

            form.append("Articles_constitution", this.data_1_1.Articles_constitution);

            form.append("article_regulation_no", this.data_1_1.article_regulation_no);

            form.append("article_description", this.data_1_1.article_description);

            form.append("dormant_date", this.data_1_1.dormant_date);

            form.append("dissolved_struck_off_date", this.data_1_1.dissolved_struck_off_date);

            form.append("liquid_strike_off_date", this.data_1_1.liquid_strike_off_date);

            form.append("termination_date", this.data_1_1.termination_date);

            form.append("common_seal", this.data_1_1.common_seal);

            form.append("company_stamp", this.data_1_1.company_stamp);

            form.append("statute_registrable_corporate_controller", this.data_1_1.statute_registrable_corporate_controller);

            form.append("incorporated", this.data_1_1.incorporated);

            form.append("ssic_code_activity_I", this.data_1_1.ssic_code_activity_I);

            form.append("default_ssic_description_I", this.data_1_1.default_ssic_description_I);

            form.append("user_described_activity_I", this.data_1_1.user_described_activity_I);

            form.append("ssic_code_activity_II", this.data_1_1.ssic_code_activity_II);

            form.append("default_ssic_description_II", this.data_1_1.default_ssic_description_II);

            form.append("user_described_activity_II", this.data_1_1.user_described_activity_II);

            form.append("website", this.data_1_1.website);

            form.append("company_phone_1", this.data_1_1.company_phone_1);

            form.append("company_phone_2", this.data_1_1.company_phone_2);

            form.append("fax", this.data_1_1.fax);

            form.append("company_email_address", this.data_1_1.company_email_address);

            form.append("remarks", this.data_1_1.remarks);

            form.append("additional_remarks", this.data_1_1.additional_remarks);

            form.append("default_address", this.data_1_1.default_address);

            form.append("block_0", this.data_1_1.block_0);

            form.append("street_name_0", this.data_1_1.street_name_0);

            form.append("building_0", this.data_1_1.building_0);

            form.append("level_0", this.data_1_1.level_0);

            form.append("unit_no_0", this.data_1_1.unit_no_0);

            form.append("country_0", this.data_1_1.country_0);

            form.append("state_0", this.data_1_1.state_0);

            form.append("city_0", this.data_1_1.city_0);

            form.append("postal_code_0", this.data_1_1.postal_code_0);

            form.append("block_1", this.data_1_1.block_1);

            form.append("street_name_1", this.data_1_1.street_name_1);

            form.append("building_1", this.data_1_1.building_1);

            form.append("level_1", this.data_1_1.level_1);

            form.append("unit_no_1", this.data_1_1.unit_no_1);

            form.append("country_1", this.data_1_1.country_1);

            form.append("state_1", this.data_1_1.state_1);

            form.append("city_1", this.data_1_1.city_1);

            form.append("postal_code_1", this.data_1_1.postal_code_1);

            form.append("block_2", this.data_1_1.block_2);

            form.append("street_name_2", this.data_1_1.street_name_2);

            form.append("building_2", this.data_1_1.building_2);

            form.append("level_2", this.data_1_1.level_2);

            form.append("unit_no_2", this.data_1_1.unit_no_2);

            form.append("country_2", this.data_1_1.country_2);

            form.append("state_2", this.data_1_1.state_2);

            form.append("city_2", this.data_1_1.city_2);

            form.append("postal_code_2", this.data_1_1.postal_code_2);

            form.append("address_line1_3", this.data_1_1.address_line1_3);

            form.append("address_line2_3", this.data_1_1.address_line2_3);

            form.append("block_4", this.data_1_1.block_4);

            form.append("street_name_4", this.data_1_1.street_name_4);

            form.append("building_4", this.data_1_1.building_4);

            form.append("level_4", this.data_1_1.level_4);

            form.append("unit_no_4", this.data_1_1.unit_no_4);

            form.append("country_4", this.data_1_1.country_4);

            form.append("state_4", this.data_1_1.state_4);

            form.append("city_4", this.data_1_1.city_4);

            form.append("postal_code_4", this.data_1_1.postal_code_4);

            form.append("block_5", this.data_1_1.block_5);

            form.append("street_name_5", this.data_1_1.street_name_5);

            form.append("building_5", this.data_1_1.building_5);

            form.append("level_5", this.data_1_1.level_5);

            form.append("unit_no_5", this.data_1_1.unit_no_5);

            form.append("country_5", this.data_1_1.country_5);

            form.append("state_5", this.data_1_1.state_5);

            form.append("city_5", this.data_1_1.city_5);

            form.append("postal_code_5", this.data_1_1.postal_code_5);



            var settings = {

            "url": "https://apps.teamworkcss.com/aaa_global/api/index/add_company",

            "method": "POST",

            "timeout": 0,

            "headers": {

                "x-api-key": "91bcec91-ddf0-402c-b287-a03d3563c320"

            },

            "processData": false,

            "mimeType": "multipart/form-data",

            "contentType": false,

            "data": form

            };



            $.ajax(settings).done(function (response) {

                console.log(response);

                $(".popup-container").hide();

                $(".popup_1_1").hide();

            });

        }



        importApi21() {

            var form = new FormData();

            form.append("registration_no", this.data_2_1.registration_no);

            form.append("type_of_event", this.data_2_1.type_of_event);

            form.append("year", this.data_2_1.year);

            form.append("actual_fye", this.data_2_1.actual_fye);

            form.append("fye_range_from", this.data_2_1.fye_range_from);

            form.append("fye_range_to", this.data_2_1.fye_range_to);

            form.append("held_date_1", this.data_2_1.held_date_1);

            form.append("filling_date_1", this.data_2_1.filling_date_1);

            form.append("held_date_2", this.data_2_1.held_date_2);

            form.append("filling_date_2", this.data_2_1.filling_date_2);

            form.append("held_date_3", this.data_2_1.held_date_3);

            form.append("filling_date_3", this.data_2_1.filling_date_3);

            form.append("held_date_4", this.data_2_1.held_date_4);

            form.append("filling_date_4", this.data_2_1.filling_date_4);

            form.append("held_date_5", this.data_2_1.held_date_5);

            form.append("filling_date_5", this.data_2_1.filling_date_5);

            form.append("held_date_6", this.data_2_1.held_date_6);

            form.append("filling_date_6", this.data_2_1.filling_date_6);



            var settings = {

            "url": "https://apps.teamworkcss.com/aaa_global/api/index/event_date",

            "method": "POST",

            "timeout": 0,

            "headers": {

                "x-api-key": "91bcec91-ddf0-402c-b287-a03d3563c320"

            },

            "processData": false,

            "mimeType": "multipart/form-data",

            "contentType": false,

            "data": form

            };



            $.ajax(settings).done(function (response) {

                console.log(response);

                $(".popup-container").hide();

                $(".popup_2_1").hide();

            });

        }



        importApi31() {

            var form = new FormData();

            form.append("registration_no", this.data_3_1.registration_no);

            form.append("official", this.data_3_1.official);

            form.append("individual_name", this.data_3_1.individual_name);

            form.append("risk_assessment_rating", this.data_3_1.risk_assessment_rating);

            form.append("individual_former_name", this.data_3_1.individual_former_name);

            form.append("gender", this.data_3_1.gender);

            form.append("alias", this.data_3_1.alias);

            form.append("date_of_birth", this.data_3_1.date_of_birth);

            form.append("country_of_birth", this.data_3_1.country_of_birth);

            form.append("nationality", this.data_3_1.nationality);

            form.append("status", this.data_3_1.status);

            form.append("additional_notes", this.data_3_1.additional_notes);

            form.append("identification_type", this.data_3_1.identification_type);

            form.append("id_no", this.data_3_1.id_no);

            form.append("id_issued_country", this.data_3_1.id_issued_country);

            form.append("id_expiry_date", this.data_3_1.id_expiry_date);

            form.append("id_issued_date", this.data_3_1.id_issued_date);

            form.append("identification_type2", this.data_3_1.identification_type2);

            form.append("id_no2", this.data_3_1.id_no2);

            form.append("id_issued_country2", this.data_3_1.id_issued_country2);

            form.append("id_expiry_date2", this.data_3_1.id_expiry_date2);

            form.append("id_issued_date2", this.data_3_1.id_issued_date2);

            form.append("preferred_contact_mode", this.data_3_1.preferred_contact_mode);

            form.append("email_address", this.data_3_1.email_address);

            form.append("skype_id", this.data_3_1.skype_id);

            form.append("mobile_number", this.data_3_1.mobile_number);

            form.append("telephone_number", this.data_3_1.telephone_number);

            form.append("fax", this.data_3_1.fax);

            form.append("default_address", this.data_3_1.default_address);

            form.append("block_0", this.data_3_1.block_0);

            form.append("street_name_0", this.data_3_1.street_name_0);

            form.append("building_0", this.data_3_1.building_0);

            form.append("level_0", this.data_3_1.level_0);

            form.append("unit_no_0", this.data_3_1.unit_no_0);

            form.append("country_0", this.data_3_1.country_0);

            form.append("state_0", this.data_3_1.state_0);

            form.append("city_0", this.data_3_1.city_0);

            form.append("postal_code_0", this.data_3_1.postal_code_0);

            form.append("address_line1_1", this.data_3_1.address_line1_1);

            form.append("address_line2_1", this.data_3_1.address_line2_1);

            form.append("block_2", this.data_3_1.block_2);

            form.append("street_name_2", this.data_3_1.street_name_2);

            form.append("building_2", this.data_3_1.building_2);

            form.append("level_2", this.data_3_1.level_2);

            form.append("unit_no_2", this.data_3_1.unit_no_2);

            form.append("country_2", this.data_3_1.country_2);

            form.append("state_2", this.data_3_1.state_2);

            form.append("city_2", this.data_3_1.city_2);

            form.append("postal_code_2", this.data_3_1.postal_code_2);

            form.append("address_line1_3", this.data_3_1.address_line1_3);

            form.append("address_line2_3", this.data_3_1.address_line2_3);

            form.append("date_of_appointment_1", this.data_3_1.date_of_appointment_1);

            form.append("date_of_cessation_1", this.data_3_1.date_of_cessation_1);

            form.append("date_of_appointment_3", this.data_3_1.date_of_appointment_3);

            form.append("date_of_cessation_3", this.data_3_1.date_of_cessation_3);

            form.append("date_of_appointment_4", this.data_3_1.date_of_appointment_4);

            form.append("date_of_cessation_4", this.data_3_1.date_of_cessation_4);

            form.append("date_of_appointment_5", this.data_3_1.date_of_appointment_5);

            form.append("date_of_cessation_5", this.data_3_1.date_of_cessation_5);

            form.append("date_of_appointment_6", this.data_3_1.date_of_appointment_6);

            form.append("date_of_cessation_6", this.data_3_1.date_of_cessation_6);

            form.append("date_of_appointment_7", this.data_3_1.date_of_appointment_7);

            form.append("date_of_cessation_7", this.data_3_1.date_of_cessation_7);

            form.append("date_of_appointment_8", this.data_3_1.date_of_appointment_8);

            form.append("date_of_cessation_8", this.data_3_1.date_of_cessation_8);

            form.append("date_of_appointment_9", this.data_3_1.date_of_appointment_9);

            form.append("date_of_cessation_9", this.data_3_1.date_of_cessation_9);

            form.append("date_of_appointment_10", this.data_3_1.date_of_appointment_10);

            form.append("date_of_cessation_10", this.data_3_1.date_of_cessation_10);

            form.append("date_of_appointment_11", this.data_3_1.date_of_appointment_11);

            form.append("date_of_cessation_11", this.data_3_1.date_of_cessation_11);

            form.append("date_of_appointment_12", this.data_3_1.date_of_appointment_12);

            form.append("date_of_cessation_12", this.data_3_1.date_of_cessation_12);



            var settings = {

            "url": "https://apps.teamworkcss.com/aaa_global/api/index/add_individual",

            "method": "POST",

            "timeout": 0,

            "headers": {

                "x-api-key": "91bcec91-ddf0-402c-b287-a03d3563c320"

            },

            "processData": false,

            "mimeType": "multipart/form-data",

            "contentType": false,

            "data": form

            };



            $.ajax(settings).done(function (response) {

                console.log(response);

                $(".popup-container").hide();

                $(".popup_3_1").hide();

            });

        }



        importApi41() {

            var form = new FormData();

            form.append("registration_no", this.data_4_1.registration_no);

            form.append("official", this.data_4_1.official);

            form.append("corporate_registration_no", this.data_4_1.corporate_registration_no);

            form.append("entity_name", this.data_4_1.entity_name);

            form.append("date_of_appointment_1", this.data_4_1.date_of_appointment_1);

            form.append("date_of_cessation_1", this.data_4_1.date_of_cessation_1);

            form.append("date_of_appointment_3", this.data_4_1.date_of_appointment_3);

            form.append("date_of_cessation_3", this.data_4_1.date_of_cessation_3);

            form.append("date_of_appointment_4", this.data_4_1.date_of_appointment_4);

            form.append("date_of_cessation_4", this.data_4_1.date_of_cessation_4);

            form.append("date_of_appointment_5", this.data_4_1.date_of_appointment_5);

            form.append("date_of_cessation_5", this.data_4_1.date_of_cessation_5);

            form.append("date_of_appointment_6", this.data_4_1.date_of_appointment_6);

            form.append("date_of_cessation_6", this.data_4_1.date_of_cessation_6);

            form.append("date_of_appointment_7", this.data_4_1.date_of_appointment_7);

            form.append("date_of_cessation_7", this.data_4_1.date_of_cessation_7);

            form.append("date_of_appointment_8", this.data_4_1.date_of_appointment_8);



            var settings = {

            "url": "https://apps.teamworkcss.com/aaa_global/api/index/add_corporate",

            "method": "POST",

            "timeout": 0,

            "headers": {

                "x-api-key": "91bcec91-ddf0-402c-b287-a03d3563c320"

            },

            "processData": false,

            "mimeType": "multipart/form-data",

            "contentType": false,

            "data": form

            };



            $.ajax(settings).done(function (response) {

                console.log(response);

                $(".popup-container").hide();

                $(".popup_4_1").hide();

            });

        }

    }

</script>