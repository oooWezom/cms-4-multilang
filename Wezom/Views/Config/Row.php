<div class="form-group">
    <?php $attributes = [
        'name' => $obj->group.'-'.$obj->key,
        'rows' => 5,
        'value' => $obj->zna,
    ]; ?>
    <?php if($obj->valid): ?>
        <?php $attributes['class'] = 'valid'; ?>
    <?php endif; ?>

    <?php if($obj->type == 'textarea'): ?>
        <?php $attributes['rows'] = 5; ?>
        <?php echo \Forms\Builder::textarea($attributes, __($obj->name)); ?>
    <?php elseif($obj->type == 'tiny'): ?>
        <?php echo \Forms\Builder::tiny($attributes, __($obj->name)); ?>
    <?php elseif($obj->type == 'select'): ?>
        <?php $values = json_decode($obj->values, true); ?>
        <?php echo \Forms\Builder::select(\Core\Support::selectData($values, 'value', 'key'), $obj->zna, $attributes, __($obj->name)); ?>
    <?php elseif($obj->type == 'radio'): ?>
        <?php echo \Forms\Form::label(__($obj->name)); ?>
        <div class="clear"></div>
        <?php $values = json_decode($obj->values, true); ?>
        <?php foreach($values AS $v): ?>
            <?php $attr = $attributes; ?>
            <label class="checkerWrap-inline radioWrap col-md-4" style="margin-right: 0;">
                <?php $attr['value'] = $v['value']; ?>
                <?php echo \Forms\Builder::radio($obj->zna == $v['value'] ? true : false, $attr); ?>
                <?php echo $v['key']; ?>
            </label>
        <?php endforeach; ?>
    <?php elseif($obj->type == 'password'): ?>
        <?php echo \Forms\Form::label($obj->name, ['for' => 'field_'.$obj->id]); ?>
        <div class="clear"></div>
        <?php $attributes['id'] = 'field_'.$obj->id; ?>
        <?php $attributes['autocomplete'] = 'off'; ?>
        <?php echo \Forms\Builder::password($attributes); ?>
        <span class="input-group-btn" style="vertical-align: bottom;">
            <?php echo \Forms\Form::button(__('Показать'), [
                'type' => 'button',
                'class' => 'btn showPassword',
            ]); ?>
        </span>
    <?php else: ?>
        <?php echo \Forms\Builder::input($attributes, __($obj->name)); ?>
    <?php endif; ?>
</div>