<div class="rowSection clearFix row-bg">
    <?php if( \Core\User::get_access_for_controller('orders') != 'no' || \Core\User::god() ): ?>
        <div class="col-sm-6 col-md-3">
            <div class="statbox widget box box-shadow">
                <div class="widgetContent">
                    <div class="visual cyan">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="title">
                        <?php echo __('Заказы'); ?>
                    </div>
                    <div class="value">
                        <?php echo $count_orders; ?>
                    </div>
                    <a href="/wezom/orders/index" class="more"><?php echo __('Подробнее'); ?> <i class="pull-right fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if( \Core\User::get_access_for_controller('comments') != 'no' || \Core\User::god() ): ?>
        <div class="col-sm-6 col-md-3">
            <div class="statbox widget box box-shadow">
                <div class="widgetContent">
                    <div class="visual green">
                        <i class="fa fa-comments-o"></i>
                    </div>
                    <div class="title">
                        <?php echo __('Отзывы к товарам'); ?>
                    </div>
                    <div class="value">
                        <?php echo $count_comments; ?>
                    </div>
                    <a href="/wezom/comments/index" class="more"><?php echo __('Подробнее'); ?> <i class="pull-right fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if( \Core\User::get_access_for_controller('items') != 'no' || \Core\User::god() ): ?>
        <div class="col-sm-6 col-md-3">
            <div class="statbox widget box box-shadow">
                <div class="widgetContent">
                    <div class="visual yellow">
                        <i class="pull-right fa fa-fixed-width">&#xf11b;</i>
                    </div>
                    <div class="title">
                        <?php echo __('Товары'); ?>
                    </div>
                    <div class="value">
                        <?php echo $count_catalog; ?>
                    </div>
                    <a href="/wezom/items/index" class="more"><?php echo __('Подробнее'); ?> <i class="pull-right fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if( \Core\User::get_access_for_controller('users') != 'no' || \Core\User::god() ): ?>
        <div class="col-sm-6 col-md-3">
            <div class="statbox widget box box-shadow">
                <div class="widgetContent">
                    <div class="visual black">
                        <i class="fa fa-users"></i>
                    </div>
                    <div class="title">
                        <?php echo __('Пользователи'); ?>
                    </div>
                    <div class="value">
                        <?php echo $count_users; ?>
                    </div>
                    <a href="/wezom/users/index" class="more"><?php echo __('Подробнее'); ?> <i class="pull-right fa fa-angle-right"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php echo \Core\Widgets::get('Index_Visitors'); ?>

<?php if( \Core\User::get_access_for_controller('orders') != 'no' || \Core\User::god() ): ?>
    <?php echo \Core\Widgets::get('Index_Orders'); ?>
<?php endif; ?>

<?php if(\Core\User::god()): ?>
    <div class="rowSection clearFix">
        <div class="col-md-6">
            <div class="widget">
                <?php echo \Core\Widgets::get('Index_Log'); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget">
                <?php echo \Core\Widgets::get('Index_News'); ?>
            </div>
        </div>
    </div>
    <?php echo \Core\Widgets::get('Index_Readme'); ?>
<?php else: ?>
    <div class="rowSection clearFix">
        <div class="col-md-6">
            <div class="widget">
                <?php echo \Core\Widgets::get('Index_News'); ?>
            </div>
        </div>
    </div>
<?php endif; ?>