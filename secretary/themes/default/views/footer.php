<div class="clearfix"></div>
<?= '</div></div></div></div></div>'; ?>
<div class="clearfix"></div>
<footer>
    <p style="text-align:center;width: 100%;height:20px; background: #171717;background-color: #FFBF00; position:relative; bottom:0; margin-bottom: 0px;">&copy; <?= date('Y') . " " . $Settings->site_name; ?>  <?php if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') {
            echo ' - Page rendered in <strong>{elapsed_time}</strong> seconds';
        } ?></p>
</footer>
<?= '</div>'; ?>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>

<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<!-- get business activity list -->
<script type="text/javascript">
	if(localStorage.getItem("business_activity_list") == null)
    {
		$.ajax({
		    type: "GET",
			url: "masterclient/get_business_activity_list",
			dataType: "json",
		    success: function(data){
		    	localStorage.setItem("business_activity_list", JSON.stringify(Object.values(data)));  	
		    }
		});
	}
</script>
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>		

<script type="text/javascript" src="<?= $assets ?>js/bootstrap-notify.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>

<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>

<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Specific Page Vendor -->
<script src="assets/vendor/jquery-appear/jquery.appear.js"></script>
<script src="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="assets/vendor/jquery-easypiechart/jquery.easypiechart.js"></script>
<script src="assets/vendor/flot/jquery.flot.js"></script>
<script src="assets/vendor/flot-tooltip/jquery.flot.tooltip.js"></script>
<script src="assets/vendor/flot/jquery.flot.pie.js"></script>
<script src="assets/vendor/flot/jquery.flot.categories.js"></script>
<script src="assets/vendor/flot/jquery.flot.resize.js"></script>
<script src="assets/vendor/jquery-sparkline/jquery.sparkline.js"></script>
<script src="assets/vendor/raphael/raphael.js"></script>
<script src="assets/vendor/morris/morris.js"></script>
<script src="assets/vendor/gauge/gauge.js"></script>
<script src="assets/vendor/chart/Chart.min.js"></script>
<script src="assets/vendor/snap-svg/snap.svg.js"></script>
<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>

<script src="assets/vendor/bootstrap-wizard/jquery.bootstrap.wizard.js"></script>
<script src="assets/vendor/pnotify/pnotify.custom.js"></script>

<script src="assets/vendor/jqvmap/jquery.vmap.js"></script>
<script src="assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
<script src="assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
<script src="assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>

<script src="assets/vendor/fullcalendar/fullcalendar.js"></script>
<script src="assets/vendor/bootstrap-maxlength/bootstrap-maxlength.js"></script>

<link rel="stylesheet" href="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" />

<script src="assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>
<!-- Theme Base, Components and Settings -->
<script src="assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="assets/javascripts/theme.init.js"></script>
<script src="assets/javascripts/ui-elements/examples.modals.js"></script>


<!-- Examples -->
<script src="assets/javascripts/forms/examples.wizard.js"></script>
<script src="assets/javascripts/forms/examples.client.js?v=44444wqec8dewewew9wewewedfa2082"></script>
<link href="<?= $assets ?>styles/blue.css" rel="stylesheet"/>
<script type="text/javascript">
	//-------------------------------Artemis KYC API------------------------------
    var user_pool_id = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_user_pool_id);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var client_id = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_client_id);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var REGION = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_region);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var username = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_username);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    var password = CryptoJS.AES.decrypt(<?php echo json_encode($artemis_password);?>, "Chanel", {format: CryptoJSAesJson}).toString(CryptoJS.enc.Utf8).replace(/['"]+/g, '');
    //--------------------------------------End Artemis KYC API----------------------------------------
</script>
<script type="text/javascript" src="<?= $assets ?>js/artemis-data.js"></script>

<!-- <script type="text/javascript" charset="UTF-8">
    
    var currentSessionValue = 1;

    $(function() {
	  	var refreshIntervalId = setInterval(checkSession, 20000); //milliseconds(300001)
	
	    function checkSession() {
	         $.ajax({
	            url: "auth/check_session", //Change this URL as per your settings
	            success: function(newVal) {
	            	//console.log(newVal);
	                if (0 > newVal)
	                {
						bootbox.dialog({ 
							closeButton: false,
						    message: "Session is timeout. Please log in again.", 
						    buttons: {
						        btn1: {
						            label : "OK",
						            className : "btn-primary",
						            callback : function() {
						            	window.location = 'logout';
						            }
						        }// btn1
						    } // buttons
						});
						clearInterval(refreshIntervalId);
	                    
	                }
	            }
	         });
	    }
    });

    $(document).ready(function () {
	    $(this).keypress(function (e) {
	    	e.stopPropagation();
	    	$.ajax({
	            url: "auth/change_session", //Change this URL as per your settings
	            success: function(newVal) {
	            	
	            }
	         });
	       // idleTime = 0;
	    });
	});

	(function ($) {
	    var timeout;
	    $(document).on('mousemove', function (event) {
	        if (timeout !== undefined) {
	            window.clearTimeout(timeout);
	        }
	        timeout = window.setTimeout(function () {
	            // trigger the new event on event.target, so that it can bubble appropriately
	            $(event.target).trigger('mousemoveend');
	        }, 100);
	    });
	}(jQuery));

	$(this).on('mousemoveend', function () {
	    $.ajax({
            url: "auth/change_session", //Change this URL as per your settings
            success: function(newVal) {
            	
            }
         });
	});
</script> -->

<?php 
if ($m == 'welcome')
	{
	?>
	<script>
		var notOwner = <?php echo json_encode($Owner);?>;
		var individual = <?php echo json_encode($Individual);?>;
		var client = <?php echo json_encode($Client);?>;
		if(!notOwner && ((!individual && individual == true) || (!individual && individual == null && !client)))
		{
			var config = {
			  type: 'bar',
			  data: {
			    labels: <?php echo json_encode($year);?>,
			    datasets: [
			    {
			      type: 'bar',
			      label: 'Paid',
			      backgroundColor: "grey",
			      data: <?php echo json_encode($paid_bill);?>,
			    }, {
			      type: 'bar',
			      label: 'Unpaid',
			      backgroundColor: "#FFBF00",
			      data: <?php echo json_encode($unpaid_bill);?>,
			    }]
			  },
			  options: {
			    scales: {
			      xAxes: [{
			      	barPercentage: 0.3,
			        stacked: true
			      }],
			      yAxes: [{
			      	barPercentage: 0.3,
			        stacked: true
			      }]
			    }
			  }
			};

			var ctx = document.getElementById("myChart").getContext("2d");
			new Chart(ctx, config);
		}

	</script>
			
	</body>
</html>

<script>
	// Datepicker
	(function(theme, $) {

		theme = theme || {};

		var instanceName = '__datepicker';

		var PluginDatePicker = function($el, opts) {
			return this.initialize($el, opts);
		};

		PluginDatePicker.defaults = {
		};

		PluginDatePicker.prototype = {
			initialize: function($el, opts) {
				if ( $el.data( instanceName ) ) {
					return this;
				}

				this.$el = $el;

				this
					.setVars()
					.setData()
					.setOptions(opts)
					.build();

				return this;
			},

			setVars: function() {
				this.skin = this.$el.data( 'plugin-skin' );

				return this;
			},

			setData: function() {
				this.$el.data(instanceName, this);

				return this;
			},

			setOptions: function(opts) {
				this.options = $.extend( true, {}, PluginDatePicker.defaults, opts );

				return this;
			},

			build: function() {
				this.$el.datepicker( this.options );

				if ( !!this.skin ) {
					this.$el.data('datepicker').picker.addClass( 'datepicker-' + this.skin );
				}

				return this;
			}
		};

		// expose to scope
		$.extend(theme, {
			PluginDatePicker: PluginDatePicker
		});

		// jquery plugin
		$.fn.themePluginDatePicker = function(opts) {
			return this.each(function() {
				var $this = $(this);

				if ($this.data(instanceName)) {
					return $this.data(instanceName);
				} else {
					return new PluginDatePicker($this, opts);
				}

			});
		}

	}).apply(this, [ window.theme, jQuery ]);

	(function( $ ) {
		'use strict';

		if ( $.isFunction($.fn[ 'datepicker' ]) ) {

			$(function() {
				$('[data-plugin-datepicker]').each(function() {
					var $this = $( this ),
						opts = {};

					var pluginOptions = $this.data('plugin-options');
					if (pluginOptions)
						opts = pluginOptions;

					$this.themePluginDatePicker(opts);
				});
			});

		}

	}).apply(this, [ jQuery ]);
	
	$(document).on('change','#promo',function(){
		$("#info_promo").html($("#promo option:selected").data('keterangan'));
	});
	$(document).on('change','#groupJual',function(){
		$("#info_jual").html($("#groupJual option:selected").data('keterangan'));
	});
	
	// MultiSelect
	(function( $ ) {

		'use strict';

		if ( $.isFunction( $.fn[ 'multiselect' ] ) ) {

			$(function() {
				$( '[data-plugin-multiselect]' ).each(function() {

					var $this = $( this ),
						opts = {};

					var pluginOptions = $this.data('plugin-options');
					if (pluginOptions)
						opts = pluginOptions;

					$this.themePluginMultiSelect(opts);

				});
			});

		}

	}).apply( this, [ jQuery ]);
	/*
	Multi Select: Toggle All Button
	*/
	function multiselect_selected($el) {
		var ret = true;
		$('option', $el).each(function(element) {
			if (!!!$(this).prop('selected')) {
				ret = false;
			}
		});
		return ret;
	}

	function multiselect_selectAll($el) {
		$('option', $el).each(function(element) {
			$el.multiselect('select', $(this).val());
		});
	}

	function multiselect_deselectAll($el) {
		$('option', $el).each(function(element) {
			$el.multiselect('deselect', $(this).val());
		});
	}

	function multiselect_toggle($el, $btn) {
		if (multiselect_selected($el)) {
			multiselect_deselectAll($el);
			$btn.text("Select All");
		}
		else {
			multiselect_selectAll($el);
			$btn.text("Deselect All");
		}
	}

	$("#ms_example7-toggle").click(function(e) {
		e.preventDefault();
		multiselect_toggle($("#ms_example7"), $(this));
	});
</script>
<?php
	}
?>	

<script>
	function user_logout() {
		$.ajax({
			type: 'GET',
			url: 'auth/logout',
			cache: 'false',
			success: function(response) {
				localStorage.clear();
				location.reload();
			},
			error: function() {

			}
		});
	}
	/*
	Name: 			Tables / Advanced - Examples
	Written by: 	Okler Themes - (http://www.okler.net)
	Theme Version: 	1.4.1
	*/

	(function( $ ) {

		'use strict';

		var datatableInit = function() {

			$('#datatable-default').dataTable();

		};

		$(function() {
			datatableInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {

		'use strict';

		var datatableClientInit = function() {
			$('#datatable-client').DataTable();
			// $('#datatable-client').DataTable({
			// 	"processing": true,
			// 	"serverSide": true,
			// 	"order": [], //Initial no order.
			// 	"aaSorting": [],
			// 	//"order": [[0, "desc" ]],
			// 	"ajax":{
			// 	      	url :  'masterclient/showClientDO',
			// 	      	type : 'POST',
			// 	      	data: {"service_category": $(".service_category").val(), "user_search": $("#search").val()},
			// 			dataType: 'json',
			// 	    },
			// });

		};

		$(function() {
			datatableClientInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {

		'use strict';

		var datatableTransactionInit = function() {
		    var transaction_table = $('#datatable-transaction').DataTable({
                "columnDefs": [ {
                    "searchable": false,
                    "orderable": false,
                    'type': 'num', 
                    "targets": 0
                }],
                "order": [[5, 'desc']]
            });

		    $('#filter_transaction').on('change', function () {
		        transaction_table.columns(8).search( this.value ).draw();
		    } );
            
            transaction_table.on( 'order.dt search.dt', function () {
                transaction_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                    cell.innerHTML = i+1;
                } );
            } ).draw();

		};

		$(function() {
			datatableTransactionInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {

		'use strict';

		var datatableTransactionInit = function() {

			$('#datatable-letter_confirmation_to_auditor').DataTable({
		        //"pagingType": "full_numbers"
		    });

		};

		$(function() {
			datatableTransactionInit();
		});

	}).apply( this, [ jQuery ]);

	(function( $ ) {

		'use strict';

		var datatableTransactionInit = function() {

			$('#datatable-company_document').DataTable({
		        //"pagingType": "full_numbers"
		    });

		};

		$(function() {
			datatableTransactionInit();
		});

	}).apply( this, [ jQuery ]);
	
</script>
		
<script>
	$(document).keydown(function (e) {
			//a = e.which;
			//ALT+A
			//alert(e.keyCode);
			if(e.keyCode == 45)
			{
				//alert("XX");
				
				var el = $('#modal_add');
				if (el.length) {
					$.magnificPopup.open({
						items: {
							src: el
						},
						type: 'inline'
					});
				}
				//$("#modal_add").modal.show();
			}
			//ALT+S
			if(e.altKey && e.keyCode == 83)
			{
			  alert('l33t!');
			}
			//ALT+E
			if(e.altKey && e.keyCode == 69)
			{
			  alert('l33t!');
			}
			//ALT+D
			if(e.altKey && e.keyCode == 68)
			{
			  alert('l33t!');
			}
		});

	$(document).on('keyup', '.number', function() {
		var x = $(this).val();
		// n.toLocaleString('en-US', {minimumFractionDigits: 2}); 
		$(this).val(x.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		// $(this).val(x.toLocaleString('en-US', {minimumFractionDigits: 2}));
		// x.replace(/,/g,"");
		// $(this).val(commafy(x));
	});	
	$(document).on('focus', '.number', function() {
		$(this).select();
	});		
	$(document).on('focus', '.numberdes', function() {
		$(this).select();
	});		
	$(document).on('keyup', '.numberdes', function() {
		var x = $(this).val();
		// n.toLocaleString('en-US', {minimumFractionDigits: 2}); 
		$(this).val(x.toString().replace(/,/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ","));
		// $(this).val(x.toLocaleString('en-US', {minimumFractionDigits: 2}));
		// x.replace(/,/g,"");
		// $(this).val(commafy(x));
	});		
	$("input.number").bind({
		keydown: function(e) {
			if (e.shiftKey === true ) {
				if (e.which == 9) {
					return true;
				}
				return false;
			}
			if (e.which > 57) {
				return false;
			}
			if (e.which==32) {
				return false;
			}
			return true;
		}
	});
	
</script>
<style>
	.number{
		text-align:right;
	}

	.select2-container {
	    display:block;
	}
	.dataTables_length label{
	    width:100%;
	}

</style>
</body>
</html>
