<?php

class ArticleImageRepository extends DbRepository
{
    protected $table = 'article_image';

    /**
     * function insert 投稿画像を挿入
     * @params int $article_id 投稿記事id
     * @params string $article_image_file 投稿画像ファイル名
     * @params int $article_image_index 1投稿における通し番号
     * @return なし
     */
    public function insert($article_id, $article_image_file, $article_image_index)
    {
        $sql = "INSERT INTO
                    article_image
                        (article_id, article_image_file, article_image_index, create_at, update_at)
                VALUES
                        (:article_id, :article_image_file, :article_image_index, now(), now())
                ";

        return $stmt = $this->execute($sql, array(
                ':article_id' => $article_id,
                ':article_image_file' => $article_image_file,
                ':article_image_index' => $article_image_index,
                ));
    }

    /**
     * function 画像削除
     * @params string $article_image_file ファイル名
     * @return 
     */

    public function delete($article_id, $article_image_index)
    {
        $sql = "DELETE FROM 
                    $this->table 
                WHERE 
                    article_id = :article_id
                AND 
                    article_image_index = :article_image_index
        ";

        return $stmt = $this->execute($sql, array(
            ':article_id' => $article_id,
            ':article_image_index' => $article_image_index,
        ));

    }

    /**
     * function 投稿に含まれる画像枚数をカウント
     * @param int article_id
     * @return int article_image_sum
     */

    public function countImage($article_id)
    {
        $sql = "SELECT 
                    COUNT(*) as cnt
                FROM
                    $this->table
                WHERE
                    article_id = :article_id
                ";

        return $this->fetch($sql,array(
            ':article_id' => $article_id
        ));

    }

    /**
     * function 画像ファイル名取得
     * @param int article_id
     * @param int article_image_index
     * @return string article_image_file
     */

     public function fetchFileName($article_id, $article_image_index)
     {
        $sql = "SELECT
                    article_image_file
                FROM
                    $this->table
                WHERE
                    article_id = :article_id
                AND
                    article_image_index = :article_image_index
                ";

        return $this->fetch($sql,array(
            'article_id' => $article_id,
            'article_image_index' => $article_image_index,
        ));
     }

     /**
      * function 画像ファイル名更新
      * @param int article_id
      * @param string article_image_file
      * @param int article_image_index
      * @return 無し
      */
     public function updateFileName($article_id, $article_image_file, $article_image_index = 1)
     {

        $sql = "UPDATE 
                    $this->table
                SET
                    article_image_file = :article_image_file,
                    update_at = now()
                WHERE
                    article_id = :article_id
                AND
                    article_image_index = :article_image_index
                ";

        return $stmt = $this->execute($sql,array(
            ':article_image_file' => $article_image_file,
            ':article_id' => $article_id,
            ':article_image_index' => $article_image_index,
        ));
     }

    // public function fetchAllGroupByArticleID($wheres = array())
    // {
    //  $sql = "SELECT * FROM $this->table";

    //  if(count($wheres)){

    //      $sql .= " WHERE ";

    //      foreach ($wheres as $column => $value) {

    //          //条件式をバインド変数でつなげる
    //          $sql .= $column . " = " . ":" . $column;

    //          //配列の最後でなければ、ANDでつなげる
    //          if ( $value !== end($wheres)){

    //              $sql .= " AND ";

    //          }
    //      }
    //  }
        
    //  $sql .= " GROUP BY article_id";

    //  return $this->fetchAll($sql);
    // }

    // public function fetchAllGroupByArticleIDInProfile($user_id)
    // {
    //  $sql = "SELECT
    //              *
    //          FROM
    //              $this->table
    //          WHERE
    //              user_id = :user_id
    //          GROUP BY
    //              article_id
    //          ";

    //  return $this->fetchAll($sql, array(
    //      ':user_id' => $user_id,
    //  ));
    // }
}