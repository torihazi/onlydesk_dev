<?php

class PostController extends Controller
{
    //全ユーザの投稿一覧を取得
    public function listAction()
    {
        //ログインユーザ情報の初期値
        $user = null;

        //ページIDがが1より大きい場合、$page_idを変える
        $page_id = 1;

        if (! empty($this->request->getGet('pg')) ){
            if ($this->request->getGet('pg') > 1) {

                $page_id = $this->request->getGet('pg');
            }
        }

        //投稿の総レコード数を取得
        $total_record = $this->db_manager->get('Article')->CountByVar();

        //OFFSET用の値を生成
        if ($page_id === 1) {

            $offset = 0;

        } else {

            $offset = ($page_id - 1 ) * ARTICLE_LIMIT;

        }

        //投稿テーブルを参照し、全件取得
        $articles = $this->db_manager->get('Article')->selectForArticles(null, true, true, $offset);
        
        //投稿画像テーブルを参照し、全件取得
        $article_images = $this->db_manager->get('Article')->selectForArticleImages(null, true, true, $offset);

        //ユーザがログインしているかによってnullを入れるか判別
        if (! empty($this->session->get('user'))) {

            $user = $this->session->get('user');

        } 

        foreach ($articles as $article) {
            foreach ($article_images as $article_image) {

                //投稿画像とユーザ情報挿入(投稿画像idと投稿記事idが等しい)
                if ($article['id'] == $article_image['id']) {
                    
                    //投稿画像挿入(1枚のみ挿入)
                    $article['image'] = $article_image['article_image_file'];

                    if ($user) {

                        //記事idとユーザidを元にいいねしているかをtrue, falseで格納
                        $article['is_good'] = $this->db_manager->get('Good')->isGood($user['id'], $article['id']);

                    } else {

                        $article['is_good'] = false;
                    }

                    //記事idを元に、いいね数をカウント
                    $good_count = $this->db_manager->get('Good')->goodCount($article['id']); 
                    $article['good_count'] = $good_count['cnt'];

                }
            }
            $new_articles[] = $article;
        }

        //投稿なしの場合
        if (empty($new_articles)) {

            $new_articles = array();
        
        }

        return $this->render(array(
            'articles' => $new_articles,
            'current_page' => $page_id,
            'total_record' => $total_record,
        )); 

    }

    //一覧画面における検索フォームから入力された値を元に、検索結果を表示する
    public function searchAction()
    {

        $user = null;

        //ページIDがが1より大きい場合、$page_idを変える
        $page_id = 1;

        if (! empty($this->request->getGet('pg'))) {
            if ($this->request->getGet('pg') > 1) {

                $page_id = $this->request->getGet('pg');
            }
        }

        //OFFSET用の値を生成
        $offset = ($page_id == 1) ? 0 : ($page_id - 1 ) * ARTICLE_LIMIT;

        //検索用カテゴリ配列の初期化
        $array_for_search = array();

        //フリーワード検索の際に使用
        $array_for_search['freeword'] = null;
    
        //検索用カテゴリ内のフリーワード配列に格納
        if (! empty($this->request->getGet('freeword'))) {

            $array_for_search['freeword'] = $this->request->getGet('freeword');
        
        }

        //検索結果(投稿テーブルに対して)
        $articles = $this->db_manager->get('Article')->searchArticle($array_for_search, true, $offset);

        //投稿の総レコード数を取得
        $total_record = count($this->db_manager->get('Article')->searchArticle($array_for_search));

        //投稿画像テーブルを参照し、全件取得
        $article_images = $this->db_manager->get('Article')->selectForArticleImages(null, true, null, null);

        //ログインユーザ情報の格納
        if (! empty($this->session->get('user'))) {

            $user = $this->session->get('user');

        }

        foreach ($articles as $article){
            foreach($article_images as $article_image){

                //投稿画像とユーザ情報挿入(投稿画像idと投稿記事idが等しい)
                if($article['id'] == $article_image['id'] ){
                    
                    //投稿画像挿入(1枚のみ挿入)
                    $article['image'] = $article_image['article_image_file'];

                    if($user){

                        //記事idとユーザidを元にいいねしているかをtrue, falseで格納
                        $article['is_good'] = $this->db_manager->get('Good')->isGood($user['id'], $article['id']);

                    } else {

                        $article['is_good'] = false;
                    }

                    //記事idを元に、いいね数をカウント
                    $good_count = $this->db_manager->get('Good')->goodCount($article['id']); 
                    $article['good_count'] = $good_count['cnt'];

                }
            }
            $new_articles[] = $article;
        }

        //投稿なしの場合
        if(empty($new_articles)) 
            $new_articles = array();

        return $this->render(array(
            'articles' => $new_articles,
            'current_page' => $page_id,
            'total_record' => $total_record,
        ), 'list'); 

    }

    //投稿の詳細画面を表示
    public function showAction()
    {

        //ログインユーザ情報の初期値
        $user = null;

        //ユーザがログインしているかによってnullを入れるか判別
        if (! empty($this->session->get('user'))) {

            $user = $this->session->get('user');

        } 


        //GETパラメータから取得した投稿記事id
        $article_id = $this->request->getGet('article_id');

        //投稿テーブルに対して
        $article = $this->db_manager->get('Article')->selectForArticles(array('id' => $article_id), false);

        //投稿画像テーブルに対して
        $article_image = $this->db_manager->get('Article')->selectForArticleImages(array('id' => $article_id), false);

        //先ほど取得した投稿記事($article)に画像を挿入
        $article['image'] = $article_image['article_image_file'];

        if ($user) {

            //記事idとユーザidを元にいいねしているかをtrue, falseで格納
            $article['is_good'] = $this->db_manager->get('Good')->isGood($user['id'], $article['id']);

        } else {

            $article['is_good'] = false;

        }

        //記事idを元に、いいね数をカウント
        $good_count = $this->db_manager->get('Good')->goodCount($article['id']); 
        $article['good_count'] = $good_count['cnt'];

        return $this->render(array(
            'user_id' => $article['user_id'],
            'article_id' => $article['id'],
            'image' => $article['image'],
            'icon_image_file' => $article['icon_image_file'],
            'user_name' => $article['name'],
            'title' => $article['title'],
            'detail' => $article['detail'],
            'is_good' => $article['is_good'],
            'good_count' => $article['good_count'],
        ));
        
    
    }

    //投稿画面機能(初期表示用)
    public function uploadFormAction()
    {
        return $this->render(array(
            'title' => '',
            'detail' => '',
            'errors' => array(),
            '_token' => $this->generateCsrfToken('/post/uploadForm'),
        ));
    }

    //投稿機能
    public function uploadFormFinAction()
    {

        //csrfのチェック
        $token = $this->request->getPost('_token');
        if (!$this->checkCsrfToken('/post/uploadForm', $token)) {
            
            return $this->redirect('/login/signin');
        
        }

        if (! $this->request->isPost()) {

            return $this->forward404();

        }

        if ($this->request->getPost('submit')) {

            //エラー配列の初期化
            $errors = array();

            //POSTされた中身の確認
            $title = $this->request->getPost('title');
            $detail = $this->request->getPost('detail');
            // $upload_image = $_FILES['up_image'];

            //ログインユーザの情報取得
            $user = $this->session->get('user');

            if (empty($title)) {
           
                $errors['title'] = sprintf(ERR_MSG_CONST_REQUIRED, 'タイトル');
           
            }

            if (! is_uploaded_file($_FILES['up_image']['tmp_name'])) {

                $errors['image'] = sprintf(ERR_MSG_CONST_VIA_POST);

            } elseif ($_FILES['up_image']['size'] === 0) {

                $errors['image'] = sprintf(ERR_MSG_CONST_REQUIRED, '画像');

            } else {

                $compressed_file_path = Util::compress_and_resize_file($_FILES['up_image']['tmp_name'], "article");

                if (! $compressed_file_path) {

                    $errors['image'] = sprintf(ERR_MSG_CONST_UPLOADED);

                } elseif ( filesize($compressed_file_path) > UPLOAD_FILE_MAX_SIZE) {

                   $errors['image'] = sprintf(ERR_MSG_CONST_UPLOADED_SIZE_LIMIT); 

                }

            }

            if (count($errors) > 0) {

                return $this->render(array(
                    'title' => $title,
                    'detail' => '',
                    'errors' => $errors,
                    '_token' => $this->generateCsrfToken('/post/uploadForm'),
                ), 'uploadForm');

            }

            //上のチェックが通らなかったなら以降処理を行わない
            try{
                $this->db_manager->get('Article')->StartTransaction();

                if (! ($result = Util::upload_file($compressed_file_path, basename($compressed_file_path), $user['id'], UPLOAD_DIR_FOR_ARTICLE_IMAGES_ABSOLUTE))) {

                     throw new Exception(sprintf(ERR_MSG_CONST_UPLOADED));

                }

                //投稿記事テーブルへ挿入し、INSERTで挿入された行の通し番号IDを取得する
                $post_data_id = $this->db_manager->get('Article')->insert($user['id'], $title, $detail);

                //投稿画像テーブルへ挿入(投稿記事IDを取得し、それに多の画像が紐づくようにする)
                $stmt = $this->db_manager->get('ArticleImage')->insert($post_data_id, $result['upload_file_name'], 1);
                $this->_logger->info($this->getPreparedStmtAfterBind($stmt));

                $this->db_manager->get('Article')->CommitTransaction();

            } catch (Exception $e) {

                $errors['error'] = $e->getMessage();

                $this->db_manager->get('Article')->backTransaction();

                $this->_logger->error("post failed");

                return $this->render(array(
                    'title' => $title,
                    'detail' => '',
                    'errors' => $errors,
                    '_token' => $this->generateCsrfToken('/post/uploadForm'),
                ), 'uploadForm');

            }

            return $this->render(array(), 'uploadFormFin');
        }
    }
}