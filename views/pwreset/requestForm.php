<?php $this->setLayoutVar('title', 'パスワードリセット'); ?>

<div class="request_container">
    
    <div class="request_title_container">
        <h2>パスワードリセット</h2>
    </div>
    
    <div class="request_form_container">
        
        <form action="<?php echo $base_url; ?>/pwreset/requestFormFin" method="post">
            <input type="hidden" name="_token" value="<?php echo $_token; ?>">
            
            <?php if (isset($errors['error'])) : ?>
                <?php echo '<p class="error">' . $errors['error'] . '</p>'; ?>
            <?php endif; ?>
            
            <div class="request_form">
                <input type="text" name="email" value="" placeholder="メールアドレス" autofocus>
            </div>
            
            <?php if (isset($errors['email'])) : ?>
                <?php echo '<p class="error">' . $errors['email'] . '</p>'; ?>
            <?php endif; ?>
            
            <div class="request_button">
                <button type="submit" name="submit" value="submit">登録する</button>
            </div>
            
        </form>
        
    </div>
    <!-- request_form_container -->
    
    <div class="request_link_container">
        <div class="request_link">
            <a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/user/signup">アカウント登録へ</a>
        </div>
        <div class="request_link">
            <a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/login/signin">ログイン画面へ</a>
        </div>
    </div>
    
</div>
<!-- request_container -->
