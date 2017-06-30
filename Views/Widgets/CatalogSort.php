<?php
use Core\Route;
use Core\Arr;
use Core\Config;

?>
<div class="small_filt" id="catalogSort"
     data-uri="<?php echo str_replace('/page/' . Route::param('page'), '', Arr::get($_SERVER, 'REQUEST_URI')); ?>"
     data-get="<?php echo Route::controller() == 'search' ? 'query=' . Arr::get($_GET, 'query') : ''; ?>">
    <div class="small_filt1">
        <p><?php echo __('выводить ПО'); ?>:</p>
        <select name="per_page" id="select1">
            <?php $limit = Config::get('basic.limit'); ?>
            <?php for ($i = Config::get('basic.limit'); $i < Config::get('basic.limit') * 5; $i += Config::get('basic.limit')): ?>
                <option value="<?php echo $i; ?>" <?php echo Arr::get($_GET, 'per_page') == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <p><?php echo __('на странице'); ?></p>
    </div>
    <div class="small_filt2">
        <p><?php echo __('сортировать'); ?>:</p>
        <select name="sort" id="select2">
            <option value=""><?php echo __('Не сортировать'); ?></option>
            <option value="cost"
                    data-type="desc" <?php echo (Arr::get($_GET, 'sort') == 'cost' and Arr::get($_GET, 'type') == 'desc') ? 'selected' : ''; ?>>
                <?php echo __('От дорогих к бютжетным'); ?>
            </option>
            <option value="cost"
                    data-type="asc" <?php echo (Arr::get($_GET, 'sort') == 'cost' and  Arr::get($_GET, 'type') == 'asc') ? 'selected' : ''; ?>>
                <?php echo __('От бютжетных к дорогим'); ?>
            </option>
            <option value="created_at"
                    data-type="desc" <?php echo (Arr::get($_GET, 'sort') == 'created_at' and Arr::get($_GET, 'type') == 'desc') ? 'selected' : ''; ?>>
                <?php echo __('От новых моделей к старым'); ?>
            </option>
            <option value="created_at"
                    data-type="asc" <?php echo (Arr::get($_GET, 'sort') == 'created_at' and Arr::get($_GET, 'type') == 'asc') ? 'selected' : ''; ?>>
                <?php echo __('От старых моделей к новым'); ?>
            </option>
            <option value="name"
                    data-type="asc" <?php echo (Arr::get($_GET, 'sort') == 'name' and Arr::get($_GET, 'type') == 'asc') ? 'selected' : ''; ?>>
                <?php echo __('По названию от А до Я'); ?>
            </option>
            <option value="name"
                    data-type="desc" <?php echo (Arr::get($_GET, 'sort') == 'name' and Arr::get($_GET, 'type') == 'desc') ? 'selected' : ''; ?>>
                <?php echo __('По названию от Я до А'); ?>
            </option>
        </select>
    </div>
</div>

<script>
    $(function () {
        $('#catalogSort select').on('change', function () {
            // Get clear uri
            var uri = $('#catalogSort').data('uri');
            arr = uri.split('?');
            uri = arr[0];
            // Get parameter for search controller
            var old = $('#catalogSort').data('get');
            // Create get parameters
            var get = [];
            if (old) {
                get.push(old);
            }
            $('#catalogSort select').each(function () {
                if ($(this).attr('name') == 'per_page') {
                    get.push('per_page=' + $(this).val());
                }
                if ($(this).attr('name') == 'sort' && $(this).val()) {
                    get.push('sort=' + $(this).val());
                    get.push('type=' + $(this).find('option:selected').data('type'));
                }
            });
            // Create link
            if (get.length) {
                get = get.join('&');
                uri += '?' + get;
            }
            // Relocate
            window.location.href = uri;
        });
    });
</script>