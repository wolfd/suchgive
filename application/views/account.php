<div class="suchgive-content-padded">
    <h2>my account</h2>
    <p>welcome, <?=$account_data['nickname']?>!</p>
    <a href="/account/change">change account information</a>
    <h3>donation stats</h3>
    <p>total donated: <?php echo ($account_data['total_donated'] / 1e8);?> doge</p>
    <h3>donation transactions</h3>
    <div class="table-responsive">
    <?php
    echo $account_data['donation_table'];
    ?>
    </div>

    <p>
    Thanks for your support!
    </p>
</div>
