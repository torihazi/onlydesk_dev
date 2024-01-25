<?php

class ArticleRepository extends DbRepository
{

    protected $table = "article"; 
    
    //写真投稿
    public function insert($user_id, $title, $detail = null)
    {

        $sql = "INSERT INTO 
                    article
                        (user_id, title, detail, is_delete, create_at, update_at)
                VALUES
                        (:user_id, :title, :detail, :is_delete, now(), now())
                ";
        $stmt = $this->execute($sql, array(
                ':user_id' => $user_id,
                ':title' => $title,
                ':detail' => $detail,
                ':is_delete' => 0,
                ));

        return $this->con->lastInsertId();

    }

    /**
     * function 投稿記事テーブルの更新
     * @params article_id
     * @params user_id
     * @params title
     * @params detail
     * @return none
     */
    public function update($article_id, $user_id, $title, $detail)
    {
        $sql = "UPDATE
                    $this->table
                SET
                    title = :title, detail = :detail, update_at = now()
                WHERE
                    id = :article_id
                AND
                    user_id = :user_id
                ";

        return $stmt = $this->execute($sql,array(
            ':title' => $title,
            ':detail' => $detail,
            ':article_id' => $article_id,
            ':user_id' => $user_id,
        ));
    }

    /**
     * function 投稿テーブルとユーザテーブルをjoinした後、各値に応じて動的に設定したSQLをDBに発行し、結果を取得する
     * @params $wheres プリペアドステートメントに仕込む変数とその値を格納した配列
     * @params $all fetchALLで取るか、fetchで取るかの判別
     * @params $limit SQLのLIMITを発動させるか否か(固定値15)
     * @params $offset ページャで使用予定(まだミカン)
     * @return articleTB+userTB
     */
    public function selectForArticles($wheres = null, $all = true, $limit = null, $offset = null){

        $sql = "SELECT 
                    a.id, a.user_id, u.name, u.icon_image_file, a.title, a.detail, a.create_at
                FROM 
                    $this->table a
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.id
                ";

        if(is_array($wheres)){

            $sql .= " WHERE ";
            $a_column_names = array('id', 'user_id', 'title', 'detail', 'create_at');
            $u_column_names = array('name');

            foreach($wheres as $key => $val){

                //$wheres の キー名が投稿TB側のものだった場合
                if(in_array($key, $a_column_names)){
                    
                    $sql .= "a.$key = :$key";
                
                }

                //$wheres の キー名がユーザTB側のものだった場合
                if(in_array($key, $u_column_names)){
                
                    $sql .= "u.$key = :$key";
                
                }

                //配列の最後の要素でない場合、WHEREを付与してループを継続
                if($key !== array_key_last($wheres)){
                
                    $sql .= " AND ";
                
                }
            }
        }

        //最新順
        $sql .= " ORDER BY a.id DESC";

        if($limit){

            $sql .= " LIMIT " . ARTICLE_LIMIT;

        }

        if($offset > 0){

            $sql .= " OFFSET " . $offset;
            
        }



        if($all){

            return $this->fetchAll($sql, $wheres);
        
        }

        return $this->fetch($sql, $wheres);
    }

    /**
     * function 投稿テーブルと投稿記事テーブルをjoinした後、各値に応じて動的に設定したSQLをDBに発行し、結果を取得する
     * @params $wheres プリペアドステートメントに仕込む変数とその値を格納した配列
     * @params $all fetchALLで取るか、fetchで取るかの判別
     * @params $limit SQLのLIMITを発動させるか否か(固定値15)
     * @params $offset ページャで使用予定(まだミカン)
     * @return articleTB+articleImageTB
     */
    public function selectForArticleImages($wheres = null, $all = true, $limit = null, $offset = null){

        $sql = "SELECT 
                    a.id, i.article_image_file,i.article_image_index
                FROM 
                    $this->table a
                LEFT JOIN
                    article_image i
                ON
                    a.id = i.article_id
                ";

        if(is_array($wheres)){

            $sql .= " WHERE ";
            $a_column_names = array('id', 'user_id');
            $i_column_names = array('article_image_file', 'article_image_index');

            foreach($wheres as $key => $val){

                //$wheres の キー名が投稿TB側のものだった場合
                if(in_array($key, $a_column_names)){
                    
                    $sql .= "a.$key = :$key";
                
                }

                //$wheres の キー名がユーザTB側のものだった場合
                if(in_array($key, $i_column_names)){
                
                    $sql .= "i.$key = :$key";
                
                }

                //配列の最後の要素でない場合、WHEREを付与してループを継続
                if($key !== array_key_last($wheres)){
                
                    $sql .= " AND ";
                
                }
            }
        }

        //最新順
        $sql .= " ORDER BY a.id DESC";

        if($limit){

            $sql .= " LIMIT " . ARTICLE_LIMIT;

        }

        if($offset > 1){

            $sql .= " OFFSET " . $offset;
            
        }


        if($all){
            return $this->fetchAll($sql, $wheres);
        
        }

        return $this->fetch($sql, $wheres);
    }



    /**
     * function 詳細検索用(フリーワード、カテゴリ(追加予定)、)
     * @params $wheres 検索カテゴリ
     * @params $limit 表示上限件数(固定値)
     * @params $offset どこから抽出するか
     * @return 検索結果
     */
    public function searchArticle($wheres = null, $limit = null, $offset = null){

        $sql = "SELECT 
                    a.id, a.user_id, u.name, u.icon_image_file, a.title, a.detail, a.create_at
                FROM 
                    $this->table a
                LEFT JOIN
                    user u
                ON
                    a.user_id = u.id
                ";

        if(is_array($wheres)){

            $sql .= " WHERE ";
            $binds = array();

            foreach($wheres as $key => $val){

                switch ($key){

                    case 'freeword':

                            $sql .= "u.name LIKE :keyword1 OR a.title LIKE :keyword2 OR a.detail LIKE :keyword3";
                            $binds[':keyword1'] = "%" . $val . "%";
                            $binds[':keyword2'] = "%" . $val . "%";
                            $binds[':keyword3'] = "%" . $val . "%";

                        break;

                }

                if($key !== array_key_last($wheres)){

                    $sql .= " AND ";
                }
            }

        }

        if($limit){

            $sql .= " LIMIT " . ARTICLE_LIMIT;

        }

        if($offset > 1){
            
            $sql .= " OFFSET " . $offset;
            
        }

        return $this->fetchAll($sql,$binds);

    }


}