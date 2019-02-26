<?php
include_once(dirname(__FILE__) . "/../config.php");

class index_controller {
    public function index_action() {
        $tpl = new tpl("header", "footer");
        $tpl->display("index/index");
    }

    public function info_action() {
        $token = get_request_assert("token");
        $_SESSION["info.token"] = $token;
        $tpl = new tpl();
        $tpl->display("index/info");
    }

    public function test_action() {
        $tpl = new tpl("header", "footer");
        $tpl->display("index/test");
    }

    public function upload_action() {
        $token = get_request_assert("token");

        $ret = uploadFile($token, function($filename, $token) {
            $ret = db_info::inst()->update_filename($token, $filename);
            if ($ret === false) {
                return "fail|数据库操作失败.";
            }
            return "success";
        }, $token);
        return $ret;
    }
}













