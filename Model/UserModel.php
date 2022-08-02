<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";
 
class UserModel extends Database
{
    public function getUsers($limit)
    {
        return $this->select("SELECT * FROM user ORDER BY user_key ASC LIMIT ?", ["i", $limit]);
    }

    public function getUsersByKey($user_key)
    {
        return $this->select("SELECT * FROM user WHERE user_key=?", ['i', $user_key]);
    }

    public function getUsersByName($user_name)
    {
        return $this->select("SELECT * FROM user WHERE user_name=?", ['s', $user_name]);
    }

}