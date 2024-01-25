<?php $this->setLayoutVar('title', '退会'); ?>
<h2 class="page_title">退会</h2>

<form method="post" action="<?php echo $base_url; ?>/mypage/quitFormCheck">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">


    <div class="quit_form_container">
        <textarea class="quit_textarea" name="content" rows="3" placeholder="もしあれば、ぜひご記入ください"><?php echo $content; ?></textarea>
    </div>

    <div class="quit_button_container">
        <button class="quit_button" type="submit" name="submit" value="submit">退会する</button>
    </div>
</form>