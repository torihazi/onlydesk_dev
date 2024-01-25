<?php $this->setLayoutVar('title', '投稿詳細') ?>

<?php if( isset($_SESSION['user']) && $user_id == $_SESSION['user']['id']): ?>
    <a href="<?php echo $base_url; ?>/mypage/articleUpdateForm?article_id=<?php echo $article_id; ?>">投稿を編集する</a>
<?php endif; ?>

<!-- 投稿写真 -->
<div class="article_container">
    <div class="main_flame">
        <div class="main_article_image">
            <img class="main_image" src="<?php echo UPLOAD_DIR_FOR_ARTICLE_IMAGES_RELATIVE . $image; ?>">
        </div>
    </div>

    <div class="side_flame">
        <div class="user_meta_info_container">
            <div class="user_profile_icon">
                <img class="profile_icon" src="<?php echo UPLOAD_DIR_FOR_PROFILE_ICON_IMAGES_RELATIVE . $icon_image_file; ?>">
            </div>
            <div class="user_name">
                <?php echo $user_name; ?>
            </div>
        </div>

        <div class="article_info_container">
            <p><span>タイトル:</span><?php echo $title; ?></p>
            <p><?php echo $detail; ?></p>
        </div>
    </div>

    <div class="content_container">
        <i class="like-btn fa-heart 
            <?php if ($is_good) : ?>
                <?php echo ' active fas'; ?>
            <?php else : ?>
                <?php echo ' far'; ?>
            <?php endif; ?>"
            data-article_id = <?php echo $article_id; ?>>
        </i>
        <span class="good_count
            <?php if($is_good) : ?>
                <?php echo ' active'; ?>
            <?php endif; ?>">
            <?php echo $good_count; ?>
        </span>
    </div>

    
</div>



