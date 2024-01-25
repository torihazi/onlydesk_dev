<?php $this->setLayoutVar('title', '投稿更新') ?>

<h2 class="page_title">投稿更新</h2>

<form class="form_container post" action="<?php echo $base_url; ?>/mypage/articleUpdateFormFin" method="post" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?php echo $_token; ?>">

    <div class="form_explain_container">

        <div class="form_title">写真</div>
        
        <div class="form_info_container">
            <span class="form_info_required">※必須</span><br>
            <span class="form_info_notice">画像形式: JPEG/PNG</span><br>
            <span class="form_info_notice">容量: 3MB 以内</span><br>
            <span class="form_info_notice">サイズ: 1280px &times 720px</span><br>
        </div>

        <?php if (isset($errors['image']) ) : ?>
            <?php echo '<p class="error">' . $errors['image'] .'</p>'; ?>
        <?php endif; ?>
        <?php if (isset($errors['error']) ) : ?>
            <?php echo '<p class="error">' . $errors['error'] .'</p>'; ?>
        <?php endif; ?>

    </div>

    <div class="form_input_container post first" id="form_input_file_container">
        <div class="form_input_wrapper post">
            <div class="form_input_box post is_open" id="form_input_box">
                <label for="input_file" class="label_input" >ファイルを選択</label>
                <input id="input_file" type="file" class="is_hidden" name="up_image">
            </div>
        </div>

        <div class="form_input_wrapper post">
            <div class="cropped_image_container post" id="cropped_image_container">
                <img id="cropped_image" class="cropped_image" alt="トリミング結果" src="#">
                <button id="remove_cropped_image_button" class="remove_image_button post" type="button">&times</button>
            </div>
        </div>

        <div class="form_input_wrapper post">
            <div class="now_image_container is_open post" id="now_image_container">
                <img class="now_image" id="now_post_image" src="<?php echo UPLOAD_DIR_FOR_ARTICLE_IMAGES_RELATIVE . $image; ?>">
                <label class="remove_image_button post" for="remove_now_image_button">&times</label><input id="remove_now_image_button" type="checkbox" name="delete_flg" value=1 style="display: none;">
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
            タイトル
        </div>

        <div clas="form_info_container">
            <span class="form_info_required">※必須</span>
        </div>

        <?php if (isset($errors['title'])) : ?>
            <?php echo '<p class="error">' . $errors['title'] .'</p>'; ?>
        <?php endif; ?>

    </div>
    
    <div class="form_input_container">

        <input class="title_form" type="text"  name="title" placeholder="タイトルを入力する" value="<?php echo $title; ?>">

    </div>


    <div class="form_explain_container">

        <div class="form_title">
            説明
        </div>

    </div>
    
    <div class="form_input_container">

        <textarea class="detail_form" name="detail" placeholder="説明を入力する"><?php echo $detail; ?></textarea>
    
    </div>
    
    
    <div class="submit_button_container post">
    
        <label for="submit_button" class="submit_button_label">投稿する</label>
        <input id="submit_button" class="submit_button" type="submit" name="submit" value="submit" style="display: none;">
    
    </div>

</form>

<script type="text/javascript" src="/js/articleImageTrimForUpdate.js"></script>