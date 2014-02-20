<div class="suchgive-content-padded">
    <h2>My Account</h2>
    <h3>Donation Stats</h3>
    <?php
    echo "Total donated: ".($account_data['total_donated'] / 1e8)." Doge";
    ?>
    <h3>Donation transactions</h3>
    <div class="table-responsive">
    <?php
    echo $account_data['donation_table'];
    ?>
    </div>

    <p>
    Thanks for your support!
    </p>
</div>
