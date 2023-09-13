$(document).ready(function () {

// Order level shipping and discoutn localStorage 
if (podiscount = localStorage.getItem('podiscount')) {
    $('#podiscount').val(podiscount);
}
$('#potax2').change(function (e) {
    localStorage.setItem('potax2', $(this).val());
});
if (potax2 = localStorage.getItem('potax2')) {
    $('#potax2').select2("val", potax2);
}
$('#postatus').change(function (e) {
    localStorage.setItem('postatus', $(this).val());
});
if (postatus = localStorage.getItem('postatus')) {
    $('#postatus').select2("val", postatus);
}
var old_shipping;
$('#poshipping').focus(function () {
    old_shipping = $(this).val();
}).change(function () {
    if (!is_numeric($(this).val())) {
        $(this).val(old_shipping);
        bootbox.alert(lang.unexpected_value);
        return;
    } else {
        shipping = $(this).val() ? parseFloat($(this).val()) : '0';
    }
    localStorage.setItem('poshipping', shipping);
    var gtotal = ((total + invoice_tax) - order_discount) + shipping;
    $('#gtotal').text(formatMoney(gtotal));
    $('#tship').text(formatMoney(shipping));
});
if (poshipping = localStorage.getItem('poshipping')) {
    shipping = parseFloat(poshipping);
    $('#poshipping').val(shipping);
}

// If there is any item in localStorage
if (localStorage.getItem('poitems')) {
    loadItems();
}

    // clear localStorage and reload
    $('#reset').click(function (e) {
        bootbox.confirm(lang.r_u_sure, function (result) {
            if (result) {
                if (localStorage.getItem('poitems')) {
                    localStorage.removeItem('poitems');
                }
                if (localStorage.getItem('podiscount')) {
                    localStorage.removeItem('podiscount');
                }
                if (localStorage.getItem('potax2')) {
                    localStorage.removeItem('potax2');
                }
                if (localStorage.getItem('poshipping')) {
                    localStorage.removeItem('poshipping');
                }
                if (localStorage.getItem('poref')) {
                    localStorage.removeItem('poref');
                }
                if (localStorage.getItem('powarehouse')) {
                    localStorage.removeItem('powarehouse');
                }
                if (localStorage.getItem('ponote')) {
                    localStorage.removeItem('ponote');
                }
                if (localStorage.getItem('posupplier')) {
                    localStorage.removeItem('posupplier');
                }
                if (localStorage.getItem('pocurrency')) {
                    localStorage.removeItem('pocurrency');
                }
                if (localStorage.getItem('poextras')) {
                    localStorage.removeItem('poextras');
                }
				if (localStorage.getItem('downpayment')) {
					localStorage.removeItem('downpayment');
				}
				if (localStorage.getItem('podate')) {
					localStorage.removeItem('podate');
				}
				if (localStorage.getItem('date_kirim')) {
					localStorage.removeItem('date_kirim');
				}
				if (localStorage.getItem('date_kirim2')) {
					localStorage.removeItem('date_kirim2');
				}
				if (localStorage.getItem('date_kirim3')) {
					localStorage.removeItem('date_kirim3');
				}
                if (localStorage.getItem('postatus')) {
                    localStorage.removeItem('postatus');
                }

                 $('#modal-loading').show();
                 location.reload();
             }
         });
});

// save and load the fields in and/or from localStorage
var $supplier = $('#posupplier'), $currency = $('#pocurrency');

$('#poref').change(function (e) {
    localStorage.setItem('poref', $(this).val());
});
if (poref = localStorage.getItem('poref')) {
    $('#poref').val(poref);
}
$('#powarehouse').change(function (e) {
    localStorage.setItem('powarehouse', $(this).val());
});
if (powarehouse = localStorage.getItem('powarehouse')) {
    $('#powarehouse').select2("val", powarehouse);
}

        $('#ponote').redactor('destroy');
        $('#ponote').redactor({
            buttons: ['formatting', '|', 'alignleft', 'aligncenter', 'alignright', 'justify', '|', 'bold', 'italic', 'underline', '|', 'unorderedlist', 'orderedlist', '|', 'link', '|', 'html'],
            formattingTags: ['p', 'pre', 'h3', 'h4'],
            minHeight: 100,
            changeCallback: function (e) {
                var v = this.get();
                localStorage.setItem('ponote', v);
            }
        });
        if (ponote = localStorage.getItem('ponote')) {
            $('#ponote').redactor('set', ponote);
        }
        $supplier.change(function (e) {
            localStorage.setItem('posupplier', $(this).val());
			// alert($(this).val());
            $('#supplier_id').val($(this).val());
        });
        if (posupplier = localStorage.getItem('posupplier')) {
            $supplier.val(posupplier).select2({
                minimumInputLength: 1,
                data: [],
                initSelection: function (element, callback) {
                    $.ajax({
                        type: "get", async: false,
                        url: site.base_url+"suppliers/getSupplier/" + $(element).val(),
                        dataType: "json",
                        success: function (data) {
                            callback(data[0]);
							// alert(data[0]['termofpay']);
							// $("#termofpay").val(data[0]['termofpay']);
                        }
                    });
                },
                ajax: {
                    url: site.base_url + "suppliers/suggestions",
                    dataType: 'json',
                    quietMillis: 15,
                    data: function (term, page) {
                        return {
                            term: term,
                            limit: 10
                        };
                    },
                    results: function (data, page) {
                        if (data.results != null) {
                            return {results: data.results};
                        } else {
                            return {results: [{id: '', text: 'No Match Found'}]};
                        }
                    }
                }
            });

		} else {
			nsSupplier();
		}

    /*$('.rexpiry').change(function (e) {
        var item_id = $(this).closest('tr').attr('data-item-id');
        poitems[item_id].row.expiry = $(this).val();
        localStorage.setItem('poitems', JSON.stringify(poitems));
    });*/
if (localStorage.getItem('poextras')) {
    $('#extras').iCheck('check');
    $('#extras-con').show();
}
$('#extras').on('ifChecked', function () {
    localStorage.setItem('poextras', 1);
    $('#extras-con').slideDown();
});
$('#extras').on('ifUnchecked', function () {
    localStorage.removeItem("poextras");
    $('#extras-con').slideUp();
});
$(document).on('change', '.rexpiry', function () { 
    var item_id = $(this).closest('tr').attr('data-item-id');
    poitems[item_id].row.expiry = $(this).val();
    localStorage.setItem('poitems', JSON.stringify(poitems));
});


// prevent default action upon enter
$('body').bind('keypress', function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }
});

// Order tax calcuation 
if (site.settings.tax2 != 0) {
    $('#potax2').change(function () {
        localStorage.setItem('potax2', $(this).val());
        loadItems();
        return;
    });
}

// Order discount calcuation 
var old_podiscount;
$('#podiscount').focus(function () {
    old_podiscount = $(this).val();
}).change(function () {
    if (is_valid_discount($(this).val())) {
        localStorage.removeItem('podiscount');
        localStorage.setItem('podiscount', $(this).val());
        loadItems();
        return;
    } else {
        $(this).val(old_podiscount);
        bootbox.alert(lang.unexpected_value);
        return;
    }

});


    /* ---------------------- 
     * Delete Row Method 
     * ---------------------- */

$(document).on('click', '.podel', function () {
    var row = $(this).closest('tr');
    var item_id = row.attr('data-item-id');
    if (site.settings.product_discount == 1) {
        idiscount = formatMoney($.trim(row.children().children('.rdiscount').text()));
        total_discount -= idiscount;
    }
    if (site.settings.tax1 == 1) {
        var itax = row.children().children('.sproduct_tax').text();
        var iptax = itax.split(') ');
        var iproduct_tax = parseFloat(iptax[1]);
        product_tax -= iproduct_tax;
    }
    var iqty = parseFloat(row.children().children('.rquantity').val());
    var icost = parseFloat(row.children().children('.rcost').val());
    an -= 1;
    total -= (iqty * icost);
    count -= iqty;

    var gtotal = ((total + product_tax + invoice_tax) - total_discount) + shipping;
    $('#total').text(formatMoney(total));
    $('#tds').text(formatMoney(total_discount));
    $('#titems').text(count - 1);
    $('#ttax1').text(formatMoney(product_tax));
    $('#gtotal').text(formatMoney(gtotal));
    if (count == 1) {
        $('#posupplier').select2('readonly', false);
            //$('#pocurrency').select2('readonly', false);
        }
        //console.log(poitems[item_id].row.name + ' is being removed.');
        delete poitems[item_id];
        localStorage.setItem('poitems', JSON.stringify(poitems));
        row.remove();

    });

    /* -----------------------
     * Edit Row Modal Hanlder 
     ----------------------- */
     $(document).on('click', '.edit', function () {
        var row = $(this).closest('tr');
        var row_id = row.attr('id');
        item_id = row.attr('data-item-id');
        item = poitems[item_id];
        var qty = row.children().children('.rquantity').val(), 
        product_option = row.children().children('.roption').val(),
        unit_cost = formatDecimal(row.children().children('.realucost').val()),
        discount = row.children().children('.rdiscount').val();
        var net_cost = unit_cost;
        $('#prModalLabel').text(item.row.name + ' (' + item.row.code + ')');
        if (site.settings.tax1) {
            $('#ptax').select2('val', item.row.tax_rate);
            $('#old_tax').val(item.row.tax_rate);
            var item_discount = 0, ds = discount ? discount : '0';
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    item_discount = parseFloat(((unit_cost) * parseFloat(pds[0])) / 100);
                } else {
                    item_discount = parseFloat(ds);
                }
            } else {
                item_discount = parseFloat(ds);
            }

            var pr_tax = item.row.tax_rate, pr_tax_val = 0;
            if (pr_tax !== null && pr_tax != 0) {
                $.each(tax_rates, function () {
                    if(this.id == pr_tax){
                        if (this.type == 1) {

                            if (poitems[item_id].row.tax_method == 0) {
                                pr_tax_val = formatDecimal(((unit_cost) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                                pr_tax_rate = formatDecimal(this.rate) + '%';
                                net_cost -= pr_tax_val;
                            } else {
                                pr_tax_val = formatDecimal(((unit_cost) * parseFloat(this.rate)) / 100);
                                pr_tax_rate = formatDecimal(this.rate) + '%';
                            }

                        } else if (this.type == 2) {

                            pr_tax_val = parseFloat(this.rate);
                            pr_tax_rate = this.rate;

                        }
                    }
                });
            }
        }
        if (site.settings.product_serial !== 0) {
            $('#pserial').val(row.children().children('.rserial').val());
        }
        var opt = '<p style="margin: 12px 0 0 0;">n/a</p>';
        if(item.options !== false) {
            var o = 1;
            opt = $("<select id=\"poption\" name=\"poption\" class=\"form-control select\" />");
            $.each(item.options, function () {
                if(o == 1) {
                    if(product_option == '') { product_variant = this.id; } else { product_variant = product_option; }
                }
                $("<option />", {value: this.id, text: this.name}).appendTo(opt);
                o++;
            });
        } 

        $('#poptions-div').html(opt);
        $('select.select').select2({minimumResultsForSearch: 6});
        $('#pquantity').val(qty);
        $('#old_qty').val(qty);
        $('#pcost').val(unit_cost);
        $('#punit_cost').val(formatDecimal(parseFloat(unit_cost)+parseFloat(pr_tax_val)));
        $('#poption').select2('val', item.row.option);
        $('#old_cost').val(unit_cost);
        $('#row_id').val(row_id);
        $('#item_id').val(item_id);
        $('#pexpiry').val(row.children().children('.rexpiry').val());
        $('#pdiscount').val(discount);
        $('#net_cost').text(formatMoney(net_cost));
        $('#pro_tax').text(formatMoney(pr_tax_val));
        $('#prModal').appendTo("body").modal('show');

    });

    $('#prModal').on('shown.bs.modal', function (e) {
        if($('#poption').select2('val') != '') {
            $('#poption').select2('val', product_variant);
            product_variant = 0;
        }
    });

    $(document).on('change', '#pcost, #ptax, #pdiscount', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id');
        var unit_cost = parseFloat($('#pcost').val());
        var item = poitems[item_id];
        var ds = $('#pdiscount').val() ? $('#pdiscount').val() : '0';
        if (ds.indexOf("%") !== -1) {
            var pds = ds.split("%");
            if (!isNaN(pds[0])) {
                item_discount = parseFloat(((unit_cost) * parseFloat(pds[0])) / 100);
            } else {
                item_discount = parseFloat(ds);
            }
        } else {
            item_discount = parseFloat(ds);
        }
        unit_cost -= item_discount;
        var pr_tax = $('#ptax').val(), item_tax_method = item.row.tax_method;
        var pr_tax_val = 0, pr_tax_rate = 0;
        if (pr_tax !== null && pr_tax != 0) {
            $.each(tax_rates, function () {
                if(this.id == pr_tax){
                    if (this.type == 1) {

                        if (item_tax_method == 0) {
                            pr_tax_val = formatDecimal(((unit_cost) * parseFloat(this.rate)) / (100 + parseFloat(this.rate)));
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                            unit_cost -= pr_tax_val;
                        } else {
                            pr_tax_val = formatDecimal(((unit_cost) * parseFloat(this.rate)) / 100);
                            pr_tax_rate = formatDecimal(this.rate) + '%';
                        }

                    } else if (this.type == 2) {

                        pr_tax_val = parseFloat(this.rate);
                        pr_tax_rate = this.rate;

                    }
                }
            });
        }

        $('#net_cost').text(formatMoney(unit_cost));
        $('#pro_tax').text(formatMoney(pr_tax_val));
    });

    /* -----------------------
     * Edit Row Method 
     ----------------------- */
     $(document).on('click', '#editItem', function () {
        var row = $('#' + $('#row_id').val());
        var item_id = row.attr('data-item-id'), new_pr_tax = $('#ptax').val(), new_pr_tax_rate = {};
        if (new_pr_tax) {
            $.each(tax_rates, function () {
                if (this.id == new_pr_tax) {
                    new_pr_tax_rate = this;
                }
            });
        } 

        poitems[item_id].row.qty = parseFloat($('#pquantity').val()),
        poitems[item_id].row.real_unit_cost = parseFloat($('#pcost').val()),
        poitems[item_id].row.tax_rate = new_pr_tax,
        poitems[item_id].tax_rate = new_pr_tax_rate,
        poitems[item_id].row.discount = $('#pdiscount').val() ? $('#pdiscount').val() : '0',
        poitems[item_id].row.option = $('#poption').val(),
        poitems[item_id].row.expiry = $('#pexpiry').val() ? $('#pexpiry').val() : '';
        localStorage.setItem('poitems', JSON.stringify(poitems));
        $('#prModal').modal('hide');
        loadItems();
        return;
    });

    /* ------------------------------
     * Show manual item addition modal 
     ------------------------------- */
     $(document).on('click', '#addManually', function (e) {
        $('#mModal').appendTo("body").modal('show');
        return false;
    });

    /* --------------------------
     * Edit Row Quantity Method 
     -------------------------- */
     var old_row_qty;
     $(document).on("focus", '.rquantity', function () {
        old_row_qty = $(this).val();
		$(this).select();
    }).on("change", '.rquantity', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_row_qty);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var new_qty = parseFloat($(this).val()),
        item_id = row.attr('data-item-id');
        poitems[item_id].row.qty = new_qty;
        localStorage.setItem('poitems', JSON.stringify(poitems));
        loadItems();
    }).on("keydown",'.rquantity', function (event) {
		// alert(event.which);
		if(event.which == 38)
		{
			var row = $(this).closest('tr');
			if (!is_numeric($(this).val())) {
				$(this).val(old_row_qty);
				bootbox.alert(lang.unexpected_value);
				return;
			}
			var new_qty = parseFloat($(this).val()),
			item_id = row.attr('data-item-id');
			poitems[item_id].row.qty = new_qty;
			localStorage.setItem('poitems', JSON.stringify(poitems));
			loadItems(row.attr('data-item-no'),38,'rquantity');
		}
		if(event.which == 40)
		{
			var row = $(this).closest('tr');
			if (!is_numeric($(this).val())) {
				$(this).val(old_row_qty);
				bootbox.alert(lang.unexpected_value);
				return;
			}
			var new_qty = parseFloat($(this).val()),
			item_id = row.attr('data-item-id');
			poitems[item_id].row.qty = new_qty;
			localStorage.setItem('poitems', JSON.stringify(poitems));
			loadItems(row.attr('data-item-no'),40,'rquantity');
		}
		if(event.which == 13)
		{
			var row = $(this).closest('tr');
			if (!is_numeric($(this).val())) {
				$(this).val(old_row_qty);
				bootbox.alert(lang.unexpected_value);
				return;
			}
			var new_qty = parseFloat($(this).val()),
			item_id = row.attr('data-item-id');
			poitems[item_id].row.qty = new_qty;
			localStorage.setItem('poitems', JSON.stringify(poitems));
			loadItems(row.attr('data-item-no'),13,'rquantity');
		}
	});
    
    /* --------------------------
     * Edit Row Cost Method 
     -------------------------- */
     var old_cost;
     $(document).on("focus", '.rcost', function () {
        old_cost = $(this).val();
    }).on("change", '.rcost', function () {
        var row = $(this).closest('tr');
        if (!is_numeric($(this).val())) {
            $(this).val(old_cost);
            bootbox.alert(lang.unexpected_value);
            return;
        }
        var new_cost = parseFloat($(this).val()),
        item_id = row.attr('data-item-id');
        poitems[item_id].row.cost = new_cost;
        localStorage.setItem('poitems', JSON.stringify(poitems));
        loadItems();
    });
	
	
	var old_row_unit;
	 $(document).on("focus", '.runit', function () {
		// old_row_qty = $(this).val();
	}).on("keydown",'.runit', function (event) {
		// alert(event.which);
		if(event.which == 38)
		{
			var row = $(this).closest('tr');
			loadItems(row.attr('data-item-no'),38,'runit');
		}
		if(event.which == 40)
		{
			var row = $(this).closest('tr');
			loadItems(row.attr('data-item-no'),40,'runit');
		}
		if(event.which == 13)
		{
			var row = $(this).closest('tr');
			loadItems(row.attr('data-item-no'),13,'runit');
		}
	});
	
	
	/* --------------------------
	 * Edit Row Price Method 
	 -------------------------- */
	var old_price;
	 $(document).on("focus", '.realucost', function () {
		old_price = $(this).val();
		$(this).select();
	}).on("change", '.realucost', function () {
		$nilai = $(this).val();
		var row = $(this).closest('tr');
		if (!is_numeric($(this).val())) {
			// $(this).val(old_price);
			$a = $nilai.split("/");
			// alert(eval($(this).val()));
			if ($a.length ==2)
			{
				$(this).val(Math.round(eval($(this).val())));
			}else
			{
				$a = $nilai.split("*");
				if ($a.length ==2)
				{
					$(this).val(Math.round(eval($(this).val())));
				} else 
				{
					$a = $nilai.split("-");
					if ($a.length ==2)
					{
						$(this).val(Math.round(eval($(this).val())));
					} else {
						$a = $nilai.split("+");
						if ($a.length ==2)
						{
							$(this).val(Math.round(eval($(this).val())));
						} else {
							bootbox.alert(lang.unexpected_value);
							return;
						}
					}
				}
			}
		}
		var new_price = parseFloat($(this).val()),
		item_id = row.attr('data-item-id');
		poitems[item_id].row.real_unit_cost = new_price;
		localStorage.setItem('poitems', JSON.stringify(poitems));
		
		poitems = JSON.parse(localStorage.getItem('poitems'));
		loadItems(row.attr('data-item-no'));
	}).on("keydown",'.realucost', function (event) {
		// alert(event.which);
		if(event.which == 38)
		{
			var row = $(this).closest('tr');
			if (!is_numeric($(this).val())) {
				$a = $nilai.split("/");
				if ($a.length ==2)
				{
					$(this).val(eval($(this).val()));
				}else
				{
					$a = $nilai.split("*");
					if ($a.length ==2)
					{
						$(this).val(eval($(this).val()));
					} else 
					{
						$a = $nilai.split("-");
						if ($a.length ==2)
						{
							$(this).val(eval($(this).val()));
						} else {
							$a = $nilai.split("+");
							if ($a.length ==2)
							{
								$(this).val(eval($(this).val()));
							}
						}
					}
				}
				bootbox.alert(lang.unexpected_value);
				return;
			}
			var new_price = parseFloat($(this).val()),
			item_id = row.attr('data-item-id');
			poitems[item_id].row.real_unit_cost = new_price;
			localStorage.setItem('poitems', JSON.stringify(poitems));
			
			poitems = JSON.parse(localStorage.getItem('poitems'));
			loadItems(row.attr('data-item-no'),38,'realucost');
		}
		if(event.which == 40)
		{
			var row = $(this).closest('tr');
			if (!is_numeric($(this).val())) {
				$(this).val(old_price);
				bootbox.alert(lang.unexpected_value);
				return;
			}
			var new_price = parseFloat($(this).val()),
			item_id = row.attr('data-item-id');
			poitems[item_id].row.real_unit_cost = new_price;
			localStorage.setItem('poitems', JSON.stringify(poitems));
			
			poitems = JSON.parse(localStorage.getItem('poitems'));
			loadItems(row.attr('data-item-no'),40,'realucost');
		}
		if(event.which == 13)
		{
			var row = $(this).closest('tr');
			if (!is_numeric($(this).val())) {
				$(this).val(old_price);
				bootbox.alert(lang.unexpected_value);
				return;
			}
			var new_price = parseFloat($(this).val()),
			item_id = row.attr('data-item-id');
			poitems[item_id].row.real_unit_cost = new_price;
			localStorage.setItem('poitems', JSON.stringify(poitems));
			
			poitems = JSON.parse(localStorage.getItem('poitems'));
			loadItems(row.attr('data-item-no'),13,'realucost');
		}
	});
    
    $(document).on("click", '#removeReadonly', function () { 
     $('#posupplier').select2('readonly', false); 
     return false;
 });
    
    
});
/* -----------------------
 * Misc Actions
 ----------------------- */

// hellper function for supplier if no localStorage value
function nsSupplier() {
    $('#posupplier').select2({
        minimumInputLength: 1,
        ajax: {
            url: site.base_url + "suppliers/suggestions",
            dataType: 'json',
            quietMillis: 15,
            data: function (term, page) {
                return {
                    term: term,
                    limit: 10
                };
            },
            results: function (data, page) {
                if (data.results != null) {
                    return {results: data.results};
                } else {
                    return {results: [{id: '', text: 'No Match Found'}]};
                }
            }
        }
    });
}

function loadItems(lokasi_baris,tombol,komponennya) {
    if (localStorage.getItem('poitems')) {
        total = 0;
        count = 1;
        an = 1;
        product_tax = 0;
        invoice_tax = 0;
        product_discount = 0;
        order_discount = 0;
        total_discount = 0;
        $("#poTable tbody").empty();
        poitems = JSON.parse(localStorage.getItem('poitems'));
		nomor_urut = 0;
        $.each(poitems, function () {
			nomor_urut++;

            var item = this;
            var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
            poitems[item_id] = item;

            var product_id = item.row.id;
			// item_type = item.row.type, combo_items = item.combo_items,  item_qty = item.row.qty, item_bqty = item.row.quantity_balance, item_expiry = item.row.expiry, item_tax_method = item.row.tax_method, item_ds = item.row.discount, item_discount = 0, item_option = item.row.option, item_code = item.row.code, item_name = item.row.name.replace(/"/g, "&#034;").replace(/'/g, "&#039;");
			var item_code = item.row.code2;
			var item_cost = item.row.cost, item_qty = item.row.qty;
			var item_name = item.row.name;
            var unit = item.row.unit, unit1 = item.row.unit1, unit2 = item.row.unit2;
            // var supplier = localStorage.getItem('posupplier'), belong = false;

                // if (supplier == item.row.supplier1) {
                    // belong = true;
                // } else
                // if (supplier == item.row.supplier2) {
                    // belong = true;
                // } else
                // if (supplier == item.row.supplier3) {
                    // belong = true;
                // } else
                // if (supplier == item.row.supplier4) {
                    // belong = true;
                // } else
                // if (supplier == item.row.supplier5) {
                    // belong = true;
                // }
                var unit_cost = item.row.real_unit_cost == 0? 0 : item.row.real_unit_cost;

                // var ds = item_ds ? item_ds : '0';
                // if (ds.indexOf("%") !== -1) {
                    // var pds = ds.split("%");
                    // if (!isNaN(pds[0])) {
                        // item_discount = formatDecimal(parseFloat(((unit_cost) * parseFloat(pds[0])) / 100));
                    // } else {
                        // item_discount = formatDecimal(ds);
                    // }
                // } else {
                     // item_discount = parseFloat(ds);
                // }
                // product_discount += parseFloat(item_discount * item_qty);

                // unit_cost = formatDecimal(unit_cost-item_discount);
                // var pr_tax = item.tax_rate;
                // var pr_tax_val = 0, pr_tax_rate = 0;
                // if (site.settings.tax1 == 1) {
                    // if (pr_tax !== false) {
                        // if (pr_tax.type == 1) {

                            // if (item_tax_method == '0') {
                                // pr_tax_val = formatDecimal(((unit_cost) * parseFloat(pr_tax.rate)) / (100 + parseFloat(pr_tax.rate)));
                                // pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                            // } else {
                                // pr_tax_val = formatDecimal(((unit_cost) * parseFloat(pr_tax.rate)) / 100);
                                // pr_tax_rate = formatDecimal(pr_tax.rate) + '%';
                            // }

                        // } else if (pr_tax.type == 2) {

                            // pr_tax_val = parseFloat(pr_tax.rate);
                            // pr_tax_rate = pr_tax.rate;

                        // }
                        // product_tax += pr_tax_val * item_qty;
                    // }
                // }
                // item_cost = item_tax_method == 0 ? formatDecimal(unit_cost-pr_tax_val) : formatDecimal(unit_cost);
                item_cost = formatDecimal(unit_cost);
                // unit_cost = formatDecimal(unit_cost+item_discount);
                // var sel_opt = '';
                // $.each(item.options, function () {
                    // if(this.id == item_option) {
                        // sel_opt = this.name;
                    // }
                // });

            var row_no = (new Date).getTime();
            var newTr = $('<tr id="row_' + row_no + '" class="row_' + item_id + ' bariske_' + nomor_urut +'" data-item-id="' + item_id + '" data-item-no="' + nomor_urut +'"></tr>');
            tr_html = '<td><input name="product[]" type="hidden" class="rcode" value="' + product_id + '"><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><input name="product_option[]" type="hidden" class="roption" value=""><span class="sname" id="name_' + row_no + '">' + item_name + ' (' + item_code + ')</span> ';
            // tr_html = '<td><input name="product[]" type="hidden" class="rcode" value=""><input name="product_name[]" type="hidden" class="rname" value="' + item_name + '"><span class="sname" id="name_' + row_no + '">' + item_name+'</span> ';
			
			// if ($remove_row != 0) {
				// tr_html += '<i class="pull-right fa fa-edit tip edit" id="' + row_no + '" data-item="' + item_id + '" title="Edit" style="cursor:pointer;"></i></td>';
			// } else {
				// tr_html += '</td>';
			// }
            // if (site.settings.product_expiry == 1) {
                // tr_html += '<td><input class="form-control date rexpiry" name="expiry[]" type="text" value="' + item_expiry + '" data-id="' + row_no + '" data-item="' + item_id + '" id="expiry_' + row_no + '"></td>';
            // }
			// if ($remove_row != 0) {
			// } else {
				// tr_html += '<td class="text-center"></td>';
			// }
			// tr_html += '<td><input name="quantity_balance[]" type="hidden" class="rbqty" value="' + item_bqty + '"><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
			tr_html += '<td><input name="quantity_balance[]" type="hidden" class="rbqty" value=""><input class="form-control text-center rquantity" name="quantity[]" type="text" value="' + formatDecimal(item_qty) + '" data-id="' + row_no + '" data-item="' + item_id + '" id="quantity_' + row_no + '" onClick="this.select();"></td>';
            tr_html += '<td><select class="form-control text-center runit" name="unit[]">';
			tr_html += '<option value="' + unit + '">' + unit + '</option>';
			// if (unit1 != "") tr_html += '<option value="' + unit1 + '">' + unit1 + '</option>';
			// if (unit2 != "") tr_html += '<option value="' + unit2 + '">' + unit2 + '</option>';
			tr_html += '</select></td>';
				tr_html += '<td class="text-right"><input class="form-control input-sm text-right rcost" name="net_cost[]" type="hidden" id="cost_' + row_no + '" value="' + item_cost + '"><input class="rucost" name="unit_cost[]" type="hidden" value="' + unit_cost + '"><input class="form-control text-right realucost" name="real_unit_cost[]" type="text" value="' + formatDecimal(unit_cost,0) + '" style="min-width:120px;"><span class="text-right scost hidden" id="scost_' + row_no + '">' + formatMoney(item_cost) + '</span></td>';
            // if ($remove_row != 0) {
				// if (site.settings.product_discount == 1) {
					// tr_html += '<td class="text-right"><input class="form-control input-sm rdiscount" name="product_discount[]" type="hidden" id="discount_' + row_no + '" value="' + item_ds + '"><span class="text-right sdiscount text-danger" id="sdiscount_' + row_no + '">' + formatMoney(0 - (item_discount * item_qty)) + '</span></td>';
				// }
				// if (site.settings.tax1 == 1) {
					// tr_html += '<td class="text-right"><input class="form-control input-sm text-right rproduct_tax" name="product_tax[]" type="hidden" id="product_tax_' + row_no + '" value="' + pr_tax.id + '"><span class="text-right sproduct_tax" id="sproduct_tax_' + row_no + '">' + (pr_tax_rate ? '(' + pr_tax_rate + ')' : '') + ' ' + formatMoney(pr_tax_val * item_qty) + '</span></td>';
				// }
			// } else {
				// tr_html += '<td class="text-center"></td>';
				// tr_html += '<td class="text-center"></td>';
				// tr_html += '<td class="text-right" style="max-width:150px;"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item_qty))) + '</span></td>';
				tr_html += '<td class="text-right" style="max-width:150px;"><span class="text-right ssubtotal" id="subtotal_' + row_no + '">' + formatMoney(((parseFloat(item_cost)) * parseFloat(item_qty))) + '</span></td>';
			
				tr_html += '<td class="text-center"><i class="fa fa-times tip podel" id="' + row_no + '" title="Remove" style="cursor:pointer;"></i></td>';
			
				// tr_html += '<td class="text-center"></td>';
				// tr_html += '<td class="text-center"></td>';
			// }
            newTr.html(tr_html);
            newTr.prependTo("#poTable");
            //total += parseFloat(item_cost * item_qty);
            // total += formatDecimal(((parseFloat(item_cost) + parseFloat(pr_tax_val)) * parseFloat(item_qty)));
            count += parseFloat(item_qty);
            an++;
            // if(!belong) 
                // $('#row_' + row_no).addClass('danger');  
            
        });

        var col = 1;
        if (site.settings.product_expiry == 1) { col++; }
        var tfoot = '<tr id="tfoot" class="tfoot active"><th colspan="'+col+'">Total</th>';
        if (site.settings.product_discount == 1) {
            // tfoot += '<th class="text-right">'+formatMoney(product_discount)+'</th>';
        }
        if (site.settings.tax1 == 1) {
            // tfoot += '<th class="text-right">'+formatMoney(product_tax)+'</th>';
        }
        tfoot += '<th class="text-center">' + formatNumber(parseFloat(count) - 1) + '</th><th></th><th></th><th class="text-right">'+formatMoney(total)+'</th><th class="text-center"><i class="fa fa-trash-o" style="opacity:0.5; filter:alpha(opacity=50);"></i></th></tr>';
        
		// if ($remove_row != 0) {
			$('#poTable tfoot').html(tfoot);
		// }

        // Order level discount calculations        
        if (podiscount = localStorage.getItem('podiscount')) {
            var ds = podiscount;
            if (ds.indexOf("%") !== -1) {
                var pds = ds.split("%");
                if (!isNaN(pds[0])) {
                    order_discount = ((total) * parseFloat(pds[0])) / 100;
                } else {
                    order_discount = parseFloat(ds);
                }
            } else {
                order_discount = parseFloat(ds);
            }
        }

        // Order level tax calculations    
        if (site.settings.tax2 != 0) {
            if (potax2 = localStorage.getItem('potax2')) {
                $.each(tax_rates, function () {
                    if (this.id == potax2) {
                        if (this.type == 2) {
                            invoice_tax = parseFloat(this.rate);
                        }
                        if (this.type == 1) {
                            invoice_tax = parseFloat(((total - order_discount) * this.rate) / 100);
                        }
                    }
                });
            }
        }
        total_discount = parseFloat(order_discount + product_discount);
        // Totals calculations after item addition
        var gtotal = ((total + invoice_tax) - order_discount) + shipping;
        $('#total').text(formatMoney(total));
        $('#titems').text((an-1)+' ('+(parseFloat(count)-1)+')');
        $('#tds').text(formatMoney(order_discount));
        if (site.settings.tax1) {
            $('#ttax1').text(formatMoney(product_tax));
        }
        if (site.settings.tax2 != 0) {
            $('#ttax2').text(formatMoney(invoice_tax));
        }
        $('#gtotal').text(formatMoney(gtotal));
        if (an > site.settings.bc_fix && site.settings.bc_fix != 0) {
            $("html, body").animate({scrollTop: $('#poTable').offset().top - 150}, 500);
            $(window).scrollTop($(window).scrollTop() + 1);
        }
	}
        //audio_success.play();
	if(lokasi_baris != "undefined" || typeof lokasi_baris != undefined){
		next_element(lokasi_baris,tombol,komponennya);
	}
	
}

function next_element(lokasi_baris,tombol,komponennya) {
	// alert(lokasi_baris);
	// alert($(".bariske_"+lokasi_baris).html());
	if (tombol == 40)	$(".bariske_"+lokasi_baris).nextAll(':has(.'+komponennya+'):first').find('.'+komponennya).focus();
	if (tombol == 38)	$(".bariske_"+lokasi_baris).prevAll(':has(.'+komponennya+'):first').find('.'+komponennya).focus();
	if (tombol == 13)	
	{
		// alert(komponennya);
		// if (komponennya == "realuprice") $(".bariske_"+lokasi_baris).find('.rquantity').focus();
		if (komponennya == "realucost") $("#add_item").focus();
		if (komponennya == "rquantity") $(".bariske_"+lokasi_baris).find('.runit').focus();
		// if (komponennya == "runit") $(".bariske_"+lokasi_baris).find('.rdiscount').focus();
		// if (komponennya == "runit") $("#add_item").focus();
		if (komponennya == "runit") $(".bariske_"+lokasi_baris).find('.realucost').focus();
	}
}
/* -----------------------------
 * Add Purchase Iten Function
 * @param {json} item
 * @returns {Boolean}
 ---------------------------- */
 function add_purchase_item(item) {
    if (count == 1) {
        poitems = {};
        if ($('#posupplier').val()) {
            $('#posupplier').select2("readonly", true);
        } else {
            bootbox.alert(lang.select_above);
            item = null;
            return;
        }
    }
    if (item == null) {
        return;
    }
    var item_id = site.settings.item_addition == 1 ? item.item_id : item.id;
    if (poitems[item_id]) {
        poitems[item_id].row.qty = parseFloat(poitems[item_id].row.qty) + 1;
    } else {
        poitems[item_id] = item;
    }
    
    localStorage.setItem('poitems', JSON.stringify(poitems));
    loadItems();
    return true;

}

if (typeof (Storage) === "undefined") {
    $(window).bind('beforeunload', function (e) {
        if (count > 1) {
            var message = "You will loss data!";
            return message;
        }
    });
}
