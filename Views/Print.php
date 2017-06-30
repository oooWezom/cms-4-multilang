<?php use Core\HTML; ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="imagetoolbar" content="no"/>
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <title>Распечатать заказ</title>
    <link type="text/css" rel="stylesheet" href="<?php echo HTML::bmedia('css/print.css'); ?>"/>
</head>
<body onload="window.print();">
<div class="conteiner">
    <?php echo $_content; ?>
</div>
</body>
</html>