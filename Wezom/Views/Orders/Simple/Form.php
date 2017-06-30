<?php echo \Forms\Builder::open(); ?>
    <div class="form-actions" style="display: none;">
        <?php echo \Forms\Form::submit(['name' => 'name', 'value' => __('Отправить'), 'class' => 'submit btn btn-primary pull-right']); ?>
    </div>
    <div class="col-md-12">
        <div class="widget">
            <div class="widgetContent">
                <div class="form-vertical row-border">
                    <div class="form-group">
                        <?php echo \Forms\Builder::bool($obj->status); ?>
                    </div>
                    <?php if ($obj->created_at): ?>
                        <div class="form-group">
                            <label class="control-label"><?php echo __('Дата'); ?></label>
                            <?php echo date( 'd.m.Y H:i:s', $obj->created_at ); ?>
                        </div>
                    <?php endif ?>
                    <div class="form-group">
                        <label class="control-label">IP</label>
                        <?php echo $obj->ip; ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Товар'); ?></label>
                        <?php if (!$item): ?>
                            <span style="color: #ccc; font-style: italic;">( <?php echo __('Удален'); ?> )</span>
                        <?php else: ?>
                            <a href="/wezom/items/edit/<?php echo $obj->catalog_id; ?>" target="_blank"><?php echo $item->name; ?></a>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Номер телефона'); ?></label>
                        <a href="tel:<?php echo $obj->phone; ?>"><?php echo $obj->phone; ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php echo \Forms\Form::close(); ?>