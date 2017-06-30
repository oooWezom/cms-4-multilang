<?php if ( count($result[ $currentParent ]) ): ?>
    <?php foreach ( $result[ $currentParent ] as $obj ): ?>
        <option value="<?php echo $obj->id; ?>" <?php echo $parentID == $obj->id ? 'selected' : ''; ?>><?php echo $space.$obj->name; ?></option>
        <?php echo Core\View::tpl( [
                    'result' => $result,
                    'currentParent' => $obj->id,
                    'space' => $space.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                    'filename' => $filename,
                    'parentID' => $parentID,
                    'parentAlias' => $parentAlias,
        ], $filename ); ?>
    <?php endforeach ?>    
<?php endif ?>