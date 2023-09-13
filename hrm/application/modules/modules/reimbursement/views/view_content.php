<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<!-- Core CSS file -->
<link rel="stylesheet" href="<?= base_url() ?>node_modules/photoswipe/dist/photoswipe.css"> 

<!-- Skin CSS file (styling of UI - buttons, caption, etc.)
     In the folder of skin CSS file there are also:
     - .png and .svg icons sprite, 
     - preloader.gif (for browsers that do not support CSS animations) -->
<link rel="stylesheet" href="<?= base_url() ?>node_modules/photoswipe/dist/default-skin/default-skin.css"> 

<!-- Core JS file -->
<script src="<?= base_url() ?>node_modules/photoswipe/dist/photoswipe.min.js"></script> 

<!-- UI JS file -->
<script src="<?= base_url() ?>node_modules/photoswipe/dist/photoswipe-ui-default.min.js"></script> 

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<!-- <a class="create_client themeColor_purple" href="reimbursement/apply_reimbursement" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply Reimbursement</a> -->
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
			<div class="form-group">
                <div style="width: 100%;">
                    <div style="float:left;margin-right: 20px;">
                        <label>Employee Name: </label>
                    </div>
                    <div style="width: 30%;float: left;">
                        <div class="input-group" style="width:100%">
                        	<span><?php echo $employee_id; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="float:left;margin-right: 20px;">
                        <label>Client Name: </label>
                    </div>
                    <div style="width: 30%;float: left;">
                        <div class="input-group" style="width:100%">
                        	<span><?php echo $client_name; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div style="width: 100%;">
                    <div style="float:left;margin-right: 20px;">
                        <label>Firm Name: </label>
                    </div>
                    <div style="width: 30%;float: left;">
                        <div class="input-group" style="width:100%">
                        	<span><?php echo $firm_name; ?></span>
                        </div>
                    </div>
                </div>
            </div>

			<div style="text-align: right;">
				<button class="btn btn_purple" onclick="change_status(true)">Approve</button>
				<button class="btn btn-danger" onclick="change_status(false)">Reject</button>
			</div>
			<div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<!-- <div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Interview" class="btn btn_purple">Show All Interview</a>
						<?= form_close();?>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left"></th>
								<th class="text-left">Date</th>
								<th class="text-left">Description</th>
								<th class="text-left">Amount</th>
								<th class="text-center">Receipt</th>
								<th class="text-center">Invoice no. </th>
								<th class="text-center">Date Applied</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($reimbursement_list as $row)
					  			{
					  				echo '<tr>';
					  				echo '<input type="hidden" class="reimbursement_id" value="'. $row->id .'">';
					  				echo '<td><input type="checkbox" name="select_claim"></td>';
					  				echo '<td>'. date('d F Y', strtotime($row->date)) .'</td>';
					  				echo '<td>'. $row->description .'</td>';
					  				// echo '<td>'. $row->firm_name .'</td>';
					  				echo '<td>'. $row->amount .'</td>';
					  				echo '<td><a style="cursor:pointer;" onclick=view_img("'. $row->receipt_img_filename .'")>'. $row->receipt_img_filename .'</a></td>';
					  				// echo '<td align="center";><img src='. base_url() . 'uploads/reimbursement/' . $row->receipt_img_filename .' style=" width:30%; height:30%;"></td>';
					  				echo '<td>'. $row->invoice_no .'</td>';
					  				echo '<td>'. date('d F Y', strtotime($row->date_applied)) .'</td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<hr>

			<div style="text-align: center;">
				<label>Previous History (Approved and Rejected)</label>
			</div>

			<div class="row datatables-header form-inline">
				<div class="col-sm-12 col-md-12">
					<!-- <div class="dataTables_filter" id="datatable-default_filter">
						<input style="width: 45%;" aria-controls="datatable-default" placeholder="Search" id="search"  name="search" value="<?=$_POST['search']?$_POST['search']:'';?>" class="form-control" type="search">
							<input type="submit" class="btn btn_purple" value="Search"/>
							<a href="Interview" class="btn btn_purple">Show All Interview</a>
						<?= form_close();?>
					</div> -->
				</div>
				<div id="buttonclick" style="display:block;padding-top:10px;table-layout: fixed;width:100%">
					<table class="table table-bordered table-striped mb-none" id="datatable_history" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">Date</th>
								<th class="text-left">Description</th>
								<th class="text-left">Amount</th>
								<th class="text-center">Receipt</th>
								<th class="text-center">Invoice no. </th>
								<th class="text-center">Date Applied</th>
								<th class="text-center">Status</th>
								<th class="text-center">Status Updated By</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach($history_list as $row)
					  			{
					  				// echo $i . "<br>";
					  				// $i++;
					  				// echo json_encode($row);
					  				echo '<tr>';
					  				echo '<td>'. date('d F Y', strtotime($row->date)) .'</td>';
					  				echo '<td>'. $row->description .'</td>';
					  				// echo '<td>'. $row->firm_name .'</td>';
					  				echo '<td>'. $row->amount .'</td>';
					  				echo '<td><a style="cursor:pointer;" onclick=view_img("'. $row->receipt_img_filename .'")>'. $row->receipt_img_filename .'</a></td>';
					  				// echo '<td align="center";><img src='. base_url() . 'uploads/reimbursement/' . $row->receipt_img_filename .' style=" width:30%; height:30%;"></td>';
					  				echo '<td>'. $row->invoice_no .'</td>';
					  				echo '<td>'. date('d F Y', strtotime($row->date_applied)) .'</td>';
					  				echo '<td>'. $row->status_id .'</td>';
					  				echo '<td>'. date('d F Y', strtotime($row->date_applied)) .'</td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Root element of PhotoSwipe. Must have class pswp. -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

    <!-- Background of PhotoSwipe. 
         It's a separate element as animating opacity is faster than rgba(). -->
    <div class="pswp__bg"></div>

    <!-- Slides wrapper with overflow:hidden. -->
    <div class="pswp__scroll-wrap">

        <!-- Container that holds slides. 
            PhotoSwipe keeps only 3 of them in the DOM to save memory.
            Don't modify these 3 pswp__item elements, data is added later on. -->
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>

        <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
        <div class="pswp__ui pswp__ui--hidden">

            <div class="pswp__top-bar">

                <!--  Controls are self-explanatory. Order can be changed. -->

                <div class="pswp__counter"></div>

                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                <button class="pswp__button pswp__button--share" title="Share"></button>

                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                <!-- element will get class pswp__preloader--active when preloader is running -->
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>

            <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                <div class="pswp__share-tooltip"></div> 
            </div>

            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>

            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>

            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>

        </div>

    </div>

</div>

<script>
	var base_url = '<?php echo base_url(); ?>';

	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );

	    $('#datatable_history').DataTable( {
	    } );
	} );

	function confirmationOL(id){
		$.post("./offer_letter/sendOL_NewEmployee", { 'id': id }, function(data, status){
			$('.modal-body').empty();
            $('.modal-body').prepend(data);
            $('#exampleModal').modal('show');
            // $('#exampleModal').show();
        });
	}

	function change_status(isApprove){
		var selected_length = $("input[name='select_claim']:checked").length;

  		// console.log();
  		if(selected_length > 0){
	        if(isApprove){
	        	if(confirm("Confirm to APPROVE the selected claim?")){
	        		// console.log('hello');
	        		$.each($("input[name='select_claim']:checked"), function(){  
	        			var reimbursement_id = $(this).parent().parent().find('.reimbursement_id').val();       
			            // console.log(reimbursement_id);

				    	$.post("<?php echo site_url('reimbursement/change_status'); ?>", { reimbursement_id: reimbursement_id, is_approve: true }, function (data, status){
				    			if(status){
				    				window.location = base_url + "reimbursement/index_admin";
				    			}
				    		} 
				    	);
			        });
				}
	        }else{
	        	if(confirm("Confirm to REJECT the selected claim?")){
	        		$.each($("input[name='select_claim']:checked"), function(){  
	        			var reimbursement_id = $(this).parent().parent().find('.reimbursement_id').val();       
			            
			            $.post("<?php echo site_url('reimbursement/change_status'); ?>", { reimbursement_id: reimbursement_id, is_approve: false }, function (data, status){
				    			if(status){
				    				window.location = base_url + "reimbursement/index_admin";
				    			}
				    		} 
				    	);
			        });
				}
	        }
	    }
	    else{
	    	alert("No claim is selected.");
	    }
	}

	function view_img(filename){
		// from photoswipe (http://photoswipe.com/documentation/getting-started.html)
		var pswpElement = document.querySelectorAll('.pswp')[0];

		// build items array
		var items = [
		    {
		        src: base_url + "uploads/reimbursement/" + filename,
		        w: 600,
		        h: 400
		    }
		];

		// define options (if needed)
		var options = {
		    // optionName: 'option value'
		    // for example:
		    index: 0 // start at first slide
		};

		// Initializes and opens PhotoSwipe
		var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, items, options);
		gallery.init();
	};
</script>