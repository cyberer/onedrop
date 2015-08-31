<?php
/**
 * Created by PhpStorm.
 * User: soenke
 * Date: 22.11.14
 * Time: 15:07
 */

class App {
    private $url;
    private $data = array();
    private $template = 'index';
    private $delete = array(
        1 => 'weeks',
        2 => 'days',
        3 => 'weeks',
        4 => 'months',
    );

    function __construct($url) {
        $this->url = trim($url, '/');
        $this->route();
    }

    private function route() {
        switch ($this->url) {
            case '':
                $this->index();
                break;
            case 'upload':
                $this->upload();
                break;
            default:
                $this->download();
                break;
        }
    }

    public function getUrl() {
        return $this->url;
    }

    private function generateID() {
        return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);
    }


    private function index() {
        $this->data = array(
            'id' => $this->generateID(),
            'title' => 'Hello'

        );
        $this->template = 'index';
    }

    private function error() {
        $this->data = array(
            'title' => 'Ooopsi'
        );
        $this->template = 'error';
    }

    private function upload() {
        if (!$_POST['form']['url'] && !$_FILES['form']['name']) {
            $this->error();
        }
        $db = new Db();
        $url = $_POST['form']['url'];
        $url = str_replace('/', '', $url);
        $result = $db->read($url);
        if (!empty($result)) {
            $url = $this->generateID();
        }

        $file = new File($_FILES['form']);
        $file->createFolder($url);
        $file->upload();
        $path = $file->getFilePath();

        $delete = intval($_POST['form']['deleteAfter']);

        $db = new Db();
        $db->save(array(
                'code' => $url,
                'file' => $path,
                'date' => strtotime("+1 " . $this->delete[$delete]),
                'now' => ($delete == 1 ? 1 : 0),
            ));

        $this->data = array(
            'file' => 'http://' . $_SERVER['HTTP_HOST'] . '/' . $url,
            'title' => 'your Link'

        );
        $this->template = 'upload';
    }

    private function download() {
        $db = new Db();
        $result = $db->read($this->url);

        if (empty($result)) {
            $this->error();
            return;
        }

        $file = str_replace(DOC_DIR, '', $result['file']);

        header("Location: " . $file);
        die;
    }


    function render() {
        $template = new PHPTAL('index.html');
        $template->setOutputMode(PHPTAL::HTML5);
        $template->setTemplateRepository(__DIR__ . "/view");
        $template->title = "index";
        $template->upload = false;
        $template->template = $this->template;
        $template->data = $this->data;
        return $template->execute();
    }



} 