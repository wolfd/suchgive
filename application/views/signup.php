<h2>Sign up</h2>
<?php
$attributes = array('class' => 'form-horizontal');
echo form_open('/account/signup', $attributes);
?>
<?php
echo validation_errors();
echo $recaptcha;
?>
<div class="form-group">
    <label for="nickname">Nickname</label>
    <input name="nickname" type="nickname" class="form-control" id="nickname" placeholder="Enter nickname" required>
</div>
<div class="form-group">
    <p>Password can contain alphanumeric and dash characters. Must be 8 characters or longer.</p>
    <label for="password">Password</label>
    <input name="password" type="password" class="form-control" id="password" placeholder="Password" required>
    <label for="passwordconfirm">Confirm Password</label>
    <input name="passwordconfirm" type="passwordconfirm" class="form-control" id="passwordconfirm" placeholder="Password" required>
</div>
<div class="checkbox">
    <label>
        <input name="anonymous" type="checkbox"> Remain anonymous: your nickname will not be listed on the site anywhere. Donations will show as from "anonymous".
    </label>
</div>
<div class="captcha">
    <?php
    require_once(APPPATH.'libraries/recaptchalib.php');
    echo recaptcha_get_html(RECAPTCHA_PUBLIC_KEY);
    ?>
</div>
<button type="submit" class="btn btn-default">Sign up</button>
<?php
echo form_close();
?>
