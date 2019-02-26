<?php
include_once(dirname(__FILE__) . "/../config.php");

class setting_controller {
    public function index_action() {
        $tpl = new tpl("header", "footer");
        // $tpl->set("settings", setting::instance()->load_all());
        $tpl->display("setting/index");
    }
}













