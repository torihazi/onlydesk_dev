<?php $this->setLayoutVar('title', '送信完了'); ?>

<script type="text/javascript">
    setTimeout( function () {
        window.location.href = "https://ida-1.onlydesk.jp/index_dev.php/post/list";
    }, 3*1000);
</script>

<p>記入いただいたメールアドレスへのメール送信が完了しました。</p>
<p>24時間以内に本文記載のURLから、パスワードリセットを行ってください</p>
<p>3秒後に投稿一覧画面に遷移します。</p>
<p>遷移されない場合は<span><a href="<?php echo $basr_url; ?>/post/list">こちら</a></span>をクリックしてください</p>