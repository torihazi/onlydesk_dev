<?php $this->setLayoutVar('title', 'ユーザ登録') ?>

<!-- <div class="signup_wrapper"> -->

    <div class="signup_container">
    
        <div class="signup_title_container">
            <h2>新規登録</h2>
        </div>

        <div class="signup_form_container">

            <form action="<?php echo $base_url; ?>/user/signupFin" method="post">
                <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

                <div class="signup_form">
                    <input type="text" name="name" value="" placeholder="名前(15文字以内)" autofocus>
                </div>

                <?php if (isset($errors['name'])) : ?>
                    <?php echo '<p class="error">' . $errors['name'] . '</p>'; ?>
                <?php endif; ?>

                <div class="signup_form">
                    <input type="text" name="email" value="" placeholder="メールアドレス">
                </div>

                <?php if (isset($errors['email'])) : ?>
                    <?php echo '<p class="error">' . $errors['email'] . '</p>'; ?>
                <?php endif; ?>

                <div class="signup_form">
                    <input type="password" name="password" value="" placeholder="パスワード(8文字以上32文字以内)">
                </div>

                <?php if (isset($errors['password'])) : ?>
                    <?php echo '<p class="error">' . $errors['password'] . '</p>'; ?>
                <?php endif; ?>

                <div class="signup_button">
                    <button type="submit" name="submit" value="submit">登録</button>
                </div>

            </form>

        </div> 
        <!-- signup_form_container -->

        <div class="signup_link_container">
            <div class="signup_link">
                <a class="line_animation opacity_animation" href="<?php echo $base_url; ?>/login/signin" >ログイン画面へ</a>
            </div>
        </div>

    </div>
    <!-- signup_container -->

<!-- </div> -->
<!-- signup_wrapper -->