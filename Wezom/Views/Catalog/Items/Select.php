<?php if ( count($result[ $currentParent ]) ): ?>
    <?php foreach ( $result[ $currentParent ] as $obj ): ?>
        <?php $countChildGroups = Core\QB\DB::select([Core\QB\DB::expr('COUNT(id)'), 'count'])->from('catalog_tree')->where('parent_id', '=', $obj->id)->count_all(); ?>
        <?php if ( $countChildGroups ): ?>
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
        ], $filename ); ?>
    <?php endforeach ?>
<?php endif ?>