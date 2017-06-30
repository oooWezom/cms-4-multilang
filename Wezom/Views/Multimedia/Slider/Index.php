<div class="dd pageList" id="myNest" data-depth="1">
    <ol class="dd-list">
        <?php foreach ($result as $obj): ?>
            <li class="dd-item dd3-item" data-id="<?php echo $obj->id; ?>">
                <div title="<?php echo __('Переместить строку'); ?>" class="dd-handle dd3-handle">Drag</div>
                <div class="dd3-content">
                    <table>
                        <tr>
                            <td class="column-drag" width="1%"></td>
                            <td width="1%" class="checkbox-column">
                                <label><input type="checkbox" /></label>
                            </td>
                            <td valign="<?php echo $obj->url ? 'top' : 'middle'; ?>" class="pagename-column">
                                <div class="clearFix">
                                    <div class="pull-left">
                                        <div class="pull-left">
                                            <div>
                                                <?php if (is_file(HOST.Core\HTML::media('images/slider/small/'.$obj->image, false))): ?>
                                                    <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">
                                                        <img src="<?php echo Core\HTML::media('images/slider/small/'.$obj->image); ?>" alt="<?php echo $obj->name; ?>" width="50">
                                                    </a>
                                                <?php else: ?>
                                                    ----
                                                <?php endif ?>
                                            </div>
                                            <?php if ($obj->url): ?>
                                                <div class="size11 nowrap">(<span class="gray">URL:</span> <?php echo $obj->url; ?>)</div>
                                            <?php endif ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="hidden-xs" valign="middle">
                                <?php if ($obj->name): ?>
                                    <a href="/wezom/<?php echo Core\Route::controller(); ?>/edit/<?php echo $obj->id; ?>">
                                        <?php echo $obj->name; ?>
                                    </a>
                                <?php else: ?>
                                    ----
                                <?php endif ?>
                            </td>
                            <td width="45" valign="top" class="icon-column status-column">
                                <?php echo Core\View::widget(['status' => $obj->status, 'id' => $obj->id], 'StatusList'); ?>
                            </td> 
                            <td class="nav-column icon-column" valign="top">
                                <ul class="table-controls">
                                    <li>
                                        <a title="<?php echo __('Управление'); ?>" href="javascript:void(0);" class="bs-tooltip dropdownToggle"><i class="fa fa-cog"></i> </a>
                                        <ul class="dropdownMenu pull-right">
                                            <li>
                                                <a title="<?php echo __('Редактировать'); ?>" href="<?php echo '/wezom/'.Core\Route::controller().'/edit/'.$obj->id; ?>"><i class="fa fa-pencil"></i> <?php echo __('Редактировать'); ?></a>
                                            </li>
                                            <li class="divider"></li>
                                            <li>
                                                <a title="<?php echo __('Удалить'); ?>" onclick="return confirm('<?php echo __('Это действие необратимо. Продолжить?'); ?>');" href="<?php echo '/wezom/'.Core\Route::controller().'/delete/'.$obj->id; ?>"><i class="fa fa-trash-o text-danger"></i> <?php echo __('Удалить'); ?></a>
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
<input type="hidden" id="myNestJson">