<?php
/**
 * Created by PhpStorm.
 * User: soenke
 * Date: 22.11.14
 * Time: 17:21
 */

class Db {

    private $dbFile = DB_DIR;
    private $db;

    function __construct() {
        if (!file_exists($this->dbFile)) {
            $this->db = new SQLite3($this->dbFile);
            // status 0 => delete
            // status 1 => offline
            // status 2 => online
            $this->db->exec('CREATE TABLE files (id INTEGER PRIMARY KEY AUTOINCREMENT, code VARCHAR, file VARCHAR, status INTEGER, delete_time DATE, delete_now INTEGER)');
        } else {
            $this->db = new SQLite3($this->dbFile);
        }
    }

    function save(array $data) {
        $code = $this->db->escapeString($data['code']);
        $file = $this->db->escapeString($data['file']);
        $date = $data['date'];
        $now = $data['now'];
//        echo("INSERT INTO files (code, file, status, delete_time, delete_now) VALUES ('{$code}', {$file}, '2', '{$date}', '{$now}')");
        $this->db->exec("INSERT INTO files (code, file, status, delete_time, delete_now) VALUES ('{$code}', '{$file}', '2', '{$date}', '{$now}')");
        $this->db->close();
    }

    function read($code) {
        $results = $this->db->query("SELECT * FROM files WHERE code = '{$code}' AND status = '2'");
        $row = $results->fetchArray();
        return $row;
    }

    function deleteRow($id) {
        $results = $this->db->query("DELETE FROM files WHERE id = {$id}");
    }





} 