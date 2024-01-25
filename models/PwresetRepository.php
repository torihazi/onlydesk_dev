<?php

class PwresetRepository extends DbRepository
{
    protected $table = "pwreset";

    public function insert($email, $pwresetToken)
    {
        $sql = "INSERT INTO
                    $this->table (email, pwresetToken, create_at, update_at)
                VALUES
                    (:email, :pwresetToken, now(), now())
                ";

        return $stmt = $this->execute($sql,array(
                ':email' => $email,
                ':pwresetToken' => $pwresetToken
            ));
    }

    public function update($email, $pwresetToken)
    {
        $sql = "UPDATE
                    $this->table
                SET
                    pwresetToken = :pwresetToken,
                    create_at = now(),
                    update_at = now()
                WHERE
                    email = :email
                ";

        return $stmt = $this->execute($sql,array(
                ':pwresetToken' => $pwresetToken,
                ':email' => $email,
            ));
    }

    public function delete($email)
    {
        $sql = "DELETE FROM
                    $this->table
                WHERE
                    email = :email
                ";

        return $stmt = $this->execute($sql, array(
            'email' => $email
        ));
    }
}