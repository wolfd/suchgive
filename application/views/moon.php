<script src="/js/prefixfree.min.js"></script>
<script src="https://code.jquery.com/jquery.js"></script>
<div class="moon-scene visible-desktop visible-tablet">
    <div class="scene-container">
        <div class="the-moon">
            <div class="reward-banner">
                <h2 amount="0"class="reward-banner-text">REWARD</h2>
                <h2 id="reward-amount" class="reward-banner-text jsonly" amount="0">0 &ETH</h2>
                <noscript>
                    <h2 class="reward-banner-text"><?php echo number_format($realtime['reward_pool_raised']);?> &ETH</h2>
                </noscript>
            </div>
        </div>
    </div>
    <div class="scene-container">
        <div class="the-earth"></div>
    </div>
    <div class="scene-container">
        <div id="spaceship-left" class="spaceship spaceship-left jsonly"></div>
    </div>
    <div class="scene-container">
        <div id="spaceship-right" class="spaceship spaceship-right jsonly"></div>
    </div>
    <div class="scene-container">
        <div class="specialfix">
            <div class="charity-mini-info left-charity-mini-info jsonly">
                <p id="left-charity-mini-info-amount" class="left-charity-mini-info-amount lead" amount="0">0 &ETH</p>
                <p id="left-charity-mini-info-percent" class="left-charity-mini-info-percent" amount="0">0&#37;</p>
            </div>
            <noscript>
                <div class="charity-mini-info left-charity-mini-info">
                    <p class="left-charity-mini-info-amount lead"><?php echo number_format($realtime['charity_zero_raised']);?> &ETH</p>
                    <p class="left-charity-mini-info-percent"><?=$realtime['charity_zero_percentage']?>&#37;</p>
                </div>
            </noscript>
            <div class="charity-mini-info right-charity-mini-info jsonly">
                <p id="right-charity-mini-info-amount" class="right-charity-mini-info-amount lead" amount="0">0 &ETH</p>
                <p id="right-charity-mini-info-percent" class="right-charity-mini-info-percent" amount="0">0&#37;</p>
            </div>
            <noscript>
                <div class="charity-mini-info right-charity-mini-info">
                    <p class="right-charity-mini-info-amount lead"><?php echo number_format($realtime['charity_one_raised']);?> &ETH</p>
                    <p class="right-charity-mini-info-percent"><?=$realtime['charity_one_percentage']?>&#37;</p>
                </div>
            </noscript>
        </div>
    </div>
</div>
<div class="funding-goal-box">
    <p class="lead funding-goal-label">Funding Goal</p>
    <h2 class="funding-goal-amount"><?php echo number_format($battle['funding_goal']);?> &ETH</h2>
</div>
<div class="col-md-5 charity-description">
    <div class="charity-description-heading">
        <h3><?=$battle['zero_name']?></h3>
        <a href="<?=$battle['zero_url']?>"><?=$battle['zero_url']?></a>
        <p class="lead"><i><?=$battle['zero_tag_line']?></i></p>
    </div>
    <div class="charity-description-text">
        <?=$battle['zero_description']?>
    </div>
    <button id="give-zero" type="button" class="btn btn-primary btn-lg charity-give-button">GIVE!</button>
    <div id="modal-of-donate-zero" class="modal-of-donate">
        <div class="modal-exit-bar"><div id="modal-close-zero" class="modal-get-out-now-pls"><span class="glyphicon glyphicon-remove"></span></div></div>
        <h3><?=$battle['zero_name']?></h3>
        <p class="lead">Address to donate to:</p>
        <p>
            <?php if($logged_in) { ?>
        <p class="doge-address"><?=$zero_address?></p>
        <?php } else { ?>
            <p class="text-danger">please log in to donate</p>
        <?php }?>
        </p>
        <p class="text-info">Your donation will take about a minute to complete. When the donation is complete, half of it will be directed to the charity you selected, and the other half will be put into the reward pool.</p>
        <p class="text-muted">Thank you for your donation!</p>
    </div>
</div>
<div class="col-md-2">
    <div class="charity-vertical-divider"></div>
</div>
<div class="col-md-5 charity-description charity-description-right">
    <div class="charity-description-heading charity-description-heading-right clearfix">
        <h3><?=$battle['one_name']?></h3>
        <a href="<?=$battle['one_url']?>"><?=$battle['one_url']?></a>
        <p class="lead"><i><?=$battle['one_tag_line']?></i></p>
    </div>
    <div class="charity-description-text charity-description-text-right">
        <?=$battle['one_description']?>
    </div>
    <button id="give-one" type="button" class="btn btn-primary btn-lg charity-give-button">GIVE!</button>
    <div id="modal-of-donate-one" class="modal-of-donate">
        <div class="modal-exit-bar"><div id="modal-close-one" class="modal-get-out-now-pls"><span class="glyphicon glyphicon-remove"></span></div></div>
        <h3><?=$battle['one_name']?></h3>
        <p class="lead">Address to donate to:</p>
        <p>
            <?php if($logged_in) { ?>
        <p class="doge-address"><?=$one_address?></p>
        <?php } else { ?>
            <p class="text-danger">please log in to donate</p>
        <?php }?>
        </p>
        <p class="text-info">Your donation will take about a minute to complete. When the donation is complete, half of it will be directed to the charity you selected, and the other half will be put into the reward pool.</p>
        <p class="text-muted">Thank you for your donation!</p>
    </div>
</div>