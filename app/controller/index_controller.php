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
}













