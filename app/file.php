<?php
/**
 * Created by PhpStorm.
 * User: soenke
 * Date: 22.11.14
 * Time: 17:21
 */

class File {

    private $names = array();
    private $tmp = array();
    private $uploadDir = DATA_DIR;
    private $files = array();
    private $output = '';
    private $count = 0;

    function __construct($files) {
        $this->names = $files['name'];
        $this->tmp = $files['tmp_name'];
    }

    function createFolder($id) {
        $dir = $this->uploadDir . $id;
        mkdir($dir);
        if (!is_dir($dir)) {
            throw new Exception("Could not create Upload-Folder in " . $this->uploadDir);
        }
        $this->uploadDir = $dir;
    }


    function upload() {
        $this->count = count($this->tmp);

        for ($i=0; $i<$this->count; $i++) {
            $uploadfile = $this->uploadDir . '/' .  basename($this->names[$i]);
            if (!move_uploaded_file($this->tmp[$i], $uploadfile)) {
                throw new Exception("Could not create File " . $uploadfile);
            }
            $this->files[] = $uploadfile;
        }

        if ($this->count > 1) {
            $this->output = $this->zip();
        } else {
            $this->output = $this->files[0];
        }

    }

    function getFilePath() {
        return $this->output;
    }

    function zip() {
        $zip = new ZipArchive();
        $filename = $this->uploadDir . "/archive.zip";

        if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
            throw new Exception("Could not create {$filename}");
        }
        $i = 0;

        foreach ($this->files as $file) {
            $zip->addFile($file,$this->names[$i]);
            $i++;
        }
        $zip->close();

        return $filename;

    }





} 