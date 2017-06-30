<div class="auLogin">
    <?php echo \Forms\Builder::input([
        'id' => 'login',
        'class' => 'loginFormEl formIconLogin',
        'autocomplete' => 'off',
        'autofocus' => '',
        'placeholder' => 'Логин',
    ]); ?>
</div>
<div class="auPass">
    <?php echo \Forms\Builder::password([
        'id' => 'password',
        'class' => 'loginFormEl formIconPass',
        'autocomplete' => 'off',
        'placeholder' => 'Пароль',
    ]); ?>
</div>
<div class="error"></div>
<div class="auRe">
    <label>
        <?php echo \Forms\Builder::checkbox(false, [
            'id' => 'remember',
        ]); ?>
        <span class="auReLabel"><?php echo __('Запомнить меня'); ?></span>
    </label>
</div>

<div class="btnWrap clearFix">
    <a href="#" class="enterLink" id="enterLink"><?php echo __('Войти'); ?></a>
</div>

<script>
    var ref = '<?php echo \Core\Arr::get($_GET, 'ref', '/wezom'); ?>';
    $(function(){
        $('.auWrap').on('keydown', function(event) {
            if(event.keyCode === 13) {
                $('#enterLink').trigger('click');
            }
        });
        $('#enterLink').on('click', function(e){
            e.preventDefault();
            var login = $('#login').val();
            var password = $('#password').val();
            var remember = 0;
            if( $('#remember').prop('checked') ) {
                remember = 1;
            }
            $.ajax({
                url: '/wezom/ajax/login',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    login: login,
                    password: password,
                    remember: remember
                },
                success: function(data){
                    if( data.success ) {
                        if(ref) {
                            window.location.href = ref;
                        } else {
                            window.location.reload();
                        }
                    } else {
                        $('.error').html(data.msg);
                    }
                }
            });
        });
    });
</script>

<style>
    .error {
        margin-top: -17px;
        color: red;
        font-size: 12px;
        height: 16px;
    }
</style>