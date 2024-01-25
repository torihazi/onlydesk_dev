<?php $this->setLayoutVar('title', 'ユーザ登録完了') ?>

<script type="text/javascript">
    setTimeout( function () {
        window.location.href = "https://ida-1.onlydesk.jp/index_dev.php/login/signin";
    }, 3*1000);
</script>

<p>登録が完了しました</p>
<p>ログイン画面においてログインを行ってください</p>
<p>3秒後にログイン画面に遷移します。</p>
<p>遷移されない場合は<span><a href="<?php echo $base_url; ?>/login/signin">こちら</a></span>をクリックしてください</p>