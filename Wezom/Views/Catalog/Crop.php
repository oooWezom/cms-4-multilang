<div class="rowSection">
    <link rel="stylesheet" href="<?php echo Core\HTML::bmedia('css/cropper.css'); ?>">
    <script type="text/javascript" src="<?php echo Core\HTML::bmedia('js/cropper.js'); ?>"></script>
    <div class="col-md-12 editModule preloadCrop" id="editModule" data-config="/Wezom/Config/crop1.json">
        <div class="widget box">
            <div class="widgetHeader">
                <div class="widgetTitle">
                    <i class="fa fa-edit"></i> <?php echo __('Редактирование изображения'); ?>
                </div>
            </div>
            <div class="widgetContent">
                <div class="col-md-5 cropBlock">
                    <div class="cropBlockIn">
                        <img src="<?php echo Core\HTML::bmedia('pic/test.jpg'); ?>" alt="Picture">
                    </div>
                </div>
                <div class="col-md-4">
                  <div class="btn btn-info" id="croppedBtn"><?php echo __('Кропнуть'); ?></div>
                </div>
                <div class="col-md-3 cropPreview" id="cropPreview"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  var crop = $('#editModule');
  if (crop.length) {
      $.getJSON(crop.attr("data-config"), function(data) {
          if (data == "undefined") {
              throw new Error('Problem with JSON file!');
          }
      }).done(function(data) {
          var ratio = parseInt(data.preview.width, 10) / parseInt(data.preview.height, 10);
          var preview = $('<div class="cropPreviewIn"><div class="cropPreviewImg"></div><div class="cropPreviewTxt">'+data.preview.width+' x '+data.preview.height+'</div></div>');
          preview.children('.cropPreviewImg').css({
              width: data.preview.width,
              height: data.preview.height
          });
          preview.appendTo($('#cropPreview'));
          initCrop(ratio);
      });
  }

  function initCrop(ratio) {
      var wd = parseFloat($('.cropBlock').outerWidth()) / ratio;
      var wdP = parseFloat($('.cropBlock').parent().outerWidth());
      $('.cropBlock').css('padding-top', wd / wdP * 100 + '%');
      
      var $crop = $('.cropBlockIn > img'),
        options = {
            aspectRatio: ratio,
            preview: '.cropPreviewImg'
        };;

      $crop.cropper(options).on('built.cropper', function(event) {         
        setTimeout(function() {
          $('#editModule').removeClass('preloadCrop');
        }, 500);
      });      

      $(document).keydown(function(event) {
          if (event.keyCode === 13) {
              $crop.cropper('reset', true);
          }
      });
      $('#croppedBtn').on('click', function(event) {
        event.preventDefault();
        var json = [
                  '{"cropBox":' + JSON.stringify($crop.cropper('getData')),
                  '"naturalImage":' + JSON.stringify($crop.cropper('getImageData')) + '}'
                  ].join();
        $.ajax({
            type: 'POST',
            /*url:  url ,*/
            data: {
                json: json
            },
            dataType: 'JSON',
            success: function(data) {
                
            }
        });
      });
  }
</script>