<?php

/**
 *  Model User Class
 */
class ModelUser {

    private $_id;

    private $_name;

    private $_email;

    private $_password;

    private $_icon_image_path;

    private $_sex_type;

    private $_sex_name;

    private $_age_type;

    private $_age_name;

    private $_is_delete;

    private $_is_follow;

    public function __construct($user_id)
    {
        // ユーザ情報を取得
        $user = $this->get_user($user_id);

        $this->_id = $user['id'];
        $this->_name = $user['name'];
        $this->_email = $user['email'];
        $this->_password = $user['password'];
        $this->_icon_image_path = $user['icon_image_file'];
        $this->_sex_type = $user['sex_type'];
        $this->_age_type = $user['age_type'];
        $this->_is_delete = $user['is_delete'];

        // 性別名称を取得
        $this->_sex_name = SEX_TYPE[$user['sex_type']];

        // 年代名称を取得
        $this->_age_name = AGE_TYPE[$user['age_type']];

        return $user;

    }

    /**
     * getter（id）
     */
    public function get_id()
    {
        return $this->_id;
    }

    /**
     * getter（name）
     */
    public function get_name()
    {
        return $this->_name;
    }

    /**
     * getter（email）
     */
    public function get_email()
    {
        return $this->_email;
    }

    /**
     * getter（password）
     */
    public function get_password()
    {
        return $this->_password;
    }

    /**
     * getter (sex_type)
     */
    public function get_sex_type()
    {
        return $this->_sex_type;
    }

    /**
     * getter (sex_name)
     */
    public function get_sex_name()
    {
        return $this->_sex_name;
    }

    /**
     * getter (age_type)
     */
    public function get_age_type()
    {
        return $this->_age_type;
    }

    /**
     * getter (age_name)
     */
    public function get_age_name()
    {
        return $this->_age_name;
    }

    /**
     * getter (icon_image_path)
     */
    public function get_icon_image_path()
    {
        return $this->_icon_image_path;
    }

    /**
     * getter (is_delete)
     */
    public function get_is_delete()
    {
        return $this->_is_delete;
    }


    /**
     * ユーザ情報取得する
     * 
     * @param int $user_id ユーザID
     * @return array ユーザ情報
     */
     public function get_user($user_id = null) 
     {

        $dsn = 'mysql:dbname=LAA1518677-ida1;host=mysql214.phy.lolipop.lan';
        $db_user = 'LAA1518677';
        $db_pass = 'kamaseinuida1';
        $options = array();

        try {
            $pdo = new PDO($dsn, $db_user, $db_pass, $options);
        } catch (PDOException $e) {
            exit('データベース接続失敗。' . $e->getMessage());
        }

        $sql = "SELECT * FROM user WHERE id = " . $user_id;
        $stmt = $pdo->query($sql);

        // 値を取得
        $user = $stmt->fetch();

        return $user;

    }

    /**
     * 退会済みユーザか確認
     * @param none
     * @return boolean
     */
    public function isValid()
    {
        if ($this->_is_delete === 1) {

            return true;

        }

        return false;
    }
}



