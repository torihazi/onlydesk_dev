<?php

class FollowRepository extends DbRepository 
{

    protected $table = "follow"; 


    /**
     * function followテーブルに値を挿入
     * @param int user_id
     * @param int aritcle_id
     * @return none
     */
    public function insert ($user_id, $follow_id)
    {
        $sql = "INSERT INTO 
                    $this->table(user_id, follow_id, create_at, update_at)
                VALUES
                    (:user_id, :follow_id, now(), now())
                ";

        return $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':follow_id' => $follow_id,
        ));
    }

    /**
     * function フォローを削除する
     * @param int user_id
     * @param int follow_id
     * @return none
     */

    public function delete ($user_id, $follow_id)
    {
        $sql = "DELETE FROM 
                    $this->table
                WHERE 
                    user_id = :user_id 
                AND 
                    follow_id = :follow_id
                ";
        return $stmt = $this->execute($sql, array(
            ':user_id' => $user_id,
            ':follow_id' => $follow_id,
        ));
    }

    /**
     * function フォ
     * ロー数をカウント
     * @param int user_id
     * @return none
     */

    public function followCount ($user_id)
    {
        $sql = "SELECT
                    COUNT(*) as cnt
                FROM
                    $this->table
                WHERE 
                    user_id = :user_id 
                ";

        $row = $this->fetch($sql, array(':user_id' => $user_id));

        return $row['cnt'];
    }

    /**
     * function フォロワー数をカウント
     * @param int user_id
     * @return none
     */

    public function followerCount ($follow_id)
    {
        $sql = "SELECT
                    COUNT(*) as cnt
                FROM
                    $this->table
                WHERE 
                    follow_id = :follow_id 
                ";

        $row = $this->fetch($sql, array(':follow_id' => $follow_id));

        return $row['cnt'];
    }

    /**
     * function フォローをしているか調べる
     * @param int user_id
     * @param int follow_id
     * @return boolean
     */
    public function isFollow ($user_id, $follow_id)
    {
        $sql = "SELECT 
                    COUNT(*) as cnt
                FROM
                     $this->table
                WHERE
                    user_id = :user_id
                AND
                    follow_id = :follow_id
                ";

        $row = $this->fetch($sql, array(':user_id' => $user_id, ':follow_id' => $follow_id));

        if($row['cnt'] == 1){
            return true;
        }

        return false;
    }

    

}