<div class="rowSection">
    <script type="text/javascript" src="<?php echo Core\HTML::bmedia('js/dropzone.js'); ?>"></script>
    <div class="col-md-12 dropModule">
        <div class="widget box dropBox">
          <div class="widgetHeader myWidgetHeader">
              <div class="widgetTitle">
                  <i class="fa fa-download"></i>
                  <?php echo __('Загрузка изображений'); ?>
              </div>
          </div>
          <div class="widgetContent">
            <button class="btn btn-success dropAdd"><i class="fa fa-plus"></i> <?php echo __('Добавить изображения'); ?></button>
            <button class="btn btn-info dropLoad" style="display: none;"><i class="fa fa-download"></i> <?php echo __('Загрузить все'); ?> (<span class="dropCount">0</span>)</button>
            <button class="btn btn-danger dropCancel" style="display: none;"><i class="fa fa-ban-circle"></i> <?php echo __('Отменить все'); ?></button>
          </div>
          <div class="widgetContent">
             <div class="dropZone" data-config="/wezom/Config/drop1.json"></div>
          </div>
        </div>
        <div class="widget box loadedBox">
          <div class="widgetHeader myWidgetHeader">
              <div class="widgetTitle">
                  <i class="fa fa-file"></i>
                  <?php echo __('Загруженные изображения'); ?>
              </div>
          </div>
          <div class="widgetContent">
            <button class="btn btn-info checkAll" style="display: none;"><i class="fa fa-check"></i> <?php echo __('Отметить все'); ?></button>
            <button class="btn btn-warning uncheckAll" style="display: none;"><i class="fa fa-ban-circle"></i> <?php echo __('Снять все'); ?></button>
            <button class="btn btn-danger removeCheck" style="display: none;"><i class="fa fa-remove"></i> <?php echo __('Удалить отмеченые'); ?></button>
          </div>
          <div class="widgetContent dropDownload"></div>
        </div>
    </div>
</div>