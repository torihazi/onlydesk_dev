<?php $this->setLayoutVar('title', 'パスワード更新完了'); ?>

<script type="text/javascript">
    setTimeout( function () {
        window.location.href = "https://ida-1.onlydesk.jp/index.php/login/signin";
    }, 3*1000);
</script>

<p>パスワードの更新が完了しました</p>
<p>更新したパスワードでログインをお願いいたします</p>
<p>3秒後にログイン画面に遷移します</p>
<p>遷移されない場合は<span><a href="<?php echo $base_url; ?>/login/signin">こちら</a></span>をクリックしてください</p>