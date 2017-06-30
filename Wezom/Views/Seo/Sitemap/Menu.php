<?php if ( isset($result[$cur]) AND count($result[$cur]) ): ?>
    <?php if ($cur > 0): ?>
        <ol>
    <?php endif ?>
    <?php foreach ($result[$cur] as $obj): ?>
        <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
            <div class="dd-handle dd3-handle"></div>
            <div class="dd3-content">
                <table>
                    <tr>
                        <td class="column-drag" width="1%"></td>
                        <td class="pagename-column">
                            <div class="clearFix">
                                <div class="pull-left">
                                    <div class="pull-left">
                                        <div><?php echo $obj->name; ?></div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td width="45" valign="top" class="icon-column status-column">
                            <?php echo Core\View::widget(['status' => $obj->status, 'id' => $obj->id], 'StatusListSitemap'); ?>
                        </td>
                    </tr>
                </table>
            </div>
            <?php echo Core\View::tpl(['result' => $result, 'tpl_folder' => $tpl_folder, 'cur' => $obj->id], $tpl_folder.'/Menu'); ?>
        </li>
    <?php endforeach; ?>
    <?php if ($cur > 0): ?>
        </ol>
    <?php endif ?>
<?php endif ?>