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
                <!--<h3 class="left-charity-mini-info-name"><?=$battle['zero_name'] ?></h3>-->
                <p id="left-charity-mini-info-amount" class="left-charity-mini-info-amount lead" amount="0">0 &ETH</p>
            </div>


            <div class="charity-mini-info right-charity-mini-info">
                <!--<h3 class="right-charity-mini-info-name"><?=$battle['one_name'] ?></h3>-->
                <p id="right-charity-mini-info-amount" class="right-charity-mini-info-amount lead" amount="0">0 &ETH</p>
            </div>
        </div>
    </div>
</div>
<div class="funding-goal-box outer-shadow">
    <p class="lead">Funding Goal</p>
    <p><?=$battle['funding_goal']?></p>
</div>
<div class="col-md-6 charity-description">
    <div class="charity-description-heading">
        <h3>Charity XYZ</h3>
        <a href="#">something.org</a>
        <p class="lead"><i>headline of charity</i></p>
    </div>
    <div class="charity-description-text">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sit amet nisi in justo laoreet consectetur dignissim a sem. Nam ac magna accumsan, elementum arcu eget, scelerisque felis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ligula augue, accumsan sit amet congue in, consectetur in risus. Curabitur eu venenatis eros, tempus lobortis elit. Aenean ornare fringilla purus. Nulla malesuada, risus a commodo eleifend, sem felis ullamcorper dui, quis sodales felis odio ac libero. Maecenas neque neque, feugiat quis neque quis, pulvinar sollicitudin mi. Mauris sit amet tortor risus. Maecenas malesuada aliquet ultricies. Sed porta libero justo, nec vulputate purus convallis at. In volutpat ac urna nec varius.</p>
        <p>Etiam augue lacus, aliquet ut urna id, rutrum cursus dui. Quisque molestie, nisi a pellentesque dictum, magna erat egestas massa, non viverra ipsum ante et augue. In hac habitasse platea dictumst. Nullam ut condimentum urna. Sed sapien leo, fermentum eget auctor eget, interdum et dolor. Integer nec auctor turpis, ac fermentum risus. Donec massa massa, pellentesque vel facilisis sit amet, sagittis eu leo. Aenean dignissim malesuada est, ac tristique metus. Phasellus auctor varius odio quis gravida. Sed ipsum erat, porta scelerisque imperdiet in, ultricies ut quam. Nulla facilisi. Aliquam placerat euismod placerat. Vestibulum eu enim nulla. Phasellus pretium viverra neque eget aliquam. Proin ut vulputate tortor.</p>
        <p>Sed hendrerit est sed euismod dapibus. Nulla sit amet massa nibh. Aliquam erat volutpat. Curabitur eu ligula varius, egestas diam quis, tempor metus. Sed auctor nulla ut turpis congue, eget semper nibh facilisis. Aenean justo nunc, tempus nec nibh in, ultricies scelerisque nisi. Cras consequat adipiscing placerat. Aliquam consectetur lorem a ligula interdum fermentum. Aenean sed viverra turpis. Duis euismod orci ac orci sodales, at sodales purus scelerisque. Sed id tellus venenatis lorem malesuada facilisis vitae at mi.</p>
    </div>
    <button type="button" class="btn btn-primary btn-lg charity-give-button">GIVE!</button>
</div>
<div class="col-md-6 charity-description charity-description-right">
    <div class="charity-description-heading charity-description-heading-right clearfix">
        <h3>Charity XYZ</h3>
        <a href="#">something.org</a>
        <p class="lead"><i>headline of charity</i></p>
    </div>
    <div class="charity-description-text charity-description-text-right">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sit amet nisi in justo laoreet consectetur dignissim a sem. Nam ac magna accumsan, elementum arcu eget, scelerisque felis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque ligula augue, accumsan sit amet congue in, consectetur in risus. Curabitur eu venenatis eros, tempus lobortis elit. Aenean ornare fringilla purus. Nulla malesuada, risus a commodo eleifend, sem felis ullamcorper dui, quis sodales felis odio ac libero. Maecenas neque neque, feugiat quis neque quis, pulvinar sollicitudin mi. Mauris sit amet tortor risus. Maecenas malesuada aliquet ultricies. Sed porta libero justo, nec vulputate purus convallis at. In volutpat ac urna nec varius.</p>
        <p>Etiam augue lacus, aliquet ut urna id, rutrum cursus dui. Quisque molestie, nisi a pellentesque dictum, magna erat egestas massa, non viverra ipsum ante et augue. In hac habitasse platea dictumst. Nullam ut condimentum urna. Sed sapien leo, fermentum eget auctor eget, interdum et dolor. Integer nec auctor turpis, ac fermentum risus. Donec massa massa, pellentesque vel facilisis sit amet, sagittis eu leo. Aenean dignissim malesuada est, ac tristique metus. Phasellus auctor varius odio quis gravida. Sed ipsum erat, porta scelerisque imperdiet in, ultricies ut quam. Nulla facilisi. Aliquam placerat euismod placerat. Vestibulum eu enim nulla. Phasellus pretium viverra neque eget aliquam. Proin ut vulputate tortor.</p>
        <p>Sed hendrerit est sed euismod dapibus. Nulla sit amet massa nibh. Aliquam erat volutpat. Curabitur eu ligula varius, egestas diam quis, tempor metus. Sed auctor nulla ut turpis congue, eget semper nibh facilisis. Aenean justo nunc, tempus nec nibh in, ultricies scelerisque nisi. Cras consequat adipiscing placerat. Aliquam consectetur lorem a ligula interdum fermentum. Aenean sed viverra turpis. Duis euismod orci ac orci sodales, at sodales purus scelerisque. Sed id tellus venenatis lorem malesuada facilisis vitae at mi.</p>
    </div>
    <button type="button" class="btn btn-primary btn-lg charity-give-button">GIVE!</button>
</div>
<?php

?>