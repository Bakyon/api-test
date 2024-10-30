<?php
class UserRepository
{
    private Connection $conn;

    public function __construct()
    {
        $this->conn = Connection::getDatabase();
    }

    public function getAllUsers() {
        $users = $this->conn->set_query('SELECT * FROM users')->load_rows();
        if (empty($users)) {
            return false;
        } else {
            return $users;
        }
    }
    public function getUserById($id) {
        $user = $this->conn->set_query('SELECT * FROM users WHERE id = ?')->load_row([$id]);
        if (empty($user)) {
            return false;
        } else {
            return $user;
        }
    }
    public function addUser(array $params) {
        $fields = '';
        $values = '';
        foreach ($params as $key => $value) {
            $fields .= $key . ",";
            $values .= "'" . $value . "',";
        }
        $fields = rtrim($fields, ",");
        $values = rtrim($values, ",");
        $this->conn->set_query("INSERT INTO users ($fields) VALUES ($values)")->save();
        return $this->getUserById($this->conn->last_insert_id());
    }
    public function updateUser($id, array $params) {
        $set = '';
        foreach ($params as $key => $value) {
            $set .= $key . " = '" . $value . "',";
        }
        $set = rtrim($set, ",");
        $result = $this->conn->set_query("UPDATE users SET $set WHERE id=$id")->save();
        return $result->rowCount();
    }

    public function deleteUser($id) {
        $result = $this->conn->set_query("DELETE FROM users WHERE id=$id")->save();
        return $result->rowCount();
    }
}