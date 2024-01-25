<?php

class ContactRepository extends DbRepository
{
    protected $table = "contact";
    
    public function insert($email, $title, $qa_type, $content)
    {
        $sql = "INSERT INTO
                    $this->table (email, title, type, content, is_answer, create_at, update_at)
                VALUES
                    (:email, :title, :type, :content, 0, now(), now())
                ";

        $stmt = $this->execute($sql, array(
                ':email' => $email,
                ':title' => $title,
                ':type' => $qa_type,
                ':content' => $content,
            ));

        return $this->con->lastInsertid();
    }
}