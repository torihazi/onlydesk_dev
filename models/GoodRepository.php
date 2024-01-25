<?php

class GoodRepository extends DbRepository 
{

    protected $table = "good"; 


    /**
     * function いいねテーブルに値を挿入
     * @param int user_id
     * @param int aritcle_id
     * @return none
     */
    public function insert ($user_id, $article_id)
    {
        $sql = "INSERT INTO 
                    $this->table(user_id, article_id, create_at, update_at)
                VALUES
                    (:user_id, :article_id, now(), now())
                ";

        return $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':article_id' => $article_id,
        ));
    }

    /**
     * function いいねを削除する
     * @param int user_id
     * @param int article_id
     * @return none
     */

    public function delete ($user_id, $article_id)
    {
        $sql = "DELETE FROM 
                    $this->table
                WHERE 
                    user_id = :user_id 
                AND 
                    article_id = :article_id
                ";
        return $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':article_id' => $article_id,
        ));
    }

    /**
     * function いいね数をカウント
     * @param int user_id
     * @param int article_id
     * @return none
     */

    public function goodCount ($article_id)
    {
        $sql = "SELECT
                    COUNT(*) as cnt
                FROM
                    $this->table
                WHERE 
                    article_id = :article_id 
                ";

        return $this->fetch($sql, array(
            ':article_id' => $article_id,
        ));
    }


    /**
     * function いいねをしているか調べる
     * @param int user_id
     * @param int article_id
     * @return boolean
     */
    public function isGood ($user_id, $article_id)
    {
        $sql = "SELECT 
                    COUNT(*) as cnt
                FROM
                     $this->table
                WHERE
                    user_id = :user_id
                AND
                    article_id = :article_id
                ";

        $row = $this->fetch($sql, array(':user_id' => $user_id, ':article_id' => $article_id));

        if($row['cnt'] == 1){
            return true;
        }

        return false;
    }

    

}