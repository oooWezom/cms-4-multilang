<div class="dd pageList">
    <ol class="dd-list">
        <?php foreach ($result as $obj): ?>
            <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
                <div class="dd3-content" style="padding-left: 0;">
                    <table>
                        <tr>
                            <td width="1%" class="checkbox-column">
                                <label><input type="checkbox" /></label>
                            </td>
                            <td valign="top" class="pagename-column">
                                <div class="clearFix">
                                    <div class="pull-left">
                                        <div><a class="pageLinkEdit" href="<?php echo '/wezom/seo_templates/edit/'.$obj->id; ?>"><?php echo $obj->name; ?></a></div>
                                    </div>
                                </div>
                            </td>
                            <td class="nav-column icon-column" valign="top">
                                <ul class="table-controls">
                                    <li>
                                        <a title="<?php echo __('Управление'); ?>" href="javascript:void(0);" class="bs-tooltip dropdownToggle"><i class="fa fa-cog"></i> </a>
                                        <ul class="dropdownMenu pull-right">
                                            <li>
                                                <a title="<?php echo __('Редактировать'); ?>" href="<?php echo '/wezom/seo_templates/edit/'.$obj->id; ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>