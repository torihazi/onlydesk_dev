<?php


class ApiController extends Controller
{
    public $code;

    public $res;

    public $errors;

    public function goodAjaxAction()
    {

        $user = $this->session->get('user');
        $article_id = $this->request->getPost('article_id');
        $errors = array();

        //ログイン判別
        if (! isset($user)) {

            $res = array("is_login" => false);
            $this->code = $res;

            //Json形式で返却
            $json = json_encode($this->code);
            header('Content-type: application/json; charset=utf-8');
            echo $json;
            exit;

        } else {

            $res = array("is_login" => true);

        }

        //SESSIONに格納されたユーザIDをもつユーザがDB上に存在するか
        if (empty($this->db_manager->get('User')->fetchByVar(array('id' => $user['id'])))) {

            $errors[] = array("user_info" => "no user in database");

        }

        //POSTされた$article_diはnullではないか
        //nullでない場合、そのarticle_idをもつ記事はDB上に存在するか
        if (! isset($article_id)) {

            $errors[] = array("article_id" => "article_id none");

        } elseif (empty($this->db_manager->get('Article')->fetchByVar(array('id' => $article_id)))) {

            $errors[] = array("article_info" => "no article in database");

        }

        //上記エラーが存在した場合、いいねの処理を実行しない
        if (count($errors) > 0) {

            $this->code = $errors;
            $json = json_encode($this->code);

            //Json形式で返却
            $json = json_encode($this->code);
            header('Content-type: application/json; charset=utf-8');
            echo $json;
            exit;
        }

        try {

            $is_good = false;

            //いいねしているか
            if (! empty($this->db_manager->get('Good')->fetchByVar(array('user_id' => $user['id'], 'article_id' => $article_id)))) {

                $is_good = true;

            }

            // していないなら削除、しているなら追加
            if ($is_good) {

                $stmt = $this->db_manager->get('Good')->delete($user['id'], $article_id);
                $this->_logger->info($this->getPreparedStmtAfterBind($stmt));

            } else {

                $stmt = $this->db_manager->get('Good')->insert($user['id'], $article_id);
                $this->_logger->info($this->getPreparedStmtAfterBind($stmt));

            }

            //レスポンス用のデータ作成
            $good_count = $this->db_manager->get('Good')->goodCount($article_id);
            $res['is_good'] = $is_good;
            $res['good_count'] =  $good_count['cnt'];

            $this->code = $res;

        } catch (Exception $e){

            $errors = array('error' => $e->getMessage());
            $this->code = $errors;
        }

        $json = json_encode($this->code);

        //Json形式で返却
        header('Content-type: application/json; charset=utf-8');
        echo $json;
        exit;
    }


    public function followajaxAction()
    {
        //フォローされたユーザID
        $follow_id = !empty($_POST['follow_id']) ? $_POST['follow_id'] : throw new ('follow_id empty');

        try {

            if (empty($follow_id)){ 
                throw new Exception('error');
            }

            //セッションからログインユーザ情報の取得
            $user = !empty($this->session->get('user')) ? $this->session->get('user') : throw new Exception('user none');

            //取得したログインユーザのIDは存在するか
            if (!($this->db_manager->get('User')->fetchByVar(array('id' => $user['id'])))) {

                throw new Exeption('Illegal User ID');

            }

            //フォローされたユーザIDは存在するか
            if(!($this->db_manager->get('User')->fetchByVar(array('id' => $follow_id)))) {

                throw new Exception("Illegal followed user ID");
            
            }
                
            //フォローをすでにしているか
            if ($this->db_manager->get('Follow')->isFollow($user['id'], $follow_id)) {

                $isfollow = true;

            } else {

                $isfollow = false;

            }

            //していないなら削除、しているなら追加
            if($isfollow){

                $stmt = $this->db_manager->get('Follow')->delete($user['id'], $follow_id);
                // $this->logger->info($this->getPreparedStmtAfterBind($stmt);
            
            }else{
            
                $stmt = $this->db_manager->get('Follow')->insert($user['id'], $follow_id);
                // $this->logger->info($this->getPreparedStmtAfterBind($stmt);

            }

            //レスポンス用のデータ作成
            $res['isfollow'] = $isfollow;
            $res['follow_num'] = $this->db_manager->get('Follow')->followCount($follow_id);
            $res['follower_num'] = $this->db_manager->get('Follow')->followerCount($follow_id);

            $this->code = $res;

        } catch (Exception $e){

            $this->code = array('error' => $e->getMessage());
        }

        $this->code = $res;
        $json = json_encode($this->code);

        //Json形式で返却
        header('Content-type: application/json; charset=utf-8');
        echo $json;
        exit;
    }

}