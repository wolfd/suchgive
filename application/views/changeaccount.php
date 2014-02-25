<h2>change account info</h2>
<form action="/account/change" method="post" class="form-horizontal">
    <?php
    echo validation_errors();
    echo $recaptcha;
    ?>
    <p>confirm your current identity</p>
    <label for="nickname">current nickname</label>
    <input name="nickname" type="text" class="form-control" id="nickname" placeholder="enter nickname" required>
    <label for="password">current password</label>
    <input name="password" type="password" class="form-control" id="password" placeholder="password" required>
    <br><p>choose a new nickname</p>
    <label for="nicknamechange">new nickname</label>
    <input name="nicknamechange" type="text" class="form-control" id="nicknamechange" placeholder="enter new nickname">
    <br><p>choose a new password</p>
    <label for="passwordchange">new password (can contain alphanumeric and dash characters. must be 8 characters or longer.)</label>
    <input name="passwordchange" type="password" class="form-control" id="passwordchange" placeholder="password">
    <label for="passwordchangeconfirm">confirm new password</label>
    <input name="passwordchangeconfirm" type="password" class="form-control" id="passwordchangeconfirm" placeholder="password">
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
    <button type="submit" class="btn btn-default">Change</button>
</form>
