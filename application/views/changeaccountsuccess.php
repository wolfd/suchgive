<?php if ($success) { ?>
    <div class="alert alert-success alert-dismissable">Account changed successfully</div>
<?php } else { ?>
    <div class="alert alert-danger alert-dismissable">Could not change account information: <?=$reason?></div>
<?php } ?>