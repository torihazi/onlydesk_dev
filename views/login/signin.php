<?php $this->setLayoutVar('title', 'ログイン') ?>

<!-- <div class="signin_wrapper"> -->

    <div class="signin_container">
    
        <div class="signin_title_container">
            <h2>ログイン</h2>
        </div>

        <div class="signin_form_container">

            <form action="<?php echo $base_url; ?>/login/authenticate" method="post">
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

                <?php if (isset($errors['user'])): ?>
                    <?php echo '<p class="error">' . $errors['user'] . '</p>'; ?>
                <?php endif; ?>

                <div class="signin_form"> 
                    <input id='email_form' type="text" name="email" autocomplete="email" value="<?php echo $email; ?>" placeholder="メールアドレス" autofocus>
                </div>
                
                <?php if (isset($errors['email'])) : ?>
                    <?php echo '<p class="error">' . $errors['email'] . '</p>'; ?>
                <?php endif; ?>

                <div class="signin_form">
                    <input type="password" name="password" value="" placeholder="パスワード">
                </div>

                <?php if (isset($errors['password'])) : ?>
                    <?php echo '<p class="error">' . $errors['password'] . '</p>'; ?>
                <?php endif; ?>

                <div class="signin_button">
                    <button type="submit" name="submit" value="submit">ログイン</button>
                </div>

            </form>
        </div>
        <!-- signin_form_container -->

        <div class="signin_link_container">
            <div class="signin_link">
                <a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/user/signup">アカウント登録へ</a>
            </div>
            <div class="signin_link">
                <a  class="line_animation opacity_animation" href="<?php echo $base_url; ?>/pwreset/requestForm">パスワードをお忘れの方</a>
            </div>
        </div>

    </div>
    <!-- signin_container -->

<!-- </div> -->
<!-- signin_wrapper -->