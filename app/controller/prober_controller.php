<?php
include_once(dirname(__FILE__) . "/../config.php");

class prober_controller {

    public function grant_action() {
        $token = md5(uniqid(md5(microtime(true)), true));
        $token = substr($token, 0, 32);
        echo "$token";
    }

}













