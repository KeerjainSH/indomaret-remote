<?php

require '../../vendor/autoload.php';

class SQL {
    public function __construct() {
        DB::$user = 'root';
        DB::$password = '';
        DB::$dbName = 'crud_db';
        DB::$host = '127.0.0.1';
        DB::$encoding = 'utf8';
    }

    public function createUser($name, $email) {
        DB::insert('users', [
            'name' => $name,
            'email' => $email
        ]);
        return DB::insertId();
    }

    public function getUsers() {
        return DB::query("SELECT * FROM users");
    }

    public function updateUser($id, $name, $email) {
        return DB::update('users', [
            'name' => $name,
            'email' => $email
        ], "id=%d", $id);
    }

    public function deleteUser($id) {
        return DB::delete('users', "id=%d", $id);
    }
}
