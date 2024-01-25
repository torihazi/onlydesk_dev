<?php $this->setLayoutVar('title', '退会'); ?>
<h2 class="page_title">退会</h2>

<p>本当に退会します。よろしいですか？</p>
<form method="post" action="<?php echo $base_url; ?>/mypage/quitFormCheckFin">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
    <input type="hidden" name="content" value="<?php echo $content; ?>">


    <div class="quit_form_container">
        <p>退会理由</p>
        <p class="quit_content"><?php if (! empty($content)) : ?>
                <?php echo $content; ?>
            <?php else: ?>
                特になし
            <?php endif; ?>
        </p>
    </div>

    <div class="quit_button_container ">
        <button class="quit_button quit" type="submit" name="submit" value="fin">退会する</button>
        <button class="quit_button cancel" type="submit" name="submit" value="cancel">キャンセル</button>
    </div>
</form>
