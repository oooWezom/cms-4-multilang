<div class="form-group">
    <div class="">
        <label class="control-label" for="parent_id">
            <?php echo __('Изображение, которое нужно обрезать'); ?>
        </label>
        <div class="">
            <div class="controls">
                <?php foreach($images AS $key => $value): ?>
                    <a href="<?php echo \Core\General::crop($arr[0], $value['path'], $arr[1], $arr[2]); ?>" class="btn <?php echo $value['path'] == $arr[3] ? 'btn-primary' : NULL; ?>"><?php echo $value['width'].'x'.$value['height']; ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<div class="rowSection">
    <link rel="stylesheet" href="<?php echo Core\HTML::bmedia('css/cropper.css'); ?>">
    <script type="text/javascript" src="<?php echo Core\HTML::bmedia('js/cropper.js'); ?>"></script>
    <div class="col-md-12 editModule preloadCrop" id="editModule" data-config='<?php echo $json; ?>'>
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-edit"></i> <?php echo __('Редактирование изображения'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="col-md-5 cropBlock">
                    <div class="cropBlockIn">
                        <img src="<?php echo $image; ?>" alt="Picture">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="btn btn-info" id="croppedBtn"><?php echo __('Кропнуть'); ?></div>
                </div>
                <div class="col-md-6 cropPreview" id="cropPreview"></div>
            </div>
        </div>
    </div>
</div>