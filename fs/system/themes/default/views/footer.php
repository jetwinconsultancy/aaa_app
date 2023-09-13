<div class="clearfix"></div>
<?= '</div></div></div></div></div>'; ?>
<div class="clearfix"></div>
<footer><a href="#" id="toTop" class="blue"
           style="position: fixed; bottom: 30px; right: 30px; font-size: 30px; display: none;"><i
            class="fa fa-chevron-circle-up"></i></a>

    <p style="text-align:center;">&copy; <?= date('Y') . " " . $Settings->site_name; ?>  <?php if ($_SERVER["REMOTE_ADDR"] == '127.0.0.1') {
            echo ' - Page rendered in <strong>{elapsed_time}</strong> seconds';
        } ?></p>
</footer>
<?= '</div>'; ?>
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<div class="modal fade in" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true"></div>
<div id="modal-loading" style="display: none;">
    <div class="blackbg"></div>
    <div class="loader"></div>
</div>
<div id="ajaxCall"><i class="fa fa-spinner fa-pulse"></i></div>
<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<script type="text/javascript">
// var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;
// var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>', New: 'Created', ordered: 'Approved', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>'};

</script>
<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>

				

<!--
<script src="<?= $assets ?>js/magnific-popup/magnific-popup.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
		<script src="<?= $assets ?>js/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="<?= $assets ?>js/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="assetsjs/perfect-scrollbar.min.js"></script>

<!-- Vendor -->
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
		<script src="<?= $assets ?>js/select2/select2.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap-notify.min.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>
		
		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="assets/vendor/jquery-appear/jquery.appear.js"></script>
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
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
		<script src="assets/vendor/snap-svg/snap.svg.js"></script>
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
		
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		
		<script src="assets/vendor/jquery-validation/jquery.validate.js"></script>
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
		
		<script src="assets/vendor/jquery-ui/js/jquery-ui-1.10.4.custom.js"></script>
		<script src="assets/vendor/jquery-ui-touch-punch/jquery.ui.touch-punch.js"></script>
		<script src="assets/vendor/fullcalendar/lib/moment.min.js"></script>
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
		<script src="assets/javascripts/tables/examples.datatables.default.js"></script>
		
		<!-- Examples -->
		<script src="assets/javascripts/forms/examples.wizard.js"></script>
		<script src="assets/javascripts/forms/examples.client.js"></script>
<?= (($m == 'purchases' || $m == 'pembelian') && ($v == 'add' || $v == 'edit' || $v == 'edit3' || $v == 'purchase_by_csv')) ? '<script type="text/javascript" src="' . $assets . 'js/purchases.js"></script>' : ''; ?>
<?= ($m == 'transfers' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/transfers.js"></script>' : ''; ?>
<?= (($m == 'sales' || $m == 'penjualan') && ($v == 'add' || $v == 'add_price' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/sales.js"></script>' : ''; ?>
<?= ($m == 'quotes' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/quotes.js"></script>' : ''; ?>

    <link href="<?= $assets ?>styles/blue.css" rel="stylesheet"/>
<script type="text/javascript" charset="UTF-8">var r_u_sure = "<?=lang('r_u_sure')?>";
    <?=$s2_file_date?>
    // $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
    // $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
    $(window).load(function () {
        $('.mm_<?=$m?>').addClass('active');
        $('.mm_<?=$m?>').find("ul").first().slideToggle();
        $('#<?=$m?>_<?=$v?>').addClass('active');
        $('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
    });
</script>
<?php 
if ($m == 'welcome')
	{
	?>
		
		<style>
			#ChartistCSSAnimation .ct-series.ct-series-a .ct-line {
				fill: none;
				stroke-width: 4px;
				stroke-dasharray: 5px;
				-webkit-animation: dashoffset 1s linear infinite;
				-moz-animation: dashoffset 1s linear infinite;
				animation: dashoffset 1s linear infinite;
			}

			#ChartistCSSAnimation .ct-series.ct-series-b .ct-point {
				-webkit-animation: bouncing-stroke 0.5s ease infinite;
				-moz-animation: bouncing-stroke 0.5s ease infinite;
				animation: bouncing-stroke 0.5s ease infinite;
			}

			#ChartistCSSAnimation .ct-series.ct-series-b .ct-line {
				fill: none;
				stroke-width: 3px;
			}

			#ChartistCSSAnimation .ct-series.ct-series-c .ct-point {
				-webkit-animation: exploding-stroke 1s ease-out infinite;
				-moz-animation: exploding-stroke 1s ease-out infinite;
				animation: exploding-stroke 1s ease-out infinite;
			}

			#ChartistCSSAnimation .ct-series.ct-series-c .ct-line {
				fill: none;
				stroke-width: 2px;
				stroke-dasharray: 40px 3px;
			}

			@-webkit-keyframes dashoffset {
				0% {
					stroke-dashoffset: 0px;
				}

				100% {
					stroke-dashoffset: -20px;
				};
			}

			@-moz-keyframes dashoffset {
				0% {
					stroke-dashoffset: 0px;
				}

				100% {
					stroke-dashoffset: -20px;
				};
			}

			@keyframes dashoffset {
				0% {
					stroke-dashoffset: 0px;
				}

				100% {
					stroke-dashoffset: -20px;
				};
			}

			@-webkit-keyframes bouncing-stroke {
				0% {
					stroke-width: 5px;
				}

				50% {
					stroke-width: 10px;
				}

				100% {
					stroke-width: 5px;
				};
			}

			@-moz-keyframes bouncing-stroke {
				0% {
					stroke-width: 5px;
				}

				50% {
					stroke-width: 10px;
				}

				100% {
					stroke-width: 5px;
				};
			}

			@keyframes bouncing-stroke {
				0% {
					stroke-width: 5px;
				}

				50% {
					stroke-width: 10px;
				}

				100% {
					stroke-width: 5px;
				};
			}

			@-webkit-keyframes exploding-stroke {
				0% {
					stroke-width: 2px;
					opacity: 1;
				}

				100% {
					stroke-width: 20px;
					opacity: 0;
				};
			}

			@-moz-keyframes exploding-stroke {
				0% {
					stroke-width: 2px;
					opacity: 1;
				}

				100% {
					stroke-width: 20px;
					opacity: 0;
				};
			}

			@keyframes exploding-stroke {
				0% {
					stroke-width: 2px;
					opacity: 1;
				}

				100% {
					stroke-width: 20px;
					opacity: 0;
				};
			}
		</style>
		
		<script type="text/javascript"> //Basic Data
						
											var flotBasicData = [{
												data: [
													[0, 170],
													[1, 169],
													[2, 173],
													[3, 188],
													[4, 147],
													[5, 113],
													[6, 128],
													[7, 169],
													[8, 173],
													[9, 128],
													[10, 128]
												],
												label: "Series 1",
												color: "#0088cc"
											}, {
												data: [
													[0, 115],
													[1, 124],
													[2, 114],
													[3, 121],
													[4, 115],
													[5, 83],
													[6, 102],
													[7, 148],
													[8, 147],
													[9, 103],
													[10, 113]
												],
												label: "Series 2",
												color: "#2baab1"
											}, {
												data: [
													[0, 70],
													[1, 69],
													[2, 73],
													[3, 88],
													[4, 47],
													[5, 13],
													[6, 28],
													[7, 69],
													[8, 73],
													[9, 28],
													[10, 28]
												],
												label: "Series 3",
												color: "#734ba9"
											}];
						
											// See: assets/javascripts/ui-elements/examples.charts.js for more settings.
						
										</script>
			
<!-- Gauge Basic-->			
		<script>
		
	(function( $ ) {

		'use strict';

		/*
		Gauge: Basic
		*/
		(function() {
			var target = $('#gaugeBasic'),
				opts = $.extend(true, {}, {
					lines: 12, // The number of lines to draw
					angle: 0.12, // The length of each line
					lineWidth: 0.5, // The line thickness
					pointer: {
						length: 0.7, // The radius of the inner circle
						strokeWidth: 0.05, // The rotation offset
						color: '#444' // Fill color
					},
					limitMax: 'true', // If true, the pointer will not go past the end of the gauge
					colorStart: '#0088CC', // Colors
					colorStop: '#0088CC', // just experiment with them
					strokeColor: '#F1F1F1', // to see which ones work best for you
					generateGradient: true
				}, target.data('plugin-options'));

				var gauge = new Gauge(target.get(0)).setOptions(opts);

			gauge.maxValue = opts.maxValue; // set max gauge value
			gauge.animationSpeed = 32; // set animation speed (32 is default value)
			gauge.set(opts.value); // set actual value
			gauge.setTextField(document.getElementById("gaugeBasicTextfield"));
		})();

		/*
		Gauge: Alternative
		*/
		(function() {
			var target = $('#gaugeAlternative'),
				opts = $.extend(true, {}, {
					lines: 12, // The number of lines to draw
					angle: 0.12, // The length of each line
					lineWidth: 0.5, // The line thickness
					pointer: {
						length: 0.7, // The radius of the inner circle
						strokeWidth: 0.05, // The rotation offset
						color: '#444' // Fill color
					},
					limitMax: 'true', // If true, the pointer will not go past the end of the gauge
					colorStart: '#2BAAB1', // Colors
					colorStop: '#2BAAB1', // just experiment with them
					strokeColor: '#F1F1F1', // to see which ones work best for you
					generateGradient: true
				}, target.data('plugin-options'));

				var gauge = new Gauge(target.get(0)).setOptions(opts);

			gauge.maxValue = opts.maxValue; // set max gauge value
			gauge.animationSpeed = 32; // set animation speed (32 is default value)
			gauge.set(opts.value); // set actual value
			gauge.setTextField(document.getElementById("gaugeAlternativeTextfield"));
		})();
	}).apply( this, [ jQuery ]);
</script>
<script>
	$(document).on('click','.edit_company', function() {
		window.location.href ='company.html';
	});
	
</script>
<script>
	$("#button").click(function(){$("#buttonclick").toggle(); });
	$("#button1").click(function(){$("#buttonclick1").toggle(); });
</script> 
<script>
	//var e = document.getElementById("ddlViewBy");
	///document.getElementById("sandi1").innerHTML = e.options[e.selectedIndex].value;
	//document.getElementById("sandi11").innerHTML = document.getElementById("ViewBy").value;
</script>
	<script>
		$(document).on('click',".fc-event-inner",function() {
			$("#div_assign_by_todolist").show();
			$("#task_todolist").val($(this).data('title'));
			$("#date_todolist").val($(this).data('tgl'));
			$("#assign_by_todolist").val($(this).data('asby'));
			$("#btn_tambah").val("Completed");
			$("#btn_tambah").addClass("btn-info");
			//alert("A");
			/*$.magnificPopup.open({
                items: {
                    src: '#modal_task',

                    type:'inline',
                },
                closeBtnInside: true
         });*/
		});

		$("#btn_tambah").on('click',function () {
			if ($(this).val() =='Save')
			{
				var $calendar = $('#calendar');
				//alert($('#date_todolist').val());
				var parts =$('#date_todolist').val().split('/');
				//please put attention to the month (parts[0]), Javascript counts months from 0:
				// January - 0, February - 1, etc
				var date = new Date(parts[2],parts[1]-1,parts[0]); 
				//var date = new Date();
				// alert(date);
				var d = date.getDate();
				var m = date.getMonth();
				var y = date.getFullYear();
				//alert(d);
					var eventObject = {
						title: $.trim($("#task_todolist").val()), // use the element's text as the event title
						tgl: $.trim($("#date_todolist").val()), // use the element's text as the event title
						assignby: "Admin", // use the element's text as the event title
					};
					var $externalEvent = $('#calendar');
					// retrieve the dropped element's stored Event Object
					//var originalEventObject = $externalEvent.data('eventObject');
					var originalEventObject = eventObject;

					// we need to copy it, so that multiple events don't have a reference to the same object
					var copiedEventObject = $.extend({}, originalEventObject);

					// assign it the date that was reported
					copiedEventObject.start = date;
					copiedEventObject.allDay = true;
					copiedEventObject.id = "112";
					copiedEventObject.className = $externalEvent.attr('data-event-class');

					// render the event on the calendar
					// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
					$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			} else {
				alert("Task Completed");
			}
		});
	</script>
	</body>
</html>
<script>
	/*
			Modal Dismiss
			*/
			$(document).on('click', '.modal-dismiss', function (e) {
				e.preventDefault();
				$.magnificPopup.close();
			});

			/*
			Modal Confirm
			*/
			$(document).on('click', '.modal-confirm', function (e) {
				e.preventDefault();
				$.magnificPopup.close();

				new PNotify({
					title: 'Success!',
					text: 'Modal Confirm Message.',
					type: 'success'
				});
			});
			
			$('.modal-with-form').magnificPopup({
					type: 'inline',
					preloader: false,
					focus: '#name',
					modal: true,

					// When elemened is focused, some mobile browsers in some cases zoom in
					// It looks not nice, so we disable it:
					callbacks: {
						beforeOpen: function() {
							if($(window).width() < 700) {
								this.st.focus = false;
							} else {
								this.st.focus = '#name';
							}
						}
					}
				});
			
			$("#kategori_brg").click(function(e) {
				var $kategori = $("#Kategori").html() + '' +
								'<label class="col-sm-3 control-label"></label>' +
								'<div class="col-sm-9">' +
									'<input type="text" name="nama[]" class="form-control kategori_brg" placeholder="Nama" />' +
								'</div>' +
							'';
				$("#Kategori").html($kategori);
			});
			
</script>

		<style>
			div.token-input-dropdown-facebook {           
			   z-index: 11001 !important;
			}
			.token-input-dropdown, .token-input-dropdown-item2 {          
			   z-index: 11001 !important;
			}
		</style>
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
			
		function no_urut(c_name){
			a = 1;
			$('.'+c_name).each(function(){
				$(this).html(a);
				a++;
			});
			// alert(a);
		}
		no_urut("ads");

			
			$byk_director =2;
			$(document).on('click',"#director_Add",function() {
				$byk_director++;
				$a = $("#body_director_list").html();
				$a += '<tr>';
				$a += '	<td rowspan =4>'+$byk_director+'</td>';
				$a += '	<td><input type="text" class="form-control input-xs" value="Dart"/></td>';
				$a += '	<td><input type="text" class="form-control" value="S8484841Z"/></td>';
				$a += '	<td><input type="text" class="form-control" value="MALAYSIAN"/></td>';
				$a += '	<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2010-01-01"/></td>';
				$a += '	<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2016-01-01"/></td>';
				$a += '	<td rowspan =4>';
				$a += '		<a href="#"><i class="fa fa-pencil"></i></a>';
				$a += '		<a href="#"><i class="fa fa-trash"></i></a>';
				$a += '	</td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '	<td rowspan =3><textarea style="height:100px;">1 Random Road, #01-01 Singapore 123001</textarea></td>';
				$a += '	<td><input type="text" class="form-control" value="01.01.84"/></td>';
				$a += '	<td>';
				$a += '		<select class="form-control">';
				$a += '			<option>Singapore</option>';
				$a += '			<option>Singapore P.R</option>';
				$a += '		</select>';
				$a += '	</td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '	<td>Local Phone </td>';
				$a += '	<td colspan=3><input type="text" class="form-control"  data-plugin-masked-input data-input-mask="(+99) 999-9999" placeholder="(+23) 123-1234"/></td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '	<td>Email</td>';
				$a += '	<td colspan=3><input type="email" class="form-control" value="user@user.com"/></td>';
				$a += '</tr>';
				$("#body_director_list").html($a);
			});
			$byk_officer =2;
			$(document).on('click',"#Officer_Add",function() {
				$byk_officer++;
				$a = $("#body_officer").html();
				$a += '<tr>';
				$a += '	<td rowspan =4>1</td>';
				$a += '	<td><input type="text" class="form-control input-xs" value="Officer Name/Company User"/></td>';
				$a += '	<td><input type="text" class="form-control" value="S8484841Z"/></td>';
				$a += '	<td><input type="text" class="form-control" value="Secretary"/></td>';
				$a += '	<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2010-01-01"/></td>';
				$a += '	<td><input type="text" class="form-control " data-plugin-datepicker data-date-format="dd/mm/yyyy" value="2016-01-01"/></td>';
				$a += '	<td rowspan =4>';
				$a += '		<a href="#"><i class="fa fa-pencil"></i></a>';
				$a += '		<a href="#"><i class="fa fa-trash"></i></a>';
				$a += '	</td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '	<td rowspan =3><textarea style="height:100px;">1 Random Road, #01-01 Singapore 123001</textarea></td>';
				$a += '	<td><input type="text" class="form-control" value="01.01.84"/></td>';
				$a += '	<td colspan=3>';
				$a += '		<select class="form-control" style="width:50%;float:left;">';
				$a += '			<option>Singapore</option>';
				$a += '			<option>MALAYSIA</option>';
				$a += '		</select>';
				$a += '		&nbsp;';
				$a += '		<label><input type="checkbox">Singapore P.R</label>';
				$a += '	</td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '<td>Local Phone </td>';
				$a += '	<td colspan=3><input type="text" class="form-control"  data-plugin-masked-input data-input-mask="(+99) 999-9999" placeholder="(+23) 123-1234"/></td>';
				$a += '</tr>';
				$a += '<tr>';
				$a += '	<td>Email</td>';
				$a += '	<td colspan=3><input type="email" class="form-control" value="user@user.com"/></td>';
				$a += '</tr>';
				$("#body_officer").html($a);
			});
			$byk_service =3;
			$(document).on('click',"#add_service",function() {
				$byk_service++;
				$a = $("#service_body").html();
				$a += '<tr>';
				$a += '	<td><label><input type="checkbox">'+$byk_service+'</label></td>';
				$a += '	<td><input type="text" value="2016-01-01"/></td>';
				$a += '	<td><select class="form-control input-xs">';
				$a += '			<option>Service 1</option>';
				$a += '			<option>Service 2</option>';
				$a += '			<option>Service 3</option>';
				$a += '		</select>';
				$a += '	</td>';
				$a += '	<td><input type="text" value="10.000"/></td>';
				$a += '	<td>none</td>';
				$a += '	<td><a href="#">Edit</a></td>';
				$a += '	<td><a href="#">Payment</a></td>';
				$a += '</tr>';
				$("#service_body").html($a);
			});
			
		</script>
		
	<script>
			$("#btn_cari_client").click(function(){$("#buttonclick").toggle(); });
			$("#btn_tampil_semua_client").click(function(){$("#buttonclick").toggle(); });
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
		$("#btn_add_client").on('click',function(){
			$("#file_add_client").show();
		});	
		function Nilai($a)
		{
			$b = $a.split(".");
			// alert($b[0]);
			$c = $b[0].replace(",","");
			return $c+"."+$b[1];
		}
		$("#calculate_buyback").on('click',function()
		{
			$total_Share = 0;
			$total_amount = 0;
			$total_amountBB = 0;
			$total_shareBB = 0;
			$harga_share_baru = 0;
				$a = Nilai($("#Allotment_Share").val());
				$a1 = Nilai($("#Allotment_Share_amount").val());
				// alert(parseFloat(Nilai($a1)));
				$persen = Math.round(parseFloat($a1) / parseFloat($a) * 100) / 100 ;
			$(".share_bb").each(function(index,item) {
				$id = $(this).data('id');
				$b = Nilai($('#shareori_bb'+$id).val());
				// alert($b)
				$c = Nilai($('#amountori_bb'+$id).val());
				$total_Share = parseFloat($total_Share) + parseFloat($b);
				$total_amount = parseFloat($total_amount) + parseFloat($c);
				$total_amountBB = 0;
				$total_shareBB = 0;
				// alert($b);
				$(item).val(parseFloat($a) /2);
				$('#amount_bb'+$id).val(parseFloat($a1) /2);
				// $("#amount_bb"+$id).val($a1*$c);
			});
			$("#total_Share").html($total_Share);
			$total_shareBB = parseFloat($a) * parseFloat($total_Share) /100;
			$("#t_s_bb").html($total_shareBB);
			$("#total_shareBB").html($total_shareBB);
			$("#total_amount").html($total_amount);
			$("#total_amountBB").html($total_amountBB);
		});	
		
function commafy(num){
  var parts = (''+num).split("."), s = parts[0], i = L = s.length, o = '', c;
 	while(i--){ o = (i==0 ? '' : ((L-i)%3 ? '' : ',')) + s.charAt(i) + o }
  return o + (parts[1] ? '.' + parts[1] : '');
};
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
		$(document).on('click','.delete_row',function(){
			$(this).parent().parent().remove();
			hitung_baris($(this).data('element'));
		});
		function hitung_baris($clas)
		{
			$i = 1;
			console.log($clas);
			$("." + $clas).each(function(){
				console.log($(this));
				$(this).html($i);
				$i++;
			});
		}
	</script>
	<style>
		.number{
			text-align:right;
		}
	</style>
</body>
</html>
