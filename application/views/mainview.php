<script type="text/javascript" src="/js/countdown.js"></script>
<div class="row color-doge">
  <div class="col-md-4 color-lightdoge">
    <div class="left-side">
    	<h1><?=$battle['zero_name']?></h4>
    	<p><?=$battle['zero_description']?></p>
    	<p><?=$battle['zero_url']?></p>
    	<h2>Donated: <?php echo ($battle['zero_shibetoshi'] / 1e8) ?> Ɖ</h2>
    	<p><?php if ($logged_in) echo $zero_address;?></p>
    </div>
  </div>
  <div class="col-md-4 text-center">
    <div class=""><h4>Time Left</h4></div>
    <div class="the-moon">
      <div class="countdown-clock-container">
        <div id="countdown-clock" class="center countdown-clock"></div>
      </div>
    </div>
    <h4><?=$battle['battle_description']?></h4>
    <div class="reward-pool">
      <h3>Reward Pool:</h3>
      <h2><?php echo ($battle['reward_shibetoshi'] / 1e8) ?> Ɖ</h2>
      <p>Doge that will go to the winning charity at the end of the battle</p>
    </div>
  </div>
  <div class="col-md-4 color-lightdoge">
    <div class="right-side">
    	<h1><?=$battle['one_name']?></h4>
    	<p><?=$battle['one_description']?></p>
    	<p><?=$battle['one_url']?></p>
    	<h2>Donated: <?php echo ($battle['one_shibetoshi'] / 1e8) ?> Ɖ</h2>
    	<p><?php if ($logged_in) echo $one_address;?></p>
    </div>
  </div>
</div>
<div class="padded-description">
  <p class="lead">competitive giving in the name of doge</p>
  <p class="lead">What is this?</p>
  <p>A website created to gamify charitible giving in the hope that it will encourage people to give more money, and give it faster.</p>

  <p class="lead">How does it work?</p>
  <p>Two charities are put head to head in a "charity battle" that lasts for a for a few days. During that time, users donate to the charity that they believe in most. Every time a donation is made, half of the Doge goes toward a "reward pool", and the other half goes directly to the charity's account. At the end of the battle, the charity that has the most Doge in their account is given the reward pool in addition to their normal earnings. The losing charity still gets the money in their account. Absolutely none of the money goes to me.</p>

  <p class="lead">How does the money get to the charities?</p>
  <p>If the charity takes Doge, I will transfer it directly to them. If the charity does not take Doge (which is likely the case), I will exchange the Doge into USD, and give the money to the charities in that way. I understand that my word is not good enough for many of you, so I will do the best I can to keep this process as public as possible. If you have any suggestions, please <a href="/contact">contact me.</a></p>


</div>
<p></p>
<script type="text/javascript">
var end_date = new Date("<?=$battle['end_date']?>");
var countdown = new Countdown({
  year  : end_date.getFullYear(), 
  month : end_date.getMonth() + 1, 
  day  : end_date.getDate(), 
  hour  : (end_date.getHours() < 12) ? end_date.getHours() : end_date.getHours() - 12,
  ampm  : (end_date.getHours() < 12) ? "am" : "pm",
  minute  : end_date.getMinutes(), 
  second  : end_date.getSeconds(),
  rangeHi  : "day",
  style  : "flip",
  inline  : true,
  target  : "countdown-clock",
  width  : 200,
  height  : 48
});
</script>