<?php $this->setLayoutVar('title', 'パスワード変更') ?>

<h2 class="page_title">パスワード変更</h2>

<div class="pw_change_form_container">

    <form method="post" action="<?php echo $base_url; ?>/mypage/pwChangeFormFin">
        <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

        <?php if (isset($errors['error'])) : ?>
            <?php echo '<p class="error">' . $errors['error'] . '</p>'; ?>
        <?php endif; ?>

        <div class="pw_form">
            <div class="pw_form_title">現在のパスワード</div>
            <input type="password" name="current_pwd" value="" placeholder="現在のパスワード">
            <?php if (isset($errors['current_pwd'])) : ?>
                <?php echo '<p class="error">' . $errors['current_pwd'] . '</p>'; ?>
            <?php endif; ?>
        </div>
        
        <div class="pw_form">
            <div class="pw_form_title">新規パスワード</div>
            <input type="password" name="new_pwd" value="" placeholder="新規パスワード(8～30文字以内)">
            <?php if (isset($errors['new_pwd'])) : ?>
                <?php echo '<p class="error">' . $errors['new_pwd'] . '</p>'; ?>
            <?php endif; ?>
        </div>
        
        <div class="pw_form">
            <div class="pw_form_title">確認用パスワード</div>
            <input type="password" name="chk_pwd" value="" placeholder="確認用パスワード">
            <?php if (isset($errors['check_pwd'])) : ?>
                <?php echo '<p class="error">' . $errors['check_pwd'] . '</p>'; ?>
            <?php endif; ?>
        </div>

        <div class="pw_form">
            <button class="pw_button" type="submit" name="submit" value="更新">確認画面へ</button>
        </div>
</form>