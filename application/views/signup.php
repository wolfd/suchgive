<h2>Sign up</h2>
<form action="https://www.suchgive.org/account/signup" method="post" class="form-horizontal">
    <?php
    echo validation_errors();
    echo $recaptcha;
    ?>
    <label for="nickname">Nickname</label>
    <input name="nickname" type="nickname" class="form-control" id="nickname" placeholder="Enter nickname" required>
    <label for="password">Password (Can contain alphanumeric and dash characters. Must be 8 characters or longer.)</label>
    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
    <label for="passwordconfirm">Confirm Password</label>
    <input name="passwordconfirm" type="password" class="form-control" id="passwordconfirm" placeholder="Password" required>
    <div class="captcha">
        <?php
        require_once(APPPATH.'libraries/recaptchalib.php');
        echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY, null, true);
        ?>
    </div>
    <button type="submit" class="btn btn-default">Sign up</button>
</form>
