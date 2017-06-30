<?php if( Core\User::caccess() == 'edit' ): ?>
    <div class="toolbar no-padding">
        <div class="btn-group">
            <?php if ( $add ): ?>
                <a href="/wezom/<?php echo Core\Route::controller(); ?>/add" class="btn btn-lg"><i class="fa fa-plus"></i> <?php echo __('Создать'); ?></a>
            <?php endif ?>
            <?php if ( $addLink ): ?>
                <a href="<?php echo $addLink; ?>" class="btn btn-lg"><i class="fa fa-plus"></i> <?php echo __('Создать'); ?></a>
            <?php endif ?>
        </div>
    </div>
<?php endif ?>