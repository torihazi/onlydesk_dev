<?php $this->setLayoutVar('title', 'プロフィール更新完了') ?>
<h2>プロフィール更新</h2>

<script type="text/javascript">
    setTimeout( function () {
        window.location.href = "https://ida-1.onlydesk.jp/index.php/post/list";
    }, 3*1000);
</script>

<p>更新が完了しました</p>
<p>3秒後に投稿一覧画面に遷移します。</p>
<p>遷移されない場合は<span><a href="<?php echo $basr_url; ?>/post/list">こちら</a></span>をクリックしてください</p>
