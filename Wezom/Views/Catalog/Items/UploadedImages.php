<?php if (count($images)): ?>
    <?php foreach($images as $im): ?>
        <?php if (is_file(HOST.Core\HTML::media('images/catalog/medium/'.$im->image, false))): ?>
            <div class="loadedBlock <?= $im->status == 1 ? 'chk' : ''; ?>" data-image="<?=$im->id; ?>">
                <div class="loadedImage">
                    <img src="<?php echo Core\HTML::media('images/catalog/medium/'.$im->image); ?>" />
                </div>
                <div class="loadedControl">
                    <?php if(\Core\User::god() || \Core\User::get_access_for_controller('items') == 'edit'): ?>
                        <div class="loadedCtrl loadedCover">
                            <label>
                                <input id="def-img-<?=$im->id; ?>" type="radio" <?= $im->main == 1 ? 'checked="checked"' : ''; ?> name="default_image" value="<?=$im->id; ?>" />
                                <ins></ins>
                                <span class="btn btn-success" alt="<?php echo __('Обложка'); ?>"><i class="fa fa-bookmark-o"></i></span>
                                <div class="checkCover"></div>
                            </label>
                        </div>
                        <div class="loadedCtrl loadedCheck">
                            <label>
                                <input type="checkbox">
                                <ins></ins>
                                <span class="btn btn-info" alt="<?php echo __('Отметить'); ?>"><i class="fa fa-check"></i></span>
                                <div class="checkInfo"></div>
                            </label>
                        </div>
                    <?php endif; ?>
                    <div class="loadedCtrl loadedView">
                        <button class="btn btn-primary btnImage" alt="<?php echo __('Просмотр'); ?>" href="<?php echo Core\HTML::media('images/catalog/big/'.$im->image); ?>"><i class="fa fa-search-plus"></i></button>
                    </div>
                    <?php if(\Core\User::god() || \Core\User::get_access_for_controller('items') == 'edit'): ?>
                        <div class="loadedCtrl">
                            <button class="btn btn-warning" alt="<?php echo __('Редактировать'); ?>" href="<?php echo \Core\General::crop('catalog', 'small', $im->image, $_SERVER['HTTP_REFERER']); ?>"><i class="fa fa-pencil"></i></button>
                        </div>
                        <div class="loadedCtrl loadedDelete">
                            <button class="btn btn-danger" data-id="<?php echo $im->id; ?>" alt="<?php echo __('Удалить'); ?>"><i class="fa fa-remove"></i></button>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if(\Core\User::god() || \Core\User::get_access_for_controller('items') == 'edit'): ?>
                    <div class="loadedDrag"></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else: ?>
    <div class="alert alert-warning"><?php echo __('Нет загруженных фото!'); ?></div>
<?php endif; ?>
