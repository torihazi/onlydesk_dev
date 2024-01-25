<?php $this->setLayoutVar('title', 'パスワード再登録'); ?>

<!-- <div class="reset_wrapper"> -->

    <div class="reset_container">

        <div class="reset_title_container">
            <h2>パスワード再登録</h2>
        </div>

        <div class="reset_form_container">

            <form action="<?php echo $base_url; ?>/pwreset/resetFormFin" method="post">
                <input type="hidden" name="_token" value="<?php echo $_token; ?>">
                <input type="hidden" name="pwresetToken" value="<?php echo $pwresetToken; ?>">

                <div class="reset_form">
                    <input type="password" name="new_password" value="" placeholder="新しいパスワード(8文字以上32文字以内)" autofocus>
                </div>

                <?php if (isset($errors['new_password'])) : ?>
                    <?php echo '<p class="error">' . $errors['new_password'] . '</p>'; ?>
                <?php endif; ?>

                <div class="reset_form">
                  <input type="password" name="check_password" value="" placeholder="確認用パスワード">
                </div>

                <?php if (isset($errors['check_password'])) : ?>
                    <?php echo '<p class="error">' . $errors['check_password'] . '</p>'; ?>
                <?php endif; ?>

                <div class="reset_button">
                    <button type="submit" name="submit" value="submit">登録する</button>
                </div>

            </form>

        </div>
        <!-- reset_form_container -->

    </div>
    <!-- reset_container -->

<!-- </div> -->
<!-- reset_wrapper -->
