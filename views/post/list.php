<?php $this->setLayoutVar('title', '投稿一覧') ?>

<!-- 投稿写真一覧 -->
<?php if(!empty(Pager::pager($current_page, $total_record))): ?>
    <?php echo $this->render('pagerview', Pager::pager($current_page, $total_record)); ?>
<?php endif; ?>

<div class="article_list">
    <div class="articles_container">
        <?php foreach ($articles as $article): ?>
            <div class="article_box">
                <div class="user_article_image">
                    <a href="<?php echo $base_url; ?>/post/show?article_id=<?php echo $article['id']; ?>">
                        <img class="article_img" src="<?php echo UPLOAD_DIR_FOR_ARTICLE_IMAGES_RELATIVE . $article['image']; ?>">
                    </a>
                </div>

                <div class="user_article_title">
                    <?php echo $article['title']; ?>
                </div>

                <div class="user_profile_container">
                    <div class="user_profile_box">
                        <a class="user_profile_anchor" href="<?php echo $base_url; ?>/mypage/profile?user_id=<?php echo $article['user_id']; ?>">
                        <img class="profile_icon" src="<?php echo UPLOAD_DIR_FOR_PROFILE_ICON_IMAGES_RELATIVE . $article['icon_image_file']; ?>">
                        </a>
                        <span class="user_profile_name"><?php echo $article['name']; ?></span>
                    </div>

                    <div class="user_good_box">
                        <i class="like-btn fa-heart 
                            <?php if ($article['is_good']) : ?>
                                <?php echo ' active fas'; ?>
                            <?php else : ?>
                                <?php echo ' far'; ?>
                            <?php endif; ?>"
                                data-article_id = <?php echo $article['id']; ?>>
                        </i>
                        <span class="good_count 
                            <?php if($article['is_good']) : ?>
                                <?php echo ' active'; ?>
                            <?php endif; ?>">
                                <?php echo $article['good_count']; ?>
                        </span>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<?php if(!empty(Pager::pager($current_page, $total_record))): ?>
    <?php echo $this->render('pagerview', Pager::pager($current_page, $total_record)); ?>
<?php endif; ?>



