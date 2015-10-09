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
            $this->db->exec('CREATE TABLE files (id INTEGER PRIMARY KEY AUTOINCREMENT, code VARCHAR, file VARCHAR, status INTEGER, delete_time INTEGER, delete_now INTEGER)');
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
    }

    function getDeleteCandidates($date) {
        $results = $this->db->query("SELECT * FROM files WHERE delete_time < '{$date}' OR status = 0");
        $rows = array();
        while($result = $results->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $result;
        }
        return $rows;
    }

    function cleanOldEntries($date) {
        $this->db->exec("DELETE FROM files WHERE delete_time < '{$date}' OR status = 0");
    }

    function update($id, array $data) {
        $q = array();
        foreach ($data as $key => $value) {
            $v = $this->db->escapeString($value);
            $q[] = "{$key} = '{$v}'";
        }
        $query = implode(", ", $q);
        $this->db->exec("UPDATE files SET {$query} WHERE id = {$id}");
    }

    function read($code) {
        $results = $this->db->query("SELECT * FROM files WHERE code = '{$code}' AND status = '2'");
        $row = $results->fetchArray();
        return $row;
    }

    function deleteRow($id) {
        $results = $this->db->exec("DELETE FROM files WHERE id = {$id}");
    }

    function __destruct() {
        $this->db->close();
    }





} 