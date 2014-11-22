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

    private function getUrl() {
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

    private function upload() {
        $this->data = array(
            'id' => $this->generateID(),
            'title' => 'Hello'

        );
        $this->template = 'upload';
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