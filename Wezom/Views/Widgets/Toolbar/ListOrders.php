<?php if( Core\User::caccess() == 'edit' ): ?>
    <div class="toolbar no-padding">
        <div class="btn-group">
            <?php if ( $add ): ?>
                <a href="/wezom/<?php echo Core\Route::controller(); ?>/add" class="btn btn-lg"><i class="fa fa-plus"></i> <?php echo __('Создать'); ?></a>
            <?php endif ?>
            <?php if ( $delete ): ?>
                <a title="<?php echo __('Удалить'); ?>" href="#" class="btn btn-lg text-warning bs-tooltip delete-items"><i class="fa fa-fixed-width">&#xf00d;</i> <span class="hidden-xx"><?php echo __('Удалить'); ?></span></a>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>