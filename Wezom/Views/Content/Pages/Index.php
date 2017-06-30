<div class="dd pageList" id="myNest">
    <ol class="dd-list">
        <?php echo Core\View::tpl(['result' => $result, 'tpl_folder' => $tpl_folder, 'cur' => 0], $tpl_folder.'/Menu'); ?>
    </ol>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>
<input type="hidden" id="myNestJson">