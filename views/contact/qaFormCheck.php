<?php $this->setLayoutVar('title', 'お問い合わせ確認') ?>

<div class="qa_container">

    <div class="qa_title_container">
        <h2>問い合わせ内容確認</h2>
        <p>下記の内容で送信します。<br>よろしければ送信を押してください</p>
    </div>

    <div class="qa_form_container">

        <form action="<?php echo $base_url; ?>/contact/qaFormFin" method="post">
            <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="title" value="<?php echo $title; ?>">
            <input type="hidden" name="qa_type" value="<?php echo $qa_type; ?>">
            <input type="hidden" name="content" value="<?php echo $content; ?>">

            <div class="qa_form">
                <div class="qa_form_title">メールアドレス</div>
                <div class="qa_check"><?php echo $email; ?></div>
            </div>


            <div class="qa_form">
                <div class="qa_form_title">件名</div>
                <div class="qa_check"><?php echo $title; ?></div>
            </div>
            
            <div class="qa_form">
                <div class="qa_form_title">問い合わせ種別</div>
                <div class="qa_check"><?php echo QA_TYPE[$qa_type]; ?></div>
            </div>
            
            <div class="qa_form">
                <div class="qa_form_title">お問い合わせ内容</div>
                <div class="qa_check qa_check_content"><?php echo $content; ?></div>
           </div>

            <div class="qa_button_box">
                <div class="qa_fin_button">
                    <button type="submit" name="submit" value="fin">送信する</button>
                </div>
                <div class="qa_check_button">
                    <button type="submit" name="submit" value="check">修正する</button>
                </div>
            </div>
        </form>

    </div>
    <!-- contact_form_container -->

</div>
<!-- contact_container -->