<script src="/js/prefixfree.min.js"></script>
<script src="https://code.jquery.com/jquery.js"></script>
<div class="moon-scene visible-desktop visible-tablet">
    <div class="scene-container">
        <div class="the-moon">
            <div class="reward-banner">
                <h2 amount="0"class="reward-banner-text">REWARD</h2>
                <h2 id="reward-amount" class="reward-banner-text" amount="0">0 &ETH</h2>
            </div>
        </div>
    </div>
    <div class="scene-container">
        <div class="the-earth"></div>
    </div>
    <div class="scene-container">
        <div id="spaceship-left" class="spaceship spaceship-left"></div>
    </div>
    <div class="scene-container">
        <div id="spaceship-right" class="spaceship spaceship-right"></div>
    </div>
    <div class="scene-container">
        <div class="specialfix">
            <div class="charity-mini-info left-charity-mini-info">
                <p id="left-charity-mini-info-amount" class="left-charity-mini-info-amount lead" amount="0">0 &ETH</p>
            </div>
            <div class="charity-mini-info right-charity-mini-info">
                <p id="right-charity-mini-info-amount" class="right-charity-mini-info-amount lead" amount="0">0 &ETH</p>
            </div>
        </div>
    </div>
</div>
<div class="funding-goal-box outer-shadow">
    <p class="lead funding-goal-label">Funding Goal</p>
    <h2 class="funding-goal-amount"><?=$battle['funding_goal']?> &ETH</h2>
</div>
<div class="col-md-6 charity-description">
    <div class="charity-description-heading">
        <h3><?=$battle['zero_name']?></h3>
        <a href="<?=$battle['zero_url']?>"><?=$battle['zero_url']?></a>
        <p class="lead"><i><?=$battle['zero_tag_line']?></i></p>
    </div>
    <div class="charity-description-text">
        <?=$battle['zero_description']?>
    </div>
    <button type="button" class="btn btn-primary btn-lg charity-give-button">GIVE!</button>
</div>
<div class="col-md-6 charity-description charity-description-right">
    <div class="charity-description-heading charity-description-heading-right clearfix">
        <h3><?=$battle['one_name']?></h3>
        <a href="<?=$battle['one_url']?>"><?=$battle['one_url']?></a>
        <p class="lead"><i><?=$battle['one_tag_line']?></i></p>
    </div>
    <div class="charity-description-text charity-description-text-right">
        <?=$battle['one_description']?>
    </div>
    <button type="button" class="btn btn-primary btn-lg charity-give-button">GIVE!</button>
</div>