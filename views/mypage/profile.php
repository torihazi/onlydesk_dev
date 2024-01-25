<?php $this->setLayoutVar('title', 'プロフィール') ?>

<h2 class="page_title">プロフィール</h2>

<div class="profile_info_box">
    <div class="profile_info_subbox">
        <div ><img class="profile_img" src="<?php echo UPLOAD_DIR_FOR_PROFILE_ICON_IMAGES_RELATIVE . $user['icon_image_file']; ?>"></div>
        <div class="profile_meta_info_box">
            <div class="profile_meta_info"><?php echo $user['name']; ?></div>
            <div class="profile_meta_info">性別: <?php echo SEX_TYPE[$user['sex_type']]; ?></div>
            <div class="profile_meta_info">年代: <?php echo AGE_TYPE[$user['age_type']]; ?></div>
            <div class="profile_meta_info"><span class="follow_num"><?php echo $user['follow_num']; ?></span>フォロー</div>
            <div class="profile_meta_info"><span class="follower_num"><?php echo $user['follower_num']; ?></span>フォロワー</div>
        </div>
    </div>
    
    <?php if(isset($_SESSION['user']) && $_SESSION['user']['id'] !== $user['id']) : ?>
        <div>
            <label class="follow_label 
                <?php if ($user['is_follow'] === true) : ?>
                
                    <?php echo ' follow'; ?>
                
                <?php else : ?>
                
                    <?php echo ' unfollow'; ?>
                
                <?php endif; ?>"

                data-followid="<?php echo $user['id']; ?>">
                <!-- ここまで <label>開始タグの最後 -->
                
                <?php  if ($user['is_follow'] === true) : ?>
                
                    <?php echo "フォロー解除"; ?>
                
                <?php else : ?>
                
                    <?php echo"フォローする"; ?>
                
                <?php endif ;?>
                <!-- ここまでが<label>内のテキスト -->
            </label>
        </div>
    <?php endif; ?>
</div>

<div>
    <div class="articles_wrapper">
        <div class="articles_container">
            <?php foreach ($articles as $article): ?>
                <div class="article_box">
                    <div class="user_article_image">
                        <a href="<?php echo $base_url; ?>/post/show?article_id=<?php echo $article['id']; ?>">
                            <img class="article_img" src="<?php echo UPLOAD_DIR_FOR_ARTICLE_IMAGES_RELATIVE . $article['image']; ?>">
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
