<div class="dd pageList">
    <ol class="dd-list">
        <?php foreach ($result as $key=>$val): ?>
            <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
                <div class="dd3-content" style="padding-left: 0;">
                    <table>
                        <tr>
                            <td valign="middle" class="pagename-column">
                                <div class="clearFix">
                                    <div class="pull-left">
                                        <div class="pull-left">
                                            <div><a class="pageLinkEdit" href="<?php echo '/wezom/seo_files/edit/'.urlencode(base64_encode($val)); ?>"><?php echo $val; ?></a></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="nav-column icon-column" valign="top">
                                <ul class="table-controls">
                                    <li>
                                        <a title="<?php echo __('Управление ссылкой'); ?>" href="javascript:void(0);" class="bs-tooltip dropdownToggle"><i class="fa fa-cog"></i> </a>
                                        <ul class="dropdownMenu pull-right">
                                            <li>
                                                <a title="<?php echo __('Редактировать'); ?>" href="<?php echo '/wezom/seo_files/edit/'.urlencode(base64_encode($val)); ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a title="<?php echo __('Удалить'); ?>" onclick="return confirm('<?php echo __('Это действие необратимо. Продолжить?'); ?>');" href="<?php echo '/wezom/seo_files/delete/'.urlencode(base64_encode($val)); ?>"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить'); ?></a>
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
    <?php echo $pager; ?>
</div>
<span id="parameters" data-table="<?php echo $tablename; ?>"></span>