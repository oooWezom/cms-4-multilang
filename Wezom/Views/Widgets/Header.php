<header class="navbar navbarPos">
    <div class="clearFix navbarIndent">
        <a class="fontLogo" href="/wezom/index">
            <span class="top fontLogoName">wezom</span>
            <span class="top fontLogoTitle">
                <span class="fontLogoLabel">cms</span>
                <span class="fontLogoVersion">4.0</span>
            </span>
        </a>
        <a title="Переключить навигацию" class="tip toggleSidebar" href="#">
            <i class="fa fa-bars"></i>
        </a>
        <ul class="navbarNav">
            <?php if( \Core\User::god() && is_dir((HOST.'/Media/cache')) && count(scandir(HOST.'/Media/cache')) > 2 ): ?>
                <li>
                    <a title="<?php echo __('Удалить минифифицированные стили и скрипты'); ?>" href="#" class="clearMinifyCache">
                        <i class="fa fa-trash">&#xf014;</i>
                    </a>
                </li>
            <?php endif; ?>
            <?php echo Core\Widgets::get('HeaderNew'); ?>
            <?php $access = \Core\User::access(); ?>
            <?php if( \Core\User::god() || (isset($access['contacts']) && $access['contacts'] != 'no') ): ?>
                <?php echo Core\Widgets::get('HeaderContacts'); ?>
            <?php endif; ?>
            <?php echo Core\Widgets::get('HeaderLanguages'); ?>
            <li class="dropdown dropdownMenuHidden">
                <a class="dropdownToggle" href="#">
                    <i class="fa fa-male"></i>
                    <span class="navText username"><?php echo Core\User::info()->name; ?></span>
                    <i class="fa fa-caret-down small"></i>
                </a>
                <ul class="dropdownMenu pull-right">
                    <li>
                        <a href="/wezom/auth/edit">
                            <i class="fa fa-user"></i>
                            <?php echo __('Профиль'); ?>
                        </a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="/wezom/auth/logout">
                            <i class="fa fa-key"></i>
                            <?php echo __('Выйти'); ?>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</header>