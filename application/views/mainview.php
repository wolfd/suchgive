<script type="text/javascript" src="/js/countdown.js"></script>

<div class="row color-doge">
  <div class="col-md-4 color-lightdoge">
    <div class="left-side">
    	<h1><?=$battle['zero_name']?></h1>
    	<p><?=$battle['zero_description']?></p>
    	<p><?=$battle['zero_url']?></p>
    	<h2>Donated: <?php echo ($battle['zero_shibetoshi'] / 1e8) ?> Ɖ</h2>
    	<p><?php if ($logged_in) echo $zero_address;?></p>
    </div>
  </div>
    <div class="col-md-4 text-center">
        <div class=""><h4>Time Left</h4></div>
        <div class="countdown-clock-container">
            <div id="countdown-clock" class="center countdown-clock"></div>
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
    	<h1><?=$battle['one_name']?></h1>
    	<p><?=$battle['one_description']?></p>
    	<p><?=$battle['one_url']?></p>
    	<h2>Donated: <?php echo ($battle['one_shibetoshi'] / 1e8) ?> Ɖ</h2>
    	<p><?php if ($logged_in) echo $one_address;?></p>
    </div>
  </div>
</div>
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