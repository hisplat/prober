<?php
include_once(dirname(__FILE__) . "/../../../config.php");
include_once(dirname(__FILE__) . "/../v1_base.php");

class login_controller extends v1_base {

    public function auth_action() {
        $ret = login::do_auth();
        if ($ret == null) {
            return array("op" => "loginfail");
        }
        return array("op" => "login", "next" => "?index/index");
    }

}












