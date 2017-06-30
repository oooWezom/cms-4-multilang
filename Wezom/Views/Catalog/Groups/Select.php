<?php if ( count($result[ $currentParent ]) ): ?>
    <?php foreach ( $result[ $currentParent ] as $obj ): ?>
        <?php if ( $currentID != $obj->id ): ?>
            <?php $countChildItems = Core\QB\DB::select([Core\QB\DB::expr('COUNT(id)'), 'count'])->from('catalog')->where('parent_id', '=', $obj->id)->count_all(); ?>
            <?php if ( $countChildItems ): ?>
                <optgroup label="<?php echo $space.$obj->name; ?>"></optgroup>
            <?php else: ?>
                <option value="<?php echo $obj->id; ?>" <?php echo $parentID == $obj->id ? 'selected' : ''; ?>><?php echo $space.$obj->name; ?></option>
            <?php endif ?>
            <?php echo Core\View::tpl( [
                        'result' => $result,
                        'currentParent' => $obj->id,
                        'space' => $space.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                        'filename' => $filename,
                        'currentID' => $currentID,
                        'parentID' => $parentID,
                        'parentAlias' => $parentAlias,
            ], $filename ); ?>
        <?php endif ?>
    <?php endforeach ?>
<?php endif ?>