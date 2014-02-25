<h2>sign up</h2>
<form action="/account/signup" method="post" class="form-horizontal">
    <?php
    echo validation_errors();
    echo $recaptcha;
    ?>
    <label for="nickname">nickname</label>
    <input name="nickname" type="text" class="form-control" id="nickname" placeholder="enter nickname" required>
    <label for="password">password (can contain alphanumeric and dash characters. must be 8 characters or longer.)</label>
    <input name="password" type="password" class="form-control" id="password" placeholder="password" required>
    <label for="passwordconfirm">confirm password</label>
    <input name="passwordconfirm" type="password" class="form-control" id="passwordconfirm" placeholder="password" required>
    <div class="captcha">
        <?php
        require_once(APPPATH.'config/suchgive_config.php');
        if (RECAPTCHA_ENABLED)
        {
            require_once(APPPATH.'libraries/recaptchalib.php');
            echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY, null, true);
        }
        ?>
    </div>
    <button type="submit" class="btn btn-default">sign up</button>
</form>
