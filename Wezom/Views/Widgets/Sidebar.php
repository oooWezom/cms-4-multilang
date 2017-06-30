<?php $arr = explode('?', $_SERVER['REQUEST_URI']); ?>
<?php $url = $arr[0]; ?>
<?php $url = (int) \Core\Route::param('page') > 1 ? str_replace('/page/'.\Core\Route::param('page'), '', $url) : $url; ?>
<?php $arr = explode('/', trim($url, '/')); ?>
<div class="sideBar sideBarFixed sideBarSize">
    <div class="mCustomScrollbar fluid sideBarScrollSize">
        <div class="sideBarContent">
            <ul class="navLeft">
                <?php foreach ( $result[0] as $obj ): ?>
                    <?php if ( isset( $result[ $obj->id ] ) AND count( $result[ $obj->id ] ) ): ?>
                        <?php $check = false; ?>
                        <?php foreach ( $result[ $obj->id ] as $_obj ): ?>
                            <?php if( $arr[1].'/'.$arr[2] == $_obj->link || $arr[1].'/index' == $_obj->link || $arr[1] == $_obj->link ): ?>
                                <?php $check = true; ?>
                            <?php endif; ?>
                        <?php endforeach ?>
                        <li class="<?php echo $check ? 'subMenuOpen' : NULL; ?>">
                            <a class="subToggle <?php echo $check ? 'cur' : NULL; ?>" href="/wezom/<?php echo $obj->link; ?>">
                                <i class="fa <?php echo $obj->icon; ?>"></i>
                                <?php echo __($obj->name); ?>
                                <?php if( isset($counts) && is_array($counts) && array_key_exists($obj->count, $counts) && (int) $counts[$obj->count] > 0 ): ?>
                                    <span class="pull-right"><?php echo (int) $counts[$obj->count]; ?></span>
                                <?php endif; ?>
                                <i class="fa fa-angle-right arrow"></i>
                            </a>
                            <ul class="subMenu" <?php echo $check ? 'style="height:'.(count($result[$obj->id]) * 29).'px;"' : NULL; ?>>
                                <?php foreach ( $result[ $obj->id ] as $_obj ): ?>
                                    <?php $check = false; ?>
                                    <?php if( $arr[1].'/'.$arr[2] == $_obj->link || (!isset($arr[2]) && $arr[1].'/index' == $_obj->link) || ($arr[2] == 'edit' && $arr[1].'/index' == $_obj->link) ): ?>
                                        <?php $check = true; ?>
                                    <?php endif; ?>
                                    <li>
                                        <a class="<?php echo $check ? 'cur' : ''; ?>" href="/wezom/<?php echo $_obj->link; ?>">
                                            <i class="fa fa-angle-right"></i>
                                            <?php echo __($_obj->name); ?>
                                            <?php if( isset($counts) && is_array($counts) && array_key_exists($_obj->count, $counts) && (int) $counts[$_obj->count] > 0 ): ?>
                                                <span class="pull-right"><?php echo (int) $counts[$_obj->count]; ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <?php $check = false; ?>
                        <?php if( $arr[1].'/'.$arr[2] == $obj->link || $arr[1].'/index' == $obj->link || $arr[1] == $obj->link ): ?>
                            <?php $check = true; ?>
                        <?php endif; ?>
                        <li>
                            <a class="<?php echo $check ? 'cur' : NULL; ?>" href="/wezom/<?php echo $obj->link; ?>">
                                <i class="fa <?php echo $obj->icon; ?>"></i>
                                <?php echo __($obj->name); ?>
                                <?php if( isset($counts) && is_array($counts) && array_key_exists($obj->count, $counts) && (int) $counts[$obj->count] > 0 ): ?>
                                    <span class="pull-right"><?php echo (int) $counts[$obj->count]; ?></span>
                                <?php endif; ?>
                                <i class="fa fa-angle-right arrow"></i>
                            </a>
                        </li>
                    <?php endif ?>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <div class="divider resizeable resizeablePos"></div>
</div>