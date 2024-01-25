<?php 

abstract class DbRepository
{
    protected $con;
    protected $table;
    protected $_now;

    public function __construct($con)
    {
        $this->setConnection($con);
    }

    public function setConnection($con)
    {
        $this->con = $con;
    }

    public function startTransaction()
    {
        return $this->con->beginTransaction();
    }

    public function commitTransaction()
    {
        return $this->con->commit();
    }

    public function backTransaction()
    {
        return $this->con->rollback();
    }

    public function execute($sql, $params = array())
    {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);
    
        return $stmt;
    }

    public function fetch($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAll($sql, $params = array())
    {
        return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }

    //パスワードのハッシュ化
    public function hashPassword($password)
    {
        return sha1($password . 'SecretKey');
    }

    // //バインドされた後のプリペアドステートメントを出力(ログ用)
    // public function getPreparedStmtAfterBind($pdo_stmt) {
        
    //     ob_start();
    //     $pdo_stmt->debugDumpParams();
    //     $content = ob_get_contents();
    //     ob_end_clean();
        
    //     preg_match('/Sent\sSQL:\s\[\d+\]\s(.*?)\sParams/s', $content, $matches);

    //     return $matches[1];
    // }

    /**
     * 任意の引数を配列で渡し、SQLクエリを実行し、その結果を
     */

    public function fetchByVar($wheres = null)
    {
        $sql = "SELECT * FROM $this->table ";

        if (is_array($wheres)){

            $sql .= " WHERE ";

            foreach ($wheres as $key => $val) {

                //条件式をバインド変数でつなげる
                $sql .= " $key = :$key";

                //配列の最後でなければ、ANDでつなげる
                if ( $key !== array_key_last($wheres)){
                    $sql .= " AND ";
                }
            }
        }

        return $this->fetch($sql, $wheres);
    }

    /**
     * IDを引数としたfetchAllクエリを実行し、結果をすべて取得
     * 
     * @param string $sql
     * @param array $params
     * @return array
     */ 
    public function fetchALLByVar($wheres = null)
    {
        $sql = "SELECT * FROM $this->table ";

        if (is_array($wheres)){

            $sql .= " WHERE ";

            foreach ($wheres as $key => $value) {

                //条件式をバインド変数でつなげる
                $sql .= $key . " = " . ":" . $key;

                //配列の最後でなければ、ANDでつなげる
                if ( $value !== end($wheres)){

                    $sql .= " AND ";

                }
            }
        }
        
        return $this->fetchAll($sql, $wheres);
    }

    /**
     * 与えた引数でクエリを実行し、ユニークであるか結果を返す
     * 
     * @param string $sql
     * @param $params
     * @return array
     */ 
    public function isUniqueByVar($wheres = array(), $default = false)
    {
        $sql = "SELECT COUNT(*) as num FROM $this->table ";

        if (count($wheres)) {

            $sql .= " WHERE ";

            foreach ($wheres as $column => $value) {

                //条件式をバインド変数でつなげる
                $sql .= $column . " = " . ":" . $column;

                //配列の最後でなければ、ANDでつなげる
                if ( $value !== end($wheres)){

                    $sql .= " AND ";

                }
            }
        }

        // $sql .= " AND is_delete = ";
        // $sql .= $default ? " 1 ":" 0 ";

        $row = $this->fetch($sql, $wheres);
        if ( $row['num'] == 1) {

            return true;
        
        }

        return false;
    }


    /**
     * 与えた引数でクエリを実行し、ヒットした件数を返す
     * 
     * @param string $sql
     * @param $params
     * @return array
     */ 
    public function CountByVar($wheres = array(), $search = false)
    {
        $sql = "SELECT COUNT(*) as num FROM $this->table ";

        if (count($wheres)){

            $sql .= " WHERE ";

            foreach ($wheres as $column => $value) {

                //条件式をバインド変数でつなげる
                $sql .= $column . " = " . ":" . $column;

                //配列の最後でなければ、ANDでつなげる
                if ( $value !== end($wheres)){

                    $sql .= " AND ";

                }
            }
        }

        // $sql .= " AND is_delete = ";
        // $sql .= $default ? " 1 ":" 0 ";

        $row = $this->fetch($sql, $wheres);

        return $row['num'];

    }


}
