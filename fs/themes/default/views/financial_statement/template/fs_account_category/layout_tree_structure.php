<link rel="stylesheet" href="<?= $assets ?>js/financial_statement/css/layout_tree_structure.css" />

<link rel="stylesheet" href="assets/vendor/vakata-jstree-3.3.8-0/dist/themes/default/style.min.css" />
<script src="assets/vendor/vakata-jstree-3.3.8-0/dist/jstree.min.js"></script>

<script src="assets/vendor/jstree-grid-master/jstreegrid.js"></script>

<style type="text/css">
  .vakata-context { 
     z-index:999 !important; 
  }

  .acenter
  {
    /*text-align: right;*/
    color: green;
  }
</style>

<!-- <a href="ms-excel:ofe|u|http:...xls">Open in Excel</a> -->

<div class="wrapper">
  <div id="sidebar" class="box" style="min-width:20%; height:77vh; overflow-y: scroll;">
      <div style="display: inline-block;">
        <h4>Account to be classified</h4>
      </div>
      <div style="display: inline-block; float:right; padding: 10px;">
        <a id="CreateAccount" style="cursor: pointer"><span class="glyphicon glyphicon-plus fa-fw"></span> Create Account</a>
      </div>

      <hr class="divider" />

      <div><input type="text" id="uncategorized_account_search" class="form-control" placeholder="Keyword Search"></input></div>
      <br>
      <div id="Uncategoried_Treeview"></div>
  </div>
  <div class="handler"></div>
  <div id="main" class="box" style="min-width:35%; height:77vh; overflow-y: scroll;">
    <div style="display: inline-block;">
        <h4>Classified Account</h4>
      </div>
      <!-- <div style="display: inline-block; float:right; padding: 10px;">
        <a id="CreateMainCategory" style="cursor: pointer"><span class="glyphicon glyphicon-plus fa-fw"></span> Create Main Category</a>
      </div> -->

      <hr class="divider" />
      <div><input type="text" id="categorized_account_search" class="form-control" placeholder="Keyword Search"></input></div>
      <br>
      <div id="Categoried_Treeview" style="padding-right: 20px;"></div>
  </div>
</div>

<div class="loading" id="loadingSaveTree" style="display: none;">Loading&#8230;</div>

<script type="text/javascript">
  var main_account_code_list = <?php echo json_encode($main_account_code_list); ?>;
  main_account_code_list = main_account_code_list.map(x => x['account_code']);

  // console.log(main_account_code_list);
</script>
<script src="themes/default/assets/js/financial_statement/layout_tree_structure.js" charset="utf-8"></script>

