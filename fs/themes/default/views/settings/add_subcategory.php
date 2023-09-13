<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('add_subcategory'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo form_open_multipart("system_settings/add_subcategory", $attrib); ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>

            <div class="form-group">
                <?php echo lang('main_category', 'category'); ?>
                <div class="controls"> <?php
                    $ct[""] = $this->lang->line("select") . " " . $this->lang->line("main_category");
                    foreach ($categories as $category) {
                        $ct[$category->id] = $category->name;
                    }
                    echo form_dropdown('category', $ct, (isset($_POST['category']) ? $_POST['category'] : $parent_id), 'class="form-control select" id="category" required="required"');
                    ?> </div>
            </div>
            <div class="form-group">
                <?php echo lang('category_code', 'code'); ?>
                <div class="controls">
                    <?php echo form_input($code); ?>
                </div>
            </div>

            <div class="form-group">
                <?php echo lang('category_name', 'name'); ?>
                <div class="controls">
                    <?php echo form_input($name); ?>
                </div>
            </div>
            <div class="form-group">
                <?= lang("category_image", "image") ?>
                <input id="image" type="file" name="userfile" data-show-upload="false" data-show-preview="false"
                       class="form-control file">
            </div>

        </div>
        <div class="modal-footer">
            <?php echo form_submit('add_subcategory', lang('add_subcategory'), 'class="btn btn-primary"'); ?>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script type="text/javascript" src="<?=$assets ?&v=30eee4fc8d1b59e4584b0d39edfa2082>js/custom.js"></script>
<?= $modal_js ?>