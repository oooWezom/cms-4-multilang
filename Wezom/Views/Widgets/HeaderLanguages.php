<li class="dropdown dropdownMenuHidden">
    <a class="dropdownToggle" href="#">
        <?php echo \Core\Config::get('i18n.languages.'.\Core\Cookie::get('backend_lang').'.name'); ?>
        <i class="fa fa-caret-down small"></i>
    </a>
    <ul class="dropdownMenu pull-right">
        <?php foreach( \Core\Config::get('i18n.languages') AS $key => $lang ): ?>
            <li>
                <a title="<?php echo $lang['name']; ?>" href="#" data-lang="<?php echo $lang['alias']; ?>" class="changeBackendLanguage">
                    <?php echo $lang['name']; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</li>