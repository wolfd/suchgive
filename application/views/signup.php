<?php 
$attributes = array('class' => 'form-horizontal');
echo form_open('/account/signup', $attributes); 
?>
<fieldset>

<!-- Form Name -->
<legend>Sign up</legend>
<?php 
echo validation_errors();
echo $recaptcha;
?>
<!-- Text input-->
<div class="control-group">
  <label class="control-label" for="email">Email</label>
  <div class="controls">
    <input id="email" name="email" type="text" placeholder="doge@coin.net" class="input-xlarge" value="<?php echo set_value('email'); ?>" required="">
    <p class="help-block">This is for identification only. We will not sell your email address.</p>
  </div>
</div>

<!-- Appended checkbox -->
<div class="control-group">
  <label class="control-label" for="nickname">Nickname</label>
  <div class="controls">
    <div class="input-append">
      <input id="nickname" name="nickname" class="span2" type="text" placeholder="Doge" value="<?php echo set_value('nickname'); ?>" required="">
    </div>
    <p class="help-block">This is how other people will see your name in public leaderboards.</p>
  </div>
</div>

<!--<input name="anonymous" type="checkbox" value="anonymous" <?php echo set_checkbox('anonymous', 'anonymous'); ?>>
<p class="help-block">Check the box if you wish this nickname to remain anonymous.</p>
Doesn't work yet.
-->

<!-- Password input-->
<div class="control-group">
  <label class="control-label" for="password">Password</label>
  <div class="controls">
    <input id="password" name="password" type="password" placeholder="" class="input-xlarge" required="">
    <p class="help-block">Password can contain alphanumeric and dash characters. Must be 8 characters or longer.</p>
  </div>
</div>

<div class="control-group">
  <label class="control-label" for="passwordconfirm">Confirm Password</label>
  <div class="controls">
    <input id="passwordconfirm" name="passwordconfirm" type="password" placeholder="" class="input-xlarge" required="">
    
  </div>
</div>

<div class="control-group">
  <div class="controls">
    <div class="captcha">
<?php
  require_once(APPPATH.'libraries/recaptchalib.php');
  $publickey = "6LeJRO4SAAAAAIV1C_ScQb3dtV855LBefVgmLau5"; // you got this from the signup page
  echo recaptcha_get_html($publickey);
?>
    </div>
  </div>
</div>

<!-- Button -->
<div class="control-group">
  <label class="control-label" for="submit"></label>
  <div class="controls">
    <button id="submit" name="submit" class="btn btn-primary">Sign me up!</button>
  </div>
</div>

</fieldset>
<?php 
echo form_close(); 
?>
