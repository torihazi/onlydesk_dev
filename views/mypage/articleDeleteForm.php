<?php $this->setLayoutVar('title', '投稿編集') ?>
<h2>投稿編集</h2>

<form action="<?php echo $base_url; ?>/mypage/articleUpdateFormFin" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="<?php echo $_token; ?>">
	<input type="hidden" name="article_id" value="<?php echo $article_id; ?>">

	<p>タイトル:<input type="text" name="title" value="<?php echo $title; ?>"></p>
	<p>内容: <textarea name="content" ><?php echo $content; ?></textarea></p>
	<p>画像:
		<?php for ($i = 0; $i < count($image); $i++): ?>
			<label>
				<input type="checkbox" name="image_del[]" value="<?php echo $image[$i]; ?>">
					<img src="<?php echo UPLOAD_DIR_FOR_ARTICLE_IMAGES_RELATIVE . $image[$i]; ?>">
			</label>
		<?php endfor;?>
	</p>
	<p>新規画像:<input type="file" name="image[]" accept="image/*" multiple>
		


	<p>
		<button type="submit" name="submit" value="submit">更新</button>
		<button type="submit" name="submit" value="delete" formaction="<?php echo $base_url; ?>/mypage/articleDeleteForm>">投稿を削除</button>
	</p>

</form>