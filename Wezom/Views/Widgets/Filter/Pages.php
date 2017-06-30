<div class="widgetHeader">                      
    <div class="pull-left checkbox-head">
        <label title="Выделить все" class="bs-tooltip"><input type="checkbox"></label>
    </div>

    <?php if ( $open ): ?>
        <div class="toolbar no-padding">
            <div class="btn-group">
                <span data-action="expand-all" class="btn btn-xs">
                    <i class="fa fa-eye"></i>
                    <?php echo __('Развернуть все'); ?>
                </span>
                <span data-action="collapse-all" class="btn btn-xs">
                    <i class="fa fa-eye-slash"></i>
                    <?php echo __('Свернуть все'); ?>
                </span>
            </div>
        </div>
    <?php endif ?>

</div>
