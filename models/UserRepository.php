<?php

class UserRepository extends DbRepository
{

    protected $table = "user";

    //一般ユーザ登録
    public function insert($name, $email, $password, $is_delete) 
    {
        $password = $this->hashPassword($password);
        // $now = now();

        $sql = "
            INSERT INTO user(name, email, password, is_delete, create_at, update_at)
            VALUES(:name, :email, :password, :is_delete, now(), now())
            ";

        return $stmt = $this->execute($sql, array(
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':is_delete' => $is_delete,
            ));


    }

    //idを取得して、対応するユーザのアカウントステータスを有効化する
    public function enableUserStatus($id)
    {
        $sql = "UPDATE user SET is_delete = 0 WHERE id = :id";

        $stmt = $this->execute($sql, array(
                ':id' => $id,
            ));
    }

    public function disableUserStatus($id)
    {
        $sql = "UPDATE 
                    user 
                SET 
                    name = '',
                    email = '',
                    password = '',
                    sex_type = 0,
                    age_type = 0,
                    icon_image_file = '',
                    is_delete = 1,
                    update_at = now()
                WHERE 
                    id = :id
                ";


        $stmt = $this->execute($sql, array(
                ':id' => $id
            ));
    }

    
    /**
     * update プロフィール更新
     * @param string name 名前
     * @param string email メールアドレス
     * @param int sex_type 性別(0,1,2)
     * @param int age_type 年代(0,1,...9)
     * @param string icon_image_file 画像ファイルパス
     * @param int id ユーザid
     */

    public function update($name, $email, $sex_type, $age_type, $id, $icon_image_file = null)
    {
        
        $sql = "UPDATE 
                    user 
                SET 
                    name = :name,
                    email = :email,
                    sex_type = :sex_type,
                    age_type = :age_type,
                    icon_image_file = :icon_image_file,
                    update_at = now()
                WHERE 
                    id = :id
                ";

             $stmt = $this->execute($sql, array(
                        ':name' => $name,
                        ':email' => $email,
                        ':sex_type' => $sex_type,
                        ':age_type' => $age_type,
                        ':icon_image_file' => $icon_image_file,
                        ':id' => $id,
                    ));

    }


    public function changePassword($password, $id)
    {
        $password = $this->hashPassword($password);
        
        $sql = "UPDATE 
                    user 
                SET 
                    password = :password 
                WHERE 
                    id = :id"
                ;

        return $stmt = $this->execute($sql, array(
                ':password' => $password,
                ':id' => $id,
                ));
    }


}