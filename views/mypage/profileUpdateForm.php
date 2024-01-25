<?php $this->setLayoutVar('title', 'プロフィール更新') ?>

<h2 class="page_title">プロフィール更新</h2>


<form class="form_container profile" method="post" enctype="multipart/form-data" action="<?php echo $base_url; ?>/mypage/profileUpdateFormFin">
    <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

    <div class="form_explain_container">

        <div class="form_title">アイコン画像</div>

        <div class="form_info_container">
            <span class="form_info_notice">※画像形式 jpeg/png</span><br>
            <span class="form_info_notice">※容量 3MB以下 </span><br>
            <span class="form_info_notice">※丸形 </span><br>
        </div>
        
        <?php if (isset($errors['image'])) : ?>
            <?php echo '<p class="error">' . $errors['image'] .'</p>'; ?>
        <?php endif; ?>
        <?php if (isset($errors['error'])) : ?>
            <?php echo '<p class="error">' . $errors['error'] .'</p>'; ?>
        <?php endif; ?>

    </div>
    
    <!-- トリミングが終わり次第、表示切替 -->
    <div class="form_input_container profile first" id="form_input_file_container">
        <div class="form_input_wrapper profile">
            <div class="form_input_box profile <?php if(! $icon_image_file) echo 'is_open'; ?>" id="form_input_box">
                <label for="input_file" class="label_input" >ファイルを選択</label>
                <input id="input_file" type="file" class="is_hidden" name="up_image">
            </div>
        </div>

        <div class="form_input_wrapper profile">
            <div class="cropped_image_container profile" id="cropped_image_container">
                <img id="cropped_icon" class="cropped_icon" alt="トリミング結果" src="#">
                <button id="remove_cropped_image_button" class="remove_image_button profile" type="button">&times</button>
            </div>
        </div> 

        <div class="form_input_wrapper profile">
            <div class="now_image_container profile <?php if($icon_image_file) echo 'is_open'; ?>" id="now_image_container">
                <img class="now_image profile" id="icon_file_image" src="<?php echo UPLOAD_DIR_FOR_PROFILE_ICON_IMAGES_RELATIVE . $icon_image_file; ?>">
                <label class="remove_image_button profile" for="remove_now_image_button">&times</label><input id="remove_now_image_button" type="checkbox" name="delete_flg" value="1" style="display: none;">
            </div>
        </div>
    </div>
        
        
    <!-- モーダルウィンドウ -->
    <div class="modal_layer" id="js_modal">
        <div id="modal_container" class="modal_container ">
        
            <div id="modal_content" class="modal_content_container">
            
                <div class="modal_title">画像トリミング</div>
            
                <div class="cropping_image_container">
                    <img id="cropping_image" class="cropping_image" alt="プレビュー画像" >
                </div>
            
                <div class="modal_button_container">
            
                    <button id="crop_fin_button" class="crop_fin_button" type="button">トリミング</button>
                    <button id="crop_cancel_button" class="crop_cancel_button" type="button">キャンセル</button>
            
                </div>
            
            </div>
            
        </div>
    </div>
    <!-- モーダルウィンドウ -->

    <div class="form_explain_container">

        <div class="form_title">
            名前
        </div>

        <div class="form_info_container">
            <span class="form_info_required">※必須</span>
        </div>

        <?php if (isset($errors['name'])) : ?>
            <?php echo '<p class="error">' . $errors['name'] .'</p>'; ?>
        <?php endif; ?>

    </div>

    <div class="form_input_container">

        <input id="name_form" type="text" class="name_form" name="name" value="<?php echo $name; ?>">
    </div>

    <div class="form_explain_container">

        <div class="form_title">
            メールアドレス
        </div>

        <div class="form_info_container">
            <span class="form_info_required">※必須</span>
        </div>

        <?php if (isset($errors['email'])) : ?>
            <?php echo '<p class="error">' . $errors['email'] .'</p>'; ?>
        <?php endif; ?>

    </div>


    <div class="form_input_container">

        <input id="email_form" type="email" class="email_form" name="email" value="<?php echo $email; ?>">
    
    </div>

    <div class="form_explain_container">
        
        <div class="form_title">
            性別
        </div>

    </div>

    <div class="form_input_container">
        
        <select class="select_sex_type" name="sex_type">

             <?php for ($i = 0; $i < count(SEX_TYPE); $i++): ?>

                <?php if( $i == $sex_type): ?>

                    <?php echo '<option value="' . $i . '" selected >' . SEX_TYPE[$i] . '</option>'; ?>

                <?php else: ?>

                    <?php echo '<option value="' . $i . '">' . SEX_TYPE[$i] . '</option>'; ?>

                    <?php endif; ?>

            <?php endfor; ?>
            
        </select>

    </div>

    <div class="form_explain_container">

        <div class="form_title">
            年代
        </div>

    </div>

    <div class="form_input_container">

       <select class="select_age_type" name="age_type">

            <?php for ($j = 0; $j < count(AGE_TYPE); $j++): ?>

                <?php if( $j == $age_type): ?>

                    <?php echo '<option value="' . $i . '" selected >' . AGE_TYPE[$j] . '</option>'; ?>

                <?php else: ?>

                    <?php echo '<option value="' . $i . '" >' . AGE_TYPE[$j] . '</option>'; ?>

                <?php endif; ?>

            <?php endfor; ?>

        </select>

    </div>

    <div class="submit_button_container profile">

        <label for="submit_button" class="submit_button_label">投稿する</label>
        <input id="submit_button" class="submit_button" type="submit" name="submit" value="submit" style="display: none;">

    </div>

</form>

<style type="text/css">
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
</style>

<script src="/js/profileIconImageTrim.js"></script>
