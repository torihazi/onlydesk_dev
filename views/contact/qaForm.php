<?php $this->setLayoutVar('title', 'お問い合わせ') ?>


<h2 class="page_title">お問い合わせ</h2>
<p><span class="about_astrisk">*の項目は必須項目です</span></p>

<div class="qa_container">

    <div class="qa_form_container">

        <form action="<?php echo $base_url; ?>/contact/qaFormCheck" method="post">
            <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

            <?php if (isset($errors['error'])) : ?>
                <?php echo '<p class="error">' . $errors['error'] . '</p>'; ?>
            <?php endif; ?>

            <div class="qa_form">
                <div class="qa_form_title form_required">メールアドレス</div>
                <input type="text" name="email" placeholder="メールアドレス" value="<?php echo $email; ?>"autofocus>
                
                <?php if (isset($errors['email'])) : ?>
                    <?php echo '<p class="error">' . $errors['email'] . '</p>'; ?>
                <?php endif; ?>
                
                <p class="qa_explain">
                    お問い合わせに対する返答を受け取ることができるメールアドレスをご入力ください。<br>
                    ご入力いただいたメールアドレスに対して管理者からメールが届きます。
                </p>
            </div>


            <div class="qa_form">
                <div class="qa_form_title form_required">件名</div>
                <input type="text" name="title" placeholder="件名" value="<?php echo $title; ?>">

                <?php if (isset($errors['title'])) : ?>
                    <?php echo '<p class="error">' . $errors['title'] . '</p>'; ?>
                <?php endif; ?>

            </div>
            
            <div class="qa_form">
                <div class="qa_form_title form_required">問い合わせ種別</div>
                <div class="qa_select_wrapper qa_select_border_design">
                    <select name="qa_type">
                    
                        <?php for ($i = 0; $i < count(QA_TYPE); $i++): ?>
                    
                            <?php if( $i == $qa_type): ?>
                    
                                <?php echo '<option value="' . $i . '" selected >' . QA_TYPE[$i] . '</option>'; ?>
                    
                            <?php else: ?>
                    
                                <?php echo '<option value="' . $i . '">' . QA_TYPE[$i] . '</option>'; ?>
                    
                            <?php endif; ?>
                    
                        <?php endfor; ?>
                    
                    </select>
                </div>
    
                <?php if (isset($errors['qa_type'])) : ?>
                    <?php echo '<p class="error">' . $errors['qa_type'] . '</p>'; ?>
                <?php endif; ?>
    
            </div>
            
            <div class="qa_form">
                <div class="qa_form_title form_required">お問い合わせ内容</div>
                <textarea class="qa_textarea" name="content"  placeholder="本文を入力" wrap="soft"></textarea>
    
                <?php if (isset($errors['content'])) : ?>
                    <?php echo '<p class="error">' . $errors['content'] . '</p>'; ?>
                <?php endif; ?>
    
                <p class="qa_explain">
                    問い合わせ種別に対応項目が存在しない場合は"その他"を選択ください。<br>
                    またその詳細についてはこちらに記載いただきますようお願いいたします。<br>
                    機能追加等のご要望についてもこちらにて詳細を記載ください。
                </p>
            </div>

            
            <div class="qa_form">
                <button class="qa_button" type="submit" name="submit" value="check">確認画面へ</button>
            </div>
        </form>

    </div>
    <!-- contact_form_container -->

</div>
<!-- contact_container -->