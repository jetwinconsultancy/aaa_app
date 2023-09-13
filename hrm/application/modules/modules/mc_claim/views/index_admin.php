<link href="<?= base_url() ?>node_modules/jquery-datatables/media/css/jquery.dataTables.min.css" rel="stylesheet">

<script src="<?= base_url() ?>node_modules/jquery-datatables/media/js/jquery.dataTables.js"></script>
<script src="<?= base_url() ?>node_modules/jquery-datatables-bs3/assets/js/datatables.js"></script>

<link rel="stylesheet" href="<?= base_url() ?>node_modules/photoswipe/dist/photoswipe.css"> 
<link rel="stylesheet" href="<?= base_url() ?>node_modules/photoswipe/dist/default-skin/default-skin.css">
<script src="<?= base_url() ?>node_modules/photoswipe/dist/photoswipe.min.js"></script> 
<script src="<?= base_url() ?>node_modules/photoswipe/dist/photoswipe-ui-default.min.js"></script> 

<section class="panel" style="margin-top: 30px;">
	<header class="panel-heading">
		<div class="panel-actions">
			<!-- <a class="create_client themeColor_purple" href="mc_claim/apply_mc" data-toggle="tooltip" data-trigger="hover" style="height:45px;font-weight:bold;" data-original-title="Create Interview" ><i class="fa fa-plus-circle themeColor_purple" style="font-size:16px;height:45px;"></i> Apply MC</a> -->
		</div>
		<h2></h2>
	</header>
	<div class="panel-body">
		<div class="col-md-12">
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
					<div style="text-align: center;">
						<h3>MC</h2>
					</div>
					<table class="table table-bordered table-striped mb-none" id="datatable-default" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">MC no</th>
								<th class="text-left">Employee</th>
								<th class="text-left">Date Start</th>
								<th class="text-left">Date End</th>
								<th class="text-center">Total MC Approved Before (time)</th>
								<th class="text-center">MC Status</th>
								<th class="text-center">MC Action</th>
								<!-- <th class="text-center">Claim Status</th>
								<th class="text-center">Claim Action</th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach($mc_list as $row)
					  			{
					  				echo '<tr>';
					  				echo '<input type="hidden" class="mc_id" value="'. $row->mc_id .'">';
					  				echo '<td><a href="mc_claim/mc_edit/'.$row->mc_id.'">'.$row->mc_no.'</a></td>';
					  				echo '<td>'.$row->employee_name.'</td>';
					  				echo '<td>'.date('d F Y', strtotime($row->mc_start_date)).'</td>';
					  				echo '<td>'.date('d F Y', strtotime($row->mc_end_date)).'</td>';
					  				echo '<td align="center">'.$row->total_mc_approved.'</td>';
					  				echo '<td>'.$row->mc_status.'</td>';
					  				echo '<td align="center">
					  						<button class="btn btn_purple" style="margin:0.5%" onclick="change_mc_status(this, true)">Approve</button>
					  						<button class="btn btn-danger" style="margin:0.5%" onclick="change_mc_status(this, false)">Reject</button>
					  					</td>';

					  				// if($row->mc_status == 'Approved'){
					  				// 	if(!empty($row->claim_id)){
					  				// 		echo '<td align="center"><a href="mc_claim/edit_claim/'.$row->claim_id.'">'.$row->claim_no.'</a></td>';
					  				// 	}else{
					  				// 		echo '<td align="center"><a href="mc_claim/claim_apply/'. $row->mc_id .'" class="btn btn_purple">Apply</a></td>';
					  				// 	}
					  				// }else{
					  				// 	echo '<td align="center"> - </td>';
					  				// }
					  				
					  				// echo '<td>'.$row->receipt_img.'</td>';
					  				// echo '<td>'.$row->claim_status.'</td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>

					<hr/>

					<div style="text-align: center;">
						<h3>Claim</h2>
					</div>
					<table class="table table-bordered table-striped mb-none" id="datatable-claim" style="width:100%">
						<thead>
							<tr style="background-color:white;">
								<th class="text-left">MC no</th>
								<th class="text-left">Employee</th>
								<!-- <th class="text-left">Date Start</th>
								<th class="text-left">Date End</th> -->
								<th class="text-center">MC Status</th>
								<th class="text-center">MC Status Updated by</th>
								<th class="text-center">Invoice no.</th>
								<th class="text-center">Receipt</th>
								<th class="text-center">Claim Status</th>
								<th class="text-center">Claim Action</th>
								<!-- <th class="text-center">Claim Status</th>
								<th class="text-center">Claim Action</th> -->
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach($claim_list as $row)
					  			{
					  				echo '<tr>';
					  				echo '<input type="hidden" class="claim_id" value="'. $row->claim_id .'">';
					  				echo '<td><a href="mc_claim/mc_edit/'.$row->mc_id.'">'.$row->mc_no.'</a></td>';
					  				echo '<td>'.$row->employee_name.'</td>';
					  				echo '<td>'.$row->mc_status.'</td>';
					  				echo '<td>'.date('d F Y', strtotime($row->status_updated_by)).'</td>';
					  				echo '<td>'.$row->claim_invoice_no.'</td>';
					  				echo '<td><a style="cursor:pointer;" onclick=view_img("'. $row->receipt_img .'")>'. $row->receipt_img .'</a></td>';
					  				echo '<td>'.$row->claim_status.'</td>';
					  				echo '<td align="center">
					  						<button class="btn btn_purple" style="margin:0.5%" onclick="change_claim_status(this, true)">Approve</button>
					  						<button class="btn btn-danger" style="margin:0.5%" onclick="change_claim_status(this, false)">Reject</button>
					  					</td>';

					  				// if($row->mc_status == 'Approved'){
					  				// 	if(!empty($row->claim_id)){
					  				// 		echo '<td align="center"><a href="mc_claim/edit_claim/'.$row->claim_id.'">'.$row->claim_no.'</a></td>';
					  				// 	}else{
					  				// 		echo '<td align="center"><a href="mc_claim/claim_apply/'. $row->mc_id .'" class="btn btn_purple">Apply</a></td>';
					  				// 	}
					  				// }else{
					  				// 	echo '<td align="center"> - </td>';
					  				// }
					  				
					  				// echo '<td>'.$row->receipt_img.'</td>';
					  				// echo '<td>'.$row->claim_status.'</td>';
					  				echo '</tr>';
					  			}
							?>
						</tbody>
					</table>
					<hr/>
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

<!-- Modal -->
<!-- <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
    	<div class="modal-header">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          	<span aria-hidden="true">&times;</span>
	    	</button>
          	<h4 class="modal-title">Send Offer Letter</h4>
        </div> -->
      <!-- <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Send Offer Letter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div> -->
     <!--  <div class="modal-body"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn_purple">Send</button>
      </div>
    </div>
  </div>
</div> -->

<script>
	var base_url = '<?php echo base_url(); ?>';
	$(document).ready( function () {
	    $('#datatable-default').DataTable( {
	    } );
	    $('#datatable-claim').DataTable( {
	    } );
	} );

	function change_mc_status(element, isApprove){
		var mc_id = $(element).parent().parent().find('.mc_id').val();
		// console.log("<?php echo site_url('mc_claim/change_mc_status'); ?>");
		if(isApprove){
	        if(confirm("Confirm to APPROVE this mc?")){
				$.post("<?php echo site_url('mc_claim/change_mc_status'); ?>", { mc_id: mc_id, is_approve: 1 }, function (data, status){
		    			if(status){
		    				window.location = base_url + "mc_claim/index";
		    			}
		    		} 
		    	);
			}
		}else{
			if(confirm("Confirm to REJECT this mc?")){
				$.post("<?php echo site_url('mc_claim/change_mc_status'); ?>", { mc_id: mc_id, is_approve: 0 }, function (data, status){
		    			if(status){
		    				window.location = base_url + "mc_claim/index";
		    			}
		    		} 
		    	);
			}
		}
	}

	function change_claim_status(element, isApprove){
		var claim_id = $(element).parent().parent().find('.claim_id').val();

		console.log(claim_id);

		if(isApprove){
	        if(confirm("Confirm to APPROVE this claim?")){
				$.post("<?php echo site_url('mc_claim/change_claim_status'); ?>", { claim_id: claim_id, is_approve: 1 }, function (data, status){
		    			if(status){
		    				window.location = base_url + "mc_claim/index";
		    			}
		    		} 
		    	);
			}
		}else{
			if(confirm("Confirm to REJECT this claim?")){
				$.post("<?php echo site_url('mc_claim/change_claim_status'); ?>", { claim_id: claim_id, is_approve: 0 }, function (data, status){
		    			if(status){
		    				window.location = base_url + "mc_claim/index";
		    			}
		    		} 
		    	);
			}
		}
	}

	function view_img(filename){
		// from photoswipe (http://photoswipe.com/documentation/getting-started.html)
		var pswpElement = document.querySelectorAll('.pswp')[0];

		// build items array
		var items = [
		    {
		        src: base_url + "uploads/claim/" + filename,
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