<h2>My Account</h3>
<h3>Donation Stats</h3>
<?php
echo "Total donated: ".($account_data['total_donated'] / 1e8)." Doge";
//print_r($account_data['total_donated']);
?>
<h3>Donation transactions</h3>
<div class="span6">
<div class="table-responsive">
<?php
echo $account_data['donation_table'];
?>
</div>
</div>

<p>
Thanks for your support!
</p>
