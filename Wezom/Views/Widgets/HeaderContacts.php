<li class="dropdown dropdownMenuHidden">
    <a class="dropdownToggle" href="#">
        <i class="fa fa-envelope"></i>
        <?php if ($cContacts): ?>
            <span class="badge"><?php echo $cContacts; ?></span>
        <?php endif ?>
    </a>
    <ul class="dropdownMenu extended pull-right">
        <li class="title">
            <?php if ( $cContacts ): ?>
                <p><?php echo __('У вас новых сообщений', [':count' => $cContacts]); ?></p>
            <?php else: ?>
                <p><?php echo __('Новых сообщений нет'); ?></p>
            <?php endif ?>
        </li>
        <?php foreach ( $contacts as $obj ): ?>
            <li>
                <a href="/wezom/contacts/edit/<?php echo $obj->id; ?>">
                    <span class="photo"></span>
                    <span class="subject">
                        <span class="from"><?php echo $obj->name; ?></span>
                        <span class="time"><?php echo date('d.m.Y H:i', $obj->created_at); ?></span>
                    </span>
                    <span class="text"><?php echo Core\Text::limit_words( strip_tags( $obj->text ), 10 ); ?></span>
                </a>
            </li>
        <?php endforeach ?>
        <li class="footer">
            <a href="/wezom/contacts/index"><?php echo __('Смотреть все сообщения'); ?></a>
        </li>
    </ul>
</li>