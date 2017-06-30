<!DOCTYPE html>
<html>
<head>
    <?php echo Core\Widgets::get('Head', $_seo); ?>
</head>
<body>
    <?php echo Core\Widgets::get('Header'); ?>
    <div class="container fixedHeader">
        <?php echo Core\Widgets::get('Sidebar'); ?>
        <div class="contentWrap">
            <div class="contentWrapMar">
                <?php echo $_breadcrumbs; ?>
                <div class="page-header clearFix">
                    <div class="page-title">
                        <h3>
                            <?php echo Core\Arr::get($_seo, 'h1'); ?>
                        </h3>
                        <span>
                            <?php echo __('Здравствуйте!', [':name' => Core\User::info()->name]); ?>
                        </span>
                    </div>
                    <?php echo $_toolbar; ?>
                </div>
                <div class="rowSection">
                    <div class="col-md-12">
                        <div class="widget checkbox-wrap">
                            <?php echo $_filter; ?>
                            <div class="widgetContent">
                                <?php echo $_content; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="text-align: right;"><?php echo $_toolbar; ?></div>
                <?php echo Core\Widgets::get('Footer'); ?>
            </div>
        </div>
    </div>
</body>
</html>