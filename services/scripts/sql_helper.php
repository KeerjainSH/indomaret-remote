<?php

require '../../vendor/autoload.php';

class SQL {
    public function __construct() {
        DB::$user = 'root';
        DB::$password = '';
        DB::$dbName = 'indomaret_db';
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

    public function getAllToko() {
        return DB::query("SELECT * FROM toko");
    }

    public function updateStmast() {
        return DB::update('stmast', [
            'begbal' => 1000,
            'qty' => 1000
        ], '1=1');
    }

    public function updateConst() {
        $values = ["wsb", "ne", "pco"];
        return DB::update('const', [
            'jenis' => "N",
        ], "rkey in %ls", $values);
    }

    public function deleteConst() {
        $value = "ccd";
        return DB::delete('const', "rkey=%s", $value);
    }
}
