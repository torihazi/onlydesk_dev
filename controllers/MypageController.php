<?php

class MypageController extends Controller
{

    //プロフィール画面表示用
    public function profileAction()
    {    

        $user_id = $this->request->getGet('user_id');

        $is_follow = null;

        if (! empty($this->session->get('user'))) {
            $login_user = $this->session->get('user');
        }
        
        $user = $this->db_manager->get('User')->fetchByVar(array('id' => $user_id, 'is_delete' => 0));

        if (! $user) {
            $this->forward404();
        }

        //フォローテーブルを参照し、ログイン中のユーザが参照先のユーザをフォローしているか
        if (! empty($this->session->get('user')) && $user_id != $login_user['id']) {
            $is_follow = $this->db_manager->get('Follow')->isFollow($login_user['id'], $user_id);
        }

        if (! is_null($is_follow)) {
            $user['is_follow'] = $is_follow;
        }

        //フォロー数
        $user['follow_num'] = $this->db_manager->get('Follow')->followCount($user_id);

        //フォロワー数
        $user['follower_num'] = $this->db_manager->get('Follow')->followerCount($user_id);

        //投稿テーブルを参照し、表示しているプロフィールのユーザidに合致する投稿を取得
        $articles = $this->db_manager->get('Article')->selectForArticles(array('user_id' => $user['id']));
        
        //投稿画像テーブルを参照し、$articlesで取得してきた各記事idに合致する投稿画像を取得
        $article_images = $this->db_manager->get('Article')->selectForArticleImages(array('user_id' => $user['id']));

        foreach ($articles as $article) {
            foreach ($article_images as $article_image) {

                //投稿画像とユーザ情報挿入(投稿画像idと投稿記事idが等しい)
                if ($article_image['id'] == $article['id']) {

                    //投稿画像挿入(1枚のみ挿入)
                    $article['image'] = $article_image['article_image_file'];

                }
            }
            $new_articles[] = $article;
        }

        //投稿なしの場合
        if (empty($new_articles)) {
            $new_articles = array();
        }

        $this->_logger->info("profile $user_id");

        return $this->render(array(
                'user' => $user,
                'articles' => $new_articles,
        ));
    }

    //投稿編集画面表示用
    public function articleUpdateFormAction()
    {
        //投稿記事idを$GETパラメータから取得
        $article_id = $this->request->getGet('article_id');

        // SESSIONを参照し、article_idに値があれば、それと差し替える
        // SESSIONを参照し、article_image_pathがあれば、それと差し替える


        //投稿テーブルを参照し、投稿記事idに合致する投稿を取得
        $article = $this->db_manager->get('Article')->selectForArticles(array('id' => $article_id), false);
        
        //投稿画像テーブルを参照し、表示しているプロフィールのユーザidに合致する投稿画像を取得
        $article_image = $this->db_manager->get('Article')->selectForArticleImages(array('id' => $article_id), false);

        //投稿画像挿入(複数枚)
        $article['image'] = $article_image['article_image_file'];

        //投稿記事をSESSIONに格納。(改ざんを防ぐため)
        $this->session->set('article_id', $article_id);
        $this->session->set('article_image_path', $article['image']);

        return $this->render(array(
            'title' => $article['title'],
            'detail' => $article['detail'],
            'image' => $article['image'],
            '_token' => $this->generateCsrfToken('/mypage/articleUpdateForm')
        ));

    }

    //投稿編集更新用
    public function articleUpdateFormFinAction()
    {
        //csrfの確認
        // $token = $this->request->getPost('_token');
        // if(!this->checkCsrfToken('/mypage/articleUpdateForm', $token)){
        //     return $this->redirect('/mypage/articleUpdateForm');
        // }

        //POSTされたかを確認
        if($this->request->getPost('submit')){

            //エラー配列の初期化
            $errors = array();

            $user = $this->session->get('user');
            $title = $this->request->getPost('title');
            $detail = $this->request->getPost('detail');

            $article_id = $this->session->get('article_id');
            $article_image_path = $this->session->get('article_image_path');

            //現アイコンの削除ボタンを押したか
            if ( $this->request->getPost('delete_flg')) {
                $delete_flg = $this->request->getPost('delete_flg');
            }

            //タイトル未入力チェック
            if(empty($title)){

                $errors[] = sprintf(ERR_MSG_CONST_REQUIRED,'タイトル');
            }

            //アップロードするファイル情報確認
            if (isset($delete_flg) && empty($_FILES['up_image']['tmp_name'])) {

                $errors['image'] = sprintf(ERR_MSG_CONST_REQUIRED, '画像');

            } elseif (! is_uploaded_file(($_FILES['up_image']['tmp_name']))) {

                $errors['image'] = sprintf(ERR_MSG_CONST_VIA_POST);

            } else {

                $compressed_file_path = Util::compress_and_resize_file($_FILES['up_image']['tmp_name'], "article");

                if (! $compressed_file_path) {

                    $errors['image'] = sprintf(ERR_MSG_CONST_UPLOADED);

                } elseif ( filesize($compressed_file_path) > UPLOAD_FILE_MAX_SIZE) {

                   $errors['image'] = sprintf(ERR_MSG_CONST_UPLOADED_SIZE_LIMIT); 

                }

                $has_uploaded_file = true;

            }

            if (count($errors) > 0) {

                 return $this->render(array(
                     'title' => $title,
                     'image' => $article_image_path,
                     'errors' => $errors,
                     '_token' => $this->generateCsrfToken('mypage/articleUpdateForm'),
                 ) ,'articleUpdateForm');
             }

            try{

                $this->db_manager->get('Article')->startTransaction();

                if (isset($has_uploaded_file)) {

                    if(! ($result = Util::upload_file($compressed_file_path, basename($compressed_file_path), $user['id'], UPLOAD_DIR_FOR_ARTICLE_IMAGES_ABSOLUTE))) {

                        throw new Exception(sprintf(ERR_MSG_CONST_UPLOADED));

                    }

                    //DBに対して画像の差し替え(UPDATE)をかける
                    $stmt = $this->db_manager->get('ArticleImage')->updateFileName($article_id, $result['upload_file_name']);
                    $this->_logger->info($this->getPreparedStmtAfterBind($stmt));
                    $stmt = $this->db_manager->get('Article')->update($article_id, $user['id'], $title, $detail);
                    $this->_logger->info($this->getPreparedStmtAfterBind($stmt));


                    //全画像の削除を行う
                    $previous_image_path = UPLOAD_DIR_FOR_ARTICLE_IMAGES_ABSOLUTE . $article_image_path;

                    if (! unlink($previous_image_path)) {

                        throw new Exception(sprintf(ERR_MSG_CONST_IMG_DELETE));

                    }
                } else {

                    //画像差し替えがない場合はテキスト系の更新のみ
                    $stmt = $this->db_manager->get('Article')->update($article_id, $user['id'], $title, $detail, $icon_image_file);
                    $this->_logger->info($this->getPreparedStmtAfterBind($stmt));


                }

                $this->db_manager->get('Article')->commitTransaction();

                $this->session->remove('article_id');
                $this->session->remove('article_image_path');

                return $this->render(array());

            } catch (Exception $e) {

                $this->db_manager->get('Article')->backTransaction();

                $errors['error'] = $e->getMessage();

                $this->_logger->error("article update failed");

                return $this->render(array(
                    'title' =>$title,
                    'errors' => $errors,
                     'image' => $article_image_path,
                    '_token' => $this->generateCsrfToken('/mypage/articleUpdateForm'),
                    ), 'articleUpdateForm');

            }
        }
    }


    //投稿削除確認画面表示用
    public function articleDeleteCheckFormAction()
    {
        echo "<pre>";
        var_dump($_POST);exit;
    }

    
    //プロフィール更新画面表示用
    public function profileUpdateFormAction()
    {

        $user = $this->session->get('user');

        if (! $user) {
            $this->forward404();
        }

        return $this->render(array(
                'icon_image_file' => $user['icon_image_file'],
                'name' => $user['name'],
                'email' => $user['email'],
                'sex_type' => $user['sex_type'],
                'age_type' => $user['age_type'],
                'errors' => array(),
                '_token' => $this->generateCsrfToken('/mypage/profileUpdateForm'),
        ));

    }

    //プロフィール画面更新用
    public function profileUpdateFormFinAction()
    {
        if (! $this->request->isPost()) {

            return $this->forward404();

        }

        //csrfのチェック
        $token = $this->request->getPost('_token');
        if(!$this->checkCsrfToken('/mypage/profileUpdateForm', $token)){
            return $this->redirect('/mypage/profileUpdateForm');
        }

        if($this->request->getPost('submit')){

            //エラー配列の初期化
            $errors = array();

            $user = $this->session->get('user');
            $name = $this->request->getPost('name');
            $email = $this->request->getPost('email');
            $sex_type = $this->request->getPost('sex_type');
            $age_type = $this->request->getPost('age_type');

            //現アイコンの削除ボタンを押したか
            if ( $this->request->getPost('delete_flg')) {
                $delete_flg = $this->request->getPost('delete_flg');
            }

            //名前未入力チェック
            if (empty($name)) {

                $errors['name'] = sprintf(ERR_MSG_CONST_REQUIRED, '名前');

            }
            //名前文字数チェック
            elseif (mb_strlen($name) >= 20) {

                $errors['name'] = sprintf(ERR_MSG_CONST_RENGTH, '名前', MSG_CONST_NAME_RENGTH);

            }
            //名前未重複チェック(他人の名前で登録していないか)
            elseif ($name !== $user['name'] && $this->db_manager->get('User')->isUniqueByVar(array('name' => $name, 'is_delete' => 0))) {

                $errors['name'] = sprintf(ERR_MSG_CONST_REGISTERD, '名前');

            }

            //メールアドレス未入力チェック
            if (empty($email)) {

                $errors['email'] = sprintf(ERR_MSG_CONST_REQUIRED, 'メールアドレス'); 
            
            }
            //メールアドレス形式チェック
            elseif (! filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $errors['email'] = sprintf(ERR_MSG_CONST_ADDRESS_CORRECTED);

            }
            //メールアドレス未重複チェック(他人のメールアドレスで登録していないか。)
            elseif ($email !== $user['email'] && $this->db_manager->get('User')->isUniqueByVar(array('email' => $email, 'is_delete' => 0))) {

                $errors['email'] = sprintf(ERR_MSG_CONST_REGISTERD, 'メールアドレス');
            
            }

            //アップロードするファイル情報確認
            if (isset($delete_flg) && empty($_FILES['up_image']['tmp_name'])) {

                $icon_image_file = "";

            } elseif (! is_uploaded_file(($_FILES['up_image']['tmp_name']))) {

                $errors['image'] = sprintf(ERR_MSG_CONST_VIA_POST);

            } else {


                $compressed_file_path = Util::compress_and_resize_file($_FILES['up_image']['tmp_name'], "icon");

                if (! $compressed_file_path) {

                    $errors['image'] = sprintf(ERR_MSG_CONST_UPLOADED);

                } elseif ( filesize($compressed_file_path) > UPLOAD_FILE_MAX_SIZE) {

                   $errors['image'] = sprintf(ERR_MSG_CONST_UPLOADED_SIZE_LIMIT); 

                }

                $has_uploaded_file = true;

            }

            if (count($errors) > 0) {

                $this->_logger->error("profile update failed");

                return $this->render(array(
                    'icon_image_file' => $user['icon_image_file'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'sex_type' => $user['sex_type'],
                    'age_type' => $user['age_type'],
                    'errors' => $errors,
                    '_token' => $this->generateCsrfToken('/mypage/profileUpdateForm'),
                ), 'profileUpdateForm');
            }

            try {

                //トランザクション開始
                $this->db_manager->get('User')->startTransaction();

                if (isset($has_uploaded_file)) {

                    if(! ($result = Util::upload_file($compressed_file_path, basename($compressed_file_path), $user['id'], UPLOAD_DIR_FOR_PROFILE_ICONS_ABSOLUTE))) {

                        throw new Exception(sprintf(ERR_MSG_CONST_UPLOADED));

                    }

                    $stmt = $this->db_manager->get('User')->update($name, $email, $sex_type, $age_type, $user['id'], $result['upload_file_name']);

                    $this->_logger->info($this->getPreparedStmtAfterBind($stmt));

                    if (! empty($user['icon_image_file'])) {

                        $previous_icon_path = UPLOAD_DIR_FOR_PROFILE_ICONS_ABSOLUTE . $user['icon_image_file'];

                        if (! unlink($previous_icon_path)) {

                            throw new Exception(sprintf(ERR_MSG_CONST_IMG_DELETE));

                        }

                    }


                } else {

                    $stmt = $this->db_manager->get('User')->update($name, $email, $sex_type, $age_type, $user['id'], $icon_image_file);
                    $this->_logger->info($this->getPreparedStmtAfterBind($stmt));

                    $previous_icon_path = UPLOAD_DIR_FOR_PROFILE_ICONS_ABSOLUTE . $user['icon_image_file'];

                    if (! unlink($previous_icon_path)) {

                        throw new Exception(sprintf(ERR_MSG_CONST_IMG_DELETE));

                    }

                }

                //現時点におけるログインユーザの情報を取得
                $update_user = $this->db_manager->get('User')->fetchByVar(array('id' => $user['id'], 'is_delete' => 0));

                //UPDATEしたログインユーザ情報をsessionクラスに格納している。
                $this->session->set('user', $update_user);

                $this->db_manager->get('User')->commitTransaction();

                return $this->render(array());

            } catch (Exception $e) {

                $this->db_manager->get('User')->backTransaction();

                //サーバ上の画像ファイル削除処理も入れないと。

                $errors['error'] = $e->getMessage();

                $this->_logger->error("profile update failed");

                return $this->render(array(
                    'name' => $name,
                    'email' => $email,
                    'sex_type' => $sex_type,
                    'age_type' => $age_type,
                    'errors' => $errors,
                    '_token' => $this->generateCsrfToken('/mypage/profileUpdateForm'),
                ), 'profileUpdateForm');

            }
        }
    }

    //プロフィールパスワード更新フォーム
    public function pwChangeFormAction()
    {
        $user = $this->session->get('user');

        return $this->render(array(
                'id' => $user['id'],
                'errors' => array(),
                '_token' => $this->generateCsrfToken('/mypage/pwChangeForm'),
        ));
    }

    //プロフィールパスワード更新完了フォーム
    public function pwChangeFormFinAction()
    {
        //csrfのチェック
        $token = $this->request->getPost('_token');
        if(!$this->checkCsrfToken('/mypage/pwChangeForm', $token)){
            return $this->redirect('/mypage/pwChangeForm');
        }

        if($this->request->getPost('submit')){

            //エラー格納配列の初期化
            $errors = array();

            $user= $this->session->get('user');

            //POSTされた値の取得
            $current_pwd = $this->request->getPost('current_pwd'); 
            $new_pwd = $this->request->getPost('new_pwd'); 
            $chk_pwd = $this->request->getPost('chk_pwd');
            
            //入力された現パスワードをハッシュ化
            $hash_pwd = $this->db_manager->get('User')->hashPassword($current_pwd);

            //パスワードの未入力チェック
            if (empty($current_pwd) || empty($new_pwd) || empty($chk_pwd)) {

                $errors['error'] = sprintf(ERR_MSG_CONST_REQUIRED, 'パスワード');
            
            }
            //新規パスワードの文字数チェック
            elseif (mb_strlen($new_pwd) <= 7 || 31 <= mb_strlen($new_pwd)) {

                $errors['new_pwd'] = sprintf(ERR_MSG_CONST_PASSWORD_RENGTH, '新規パスワード', MSG_CONST_PASSWORD_RENGTH_FROM, MSG_CONST_PASSWORD_RENGTH_TO);
            
            }
            //現パスワードが合っているかチェック
            elseif ( !($this->db_manager->get('User')->isUniqueByVar(array('id' => $user['id'], 'password' => $hash_pwd)))) {

                $errors['current_pwd'] = sprintf(ERR_MSG_CONST_CORRECTED, '現在のパスワード');
            
            }
            //確認用パスワードと合致チェック
            elseif (strcmp($new_pwd, $chk_pwd) != 0) {

                $errors['check_pwd'] = sprintf(ERR_MSG_CONST_CORRECTED, '確認用パスワード');
            
            }

            //エラーがない場合
            if(0 >= count($errors)){

                //パスワードの更新をかける
                $result = $this->db_manager->get('User')->changePassword($new_pwd, $user['id']);
                $this->_logger->info($this->getPreparedStmtAfterBind($result));

                //現時点におけるログインユーザの情報を取得
                $update_user = $this->db_manager->get('User')->fetchByVar(array('id' => $user['id']));

                //パスワード更新に成功したユーザ情報を再度、sessionクラスに格納している。
                $this->session->set('user', $update_user);

                $this->_logger->info("password change success");

                return $this->render(array(),'pwChangeFormFin');
                
            }

            $this->_logger->error("password change failed");

            
            return $this->render(array(
                'current_pwd' => '',
                'new_pwd' => '',
                'chk_pwd'=> '',
                'errors' => $errors,
                '_token' => $this->generateCsrfToken('/mypage/pwChangeForm'),
            ), 'pwChangeForm');
        }
    }

    //サービス退会画面表示(初期アクセス時)
    public function quitFormAction()
    {
        $content = '';

        return $this->render(array(
            '_token' => $this->generateCsrfToken('/mypage/quitForm'),
            'content' => $content,
        ));
    }

    //サービス退会確認画面表示
    public function quitFormCheckAction()
    {
        //csrfのチェック
        // $token = $this->request->getPost('_token');
        // if(!$this->checkCsrfToken('/mypage/quitForm', $token)){
        //     return $this->redirect('/mypage/quitForm');
        // }

        $user = $this->session->get('user');
        $content = $this->request->getPost('content');


        return $this->render(array(
            'id' => $user['id'],
            'content' => $content,
            '_token' => $this->generateCsrfToken('/mypage/quitFormCheck'),
        ));

    }

    //サービス退会完了画面表示、退会処理
    public function quitFormCheckFinAction()
    {
        // //csrfのチェック
        // $token = $this->request->getPost('_token');
        // if(!$this->checkCsrfToken('/mypage/quitFormCheck', $token)){
        //     return $this->redirect('/post/list');
        // }

        //POSTされたボタン押下ステータス取得
        $submit_status = $this->request->getPost('submit');

        //退会するボタンを押下
        if ($submit_status == 'fin'){

            //IDを元にDBを検索し、一致したレコードの削除フラグを立てる
            $this->db_manager->get('User')->disableUserStatus($user['id']);

            // ログインユーザの$_SESSION情報を削除
            $this->session->clear();
            $this->session->setAuthenticated(false);

            $this->_logger->info("quit success");

            return $this->render(array());

        }
        //キャンセルボタンを押下
        elseif ($submit_status == 'cancel'){

            $content='';

            return $this->render(array(
                '_token' => $this->generateCsrfToken('/mypage/quitForm'),
                'content' => $content,
            ), 'quitForm');
        }
    }
}