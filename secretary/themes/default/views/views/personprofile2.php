						<div class="header_between_all_section">
							<section class="panel">
								<header class="panel-heading">
									<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
										<div class="panel-actions" style="height:80px">
											<a class="edit_client amber" href="<?= base_url();?>personprofile/add" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Person" ><i class="fa fa-plus-circle  amber" style="font-size:16px;height:45px;"></i> Create Person</a>
										</div>
										<h2></h2>
									<?php } ?>
								</header>
								<div class="panel-body">
											<div class="col-md-12">
												<?php if((!$Individual && $Individual == true) || (!$Individual && $Individual == null && !$Client)) {?>
													<div class="col-md-2" style="left: 150px; z-index: 200">
														<select class="form-control" name="type">
															<option value="">All</option>									
															<option value="individual" <?=$type == "individual"?'selected':'';?>>Individual</option>
															<option value="company" <?=$type == "company"?'selected':'';?>>Company</option>
															
														</select>
													</div>
													<div class="col-md-5 person_search_input" style="left: 150px; z-index: 200">
															<input type="text" class="form-control" id="w2-search" name="search" placeholder="Search" value="<?=isset($_POST['search'])?$_POST['search']:'';?>">
													</div>
													<div class="col-md-4 search_group_button" style="left: 150px; z-index: 200">
														<input type="button" id="search" class="btn btn-primary" value="Search"/>
														<a href="javascript:void(0)" id="searchreset" class="btn btn-primary">Show All Person</a>
													</div>
												<?php } ?>

														<div class="col-md-12" id="div_person" style="margin-top:-50px;">
														<table class="table table-bordered table-striped table-condensed mb-none" id="datatable-person" style="width: 100%">
															<thead>
																<tr>
																	<th style="text-align: center;width: 100px">Type</th>
																	<th style="text-align: center;width: 200px">Identification No/ UEN</th>
																	<th style="text-align: center;width: 300px">Name</th>
																	<th style="text-align: center;width: 150px">Phone</th>
																	<th style="text-align: center;width: 200px">Email</th>
																</tr>
															</thead>
															<tbody>
																
															</tbody>
														</table>
														<br/>
													</div>
											</div>
								</div>
							<!-- end: page -->
					</section>
								</div>
			
<style>
	/*#div_person .datatables-header {
		display:none;
	}*/
	#div_person .dataTables_filter {
		display:none;
	}

	#div_person .datatables-header {
		
	    width: 350px;
	    position: relative;
	    /*left: 15px;*/
	    top: 15px;
	    /*bottom: -50px;*/
	    z-index: 100;

	}
</style>

<script type="text/javascript">
	(function( $ ) {

		'use strict';

		// ajax.reload(null, false);

		

		var datatableTransactionInit = function() {
		    var table_person = $('#datatable-person').DataTable({
                "serverSide": false,
            	"processing": false,
            	"paging": true,
            	"scrollX": true,
            	"searching": { "regex": true },
            	"lengthMenu": [
            	    [10, 25, 50, 100, -1],
            	    [10, 25, 50, 100, "All"]
            	],
            	"pageLength": 10,
            	"autoWidth": true,
            	"order": [
            	    [2, "asc"]
            	],
            	initComplete: function() {
            	    var api = this.api();
            	    	$('.dataTables_filter input')
            	        .off('.DT')
            	        .on('keyup.DT', function(e) {
            	            if (e.keyCode == 13) {
            	                api.search(this.value).draw();
            	            }
            	            if (e.which == 8 && this.value == "") {
            	                api.search(this.value).draw();
            	            }
            	        });
            	},

            	// Load data for the table's content from an Ajax source 
            	"ajax": {
            	    "url": "<?=base_url("personprofile/getdata")?>?sign="+Date.now(),
            	    "data": function(d) {
            	        d.datapos = {};
            	        d.datapos.method = "get";
            	        d.<?php echo $this->security->get_csrf_token_name();?> = "<?php echo $this->security->get_csrf_hash();?>";
            	    },
            	    "type": "POST"
            	},

            	"columnDefs": [{
        "targets": [0], //last column
        "orderable": true, //set not orderable
        "width": "10px",
        render: function(data, type, row, meta) {
            return row.field_type;
        }
    }, {
        "defaultContent": "",
        "targets": "_all"
    },{
          		  "targets": [ 1 ], //last column
          		  "orderable": true, //set not orderable
          		  render: function (data, type, row, meta) {

          		         return row.field_type == 'company' ? row.decrypt_register_no : row.decrypt_identification_no;
          		   }  
          		},{
          		  "targets": [ 2 ], //last column
          		  "orderable": true, //set not orderable
          		  render: function (data, type, row, meta) {
          		  	var verification = row.non_verify == 1 ? '<span style="color:red;"data-toggle="tooltip" data-trigger="hover"  data-original-title="Not Verify"> *</span>':''
          		         return (row.field_type == 'company' ? "<a href='personprofile/editCompany/"+encodeURIComponent(encodeURIComponent(row.register_no))+"' data-name='"+row.company_name+"' style='cursor:pointer;'>"+row.company_name+"</a>" : "<a href='personprofile/edit/"+encodeURIComponent(encodeURIComponent(row.identification_no))+"' data-name='"+row.name+"' style='cursor:pointer;'>"+row.name+"</a>")+verification;
          		        }  
          		},{
          		  "targets": [ 3 ], //last column
          		  "orderable": true, //set not orderable
          		  render: function (data, type, row, meta) {
          		         var local_mobile =  row.field_type == 'company' ? row.company_phone_number : row.local_mobile;
          		         return local_mobile == false ? '' : local_mobile
          		        }  
          		},{
          		  "targets": [ 4 ], //last column
          		  "orderable": true, //set not orderable
          		  render: function (data, type, row, meta) {
          		         var email =  row.field_type == 'company' ? row.company_email : row.email;
          		         return email == false ? '' : email
          		        }  
          		}],
            });

		    $('#w2-search').on( 'keyup', function () {
		    table_person
		    .search($('#w2-search').val())
		    table_person
		    .column(0).search( $('[name="type"]').val() )
		    .draw();
		} );

		    $('#search').on( 'click', function () {
		    table_person
		    .search($('#w2-search').val())
		    table_person
		    .column(0).search( $('[name="type"]').val() )
		    .draw();
		} );

		    $('#searchreset').on( 'click', function () {
		    $('#w2-search').val("")
		    $('[name="type"]').val("")
		    table_person
		    .search("")
		    table_person
		    .column(0).search( "" )
		    .draw();
		} );

		    $('[name="type"]').on( 'change', function () {
		    table_person
		    .search($('#w2-search').val())
		    table_person
		    .column(0).search( $('[name="type"]').val() )
		    .draw();
		} );

		};

		$(function() {
			datatableTransactionInit();
		});



	}).apply( this, [ jQuery ]);
</script>